# 3T App

Aplikasi pelacakan hafalan Al-Qur'an untuk program **3T (Takhassus, Tahsin, Tahfizh)** di SMP Islam Al Azhar 22 Sentra Primer. Guru mencatat progres hafalan per-ayat per murid, mencetak laporan A4, dan membagikan progres ke orang tua lewat tautan khusus. Aplikasi juga menyediakan menu baca Al-Qur'an (teks Arab + terjemahan, dari API Quran Foundation) yang menandai ayat yang sudah dihafal seorang murid.

## Stack

- Laravel 13, PHP 8.3
- Blade + Alpine.js (tanpa SPA), Tailwind CSS v4, Vite
- SQLite (default lokal) via Eloquent

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

### Kredensial login awal

Password admin **di-generate acak** oleh `UserSeeder` dan **hanya ditampilkan sekali** di console saat `migrate --seed` dijalankan — tidak disimpan di tempat lain dalam bentuk cleartext. **Catat password itu segera.** Kalau hilang, reset lewat `php artisan tinker`:

```php
User::first()->update(['password' => Hash::make('password-baru-anda')]);
```

Jangan re-seed untuk "recovery" — `UserSeeder` akan men-generate password acak yang baru lagi, bukan mengembalikan yang lama.

### Menu Al-Qur'an (opsional)

Menu "Al-Qur'an" memanggil API eksternal [Quran Foundation](https://quran.foundation) (OAuth2 client-credentials). Isi kredensial Anda di `.env` (jangan di-commit):

```
QURAN_FOUNDATION_CLIENT_ID=
QURAN_FOUNDATION_CLIENT_SECRET=
```

Tanpa kredensial ini, menu Al-Qur'an akan menampilkan pesan "konten tidak tersedia" — fitur lain aplikasi tetap berjalan normal.

## Testing

```bash
composer test
```

## Catatan Keamanan Dependency

Lihat `composer audit` untuk advisory terkini pada dependency. Advisory yang diterima secara sadar (dev-dependency tanpa rilis yang kompatibel PHP 8.3) dicatat di sini — belum ada per rilis ini.
