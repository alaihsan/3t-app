<?php

namespace Tests\Feature;

use App\Models\MemorizationRecord;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Surah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreeTAppTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::where('email', 'ichsan@smpi-alazhar22.sch.id')->first() ?? User::factory()->create();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'ichsan@smpi-alazhar22.sch.id',
            'password' => 'B1smillah',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_renders_successfully_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('3T App');
        $response->assertSee('Takhassus');
        $response->assertSee('Tahsin');
        $response->assertSee('Tahfizh');
    }

    public function test_big_search_api_returns_expected_students(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('search.api', ['q' => 'Fatih']));
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonFragment(['name' => 'Muhammad Al-Fatih']);
    }

    public function test_memorization_toggle_verse_saves_directly_to_database(): void
    {
        $student = Student::first();
        $surah = Surah::where('number', 112)->first(); // Al-Ikhlas (4 verses)

        // Toggle verse 1 on with Grade A
        $response = $this->actingAs($this->user)->postJson(route('memorization.toggle-verse'), [
            'student_id' => $student->id,
            'surah_id' => $surah->id,
            'verse_number' => 1,
            'category' => 'Tahfizh',
            'grade' => 'A',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'is_memorized' => true,
            'verse_number' => 1,
            'grade' => 'A',
            'message' => '✓ Tersimpan ke database',
        ]);

        $this->assertDatabaseHas('memorization_records', [
            'student_id' => $student->id,
            'surah_id' => $surah->id,
            'verse_number' => 1,
            'grade' => 'A',
            'category' => 'Tahfizh',
        ]);
    }

    public function test_memorization_save_range_saves_bulk_verses_in_database(): void
    {
        $student = Student::first();
        $surah = Surah::where('number', 113)->first(); // Al-Falaq (5 verses)

        $response = $this->actingAs($this->user)->postJson(route('memorization.save-range'), [
            'student_id' => $student->id,
            'surah_id' => $surah->id,
            'verse_from' => 1,
            'verse_to' => 5,
            'category' => 'Takhassus',
            'grade' => 'A',
            'action' => 'add',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'action' => 'add',
            'grade' => 'A',
        ]);

        for ($v = 1; $v <= 5; $v++) {
            $this->assertDatabaseHas('memorization_records', [
                'student_id' => $student->id,
                'surah_id' => $surah->id,
                'verse_number' => $v,
                'category' => 'Takhassus',
                'grade' => 'A',
            ]);
        }
    }

    public function test_student_import_excel_copas_works_with_multi_categories(): void
    {
        $pasteData = "202407999\tIbnu Sina\tL\t7C\tTahsin, Tahfizh";

        $response = $this->actingAs($this->user)->post(route('students.process-import-excel'), [
            'paste_data' => $pasteData,
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', [
            'nis' => '202407999',
            'name' => 'Ibnu Sina',
            'gender' => 'L',
            'class_name' => '7C',
        ]);

        $created = Student::where('nis', '202407999')->first();
        $this->assertEquals(['Tahsin', 'Tahfizh'], $created->categories);
    }

    public function test_share_api_json_endpoint_returns_accurate_data(): void
    {
        $student = Student::first();
        
        // Public route without login
        $response = $this->getJson(route('share.api', [
            'share_token' => $student->share_token,
            'pin' => $student->access_pin,
        ]));

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('murid.nis', $student->nis);
        $response->assertJsonPath('murid.nama', $student->name);
        $response->assertJsonStructure([
            'status',
            'sekolah' => ['nama', 'tahun_ajaran', 'semester', 'guru_pembimbing'],
            'murid' => ['nis', 'nama', 'jenis_kelamin', 'kelas', 'program_3t'],
            'ringkasan_progres' => ['total_ayat_dihafal', 'total_surah_disetor', 'persentase_total_quran', 'sebaran_grade'],
            'detail_hafalan_per_surah',
        ]);
    }

    public function test_share_web_view_accessible_without_pin(): void
    {
        $student = Student::first();

        // Direct public access without PIN
        $response = $this->get(route('share.view', $student->share_token));
        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee('Peta Capaian Juz');
    }

    public function test_report_print_a4_landscape_renders_properly(): void
    {
        $response = $this->actingAs($this->user)->get(route('reports.print-a4-landscape', ['class_name' => '7A']));
        $response->assertStatus(200);
        $response->assertSee('LAPORAN MONITORING HAFALAN', false);
        $response->assertSee('Guru Tahfizh', false);
    }
}
