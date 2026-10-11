<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Thin client for the Quran Foundation OAuth2 content API
 * (https://api-docs.quran.foundation). Knows nothing about this app's
 * own data (students, memorization records) — purely talks to the API.
 */
class QuranFoundationClient
{
    private const TOKEN_CACHE_KEY = 'quran_foundation:access_token';
    private const TOKEN_LOCK_KEY = 'quran_foundation:token_lock';
    private const TRANSLATION_ID_CACHE_KEY = 'quran_foundation:translation_id:id';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $tokenUrl,
        private readonly string $apiBaseUrl,
    ) {
    }

    /**
     * Get a valid access token, auto-refreshing shortly before expiry.
     * Uses a cache lock so concurrent requests don't all fetch a new
     * token at the same time.
     */
    public function getAccessToken(): string
    {
        if ($cached = Cache::get(self::TOKEN_CACHE_KEY)) {
            return $cached;
        }

        return Cache::lock(self::TOKEN_LOCK_KEY, 10)->block(5, function () {
            if ($cached = Cache::get(self::TOKEN_CACHE_KEY)) {
                return $cached;
            }

            return $this->fetchAndCacheToken();
        });
    }

    private function fetchAndCacheToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post($this->tokenUrl, [
                'grant_type' => 'client_credentials',
                'scope' => 'content',
            ])->throw();

        $data = $response->json();

        // No refresh_token in client-credentials flow; refresh a little
        // before actual expiry as recommended by Quran Foundation's docs.
        $ttl = max(30, (int) ($data['expires_in'] ?? 3600) - 30);

        Cache::put(self::TOKEN_CACHE_KEY, $data['access_token'], $ttl);

        return $data['access_token'];
    }

    private function forceRefreshToken(): string
    {
        Cache::forget(self::TOKEN_CACHE_KEY);

        return Cache::lock(self::TOKEN_LOCK_KEY, 10)->block(5, fn () => $this->fetchAndCacheToken());
    }

    /**
     * GET against the content API, retrying once with a fresh token if
     * the current one was rejected (401).
     */
    private function authenticatedGet(string $path, array $query = []): array
    {
        $doRequest = fn (string $token) => Http::baseUrl($this->apiBaseUrl)
            ->withHeaders([
                'x-auth-token' => $token,
                'x-client-id' => $this->clientId,
            ])->get($path, $query);

        $response = $doRequest($this->getAccessToken());

        if ($response->status() === 401) {
            $response = $doRequest($this->forceRefreshToken());
        }

        return $response->throw()->json();
    }

    /**
     * All verses of one chapter (every page merged into a single array),
     * cached near-permanently per chapter + translation since Qur'an
     * text/translations never change.
     *
     * @return array<int, array>
     */
    public function getVersesByChapter(int $chapterNumber, int $translationResourceId): array
    {
        $cacheKey = "quran_foundation:verses:{$chapterNumber}:{$translationResourceId}";

        return Cache::rememberForever($cacheKey, function () use ($chapterNumber, $translationResourceId) {
            $verses = [];
            $page = 1;

            do {
                $data = $this->authenticatedGet("/verses/by_chapter/{$chapterNumber}", [
                    'translations' => $translationResourceId,
                    'fields' => 'text_uthmani',
                    'page' => $page,
                    'per_page' => 50,
                ]);

                $verses = array_merge($verses, $data['verses'] ?? []);
                $page++;
            } while (($data['pagination']['next_page'] ?? null) !== null);

            return $verses;
        });
    }

    /**
     * Find the resource id for the Indonesian translation once, cached
     * permanently afterwards.
     */
    public function resolveIndonesianTranslationId(): int
    {
        return Cache::rememberForever(self::TRANSLATION_ID_CACHE_KEY, function () {
            $data = $this->authenticatedGet('/resources/translations');

            foreach ($data['translations'] ?? [] as $translation) {
                if (str_contains(strtolower($translation['language_name'] ?? ''), 'indonesian')) {
                    return (int) $translation['id'];
                }
            }

            throw new RuntimeException('Resource terjemahan Bahasa Indonesia tidak ditemukan di Quran Foundation API.');
        });
    }
}
