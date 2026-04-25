<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_attendance_index_is_accessible_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get('/attendance');
        $response->assertStatus(200);
        $response->assertViewIs('attendance.index');
    }

    public function test_user_can_clock_in_when_within_geofence(): void
    {
        // Coordinates very close to office (within 100m)
        $response = $this->actingAs($this->user)->postJson('/attendance/store', [
            'latitude'  => -2.9851,  // ~11m from office
            'longitude' => 104.7321,
            'photo'     => 'data:image/jpeg;base64,' . base64_encode(str_repeat('x', 100)),
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->user->id,
            'date'    => now()->toDateString(),
        ]);
    }

    public function test_user_cannot_clock_in_when_outside_geofence(): void
    {
        // Far away coordinates (Jakarta, ~800km from Palembang)
        $response = $this->actingAs($this->user)->postJson('/attendance/store', [
            'latitude'  => -6.2088,
            'longitude' => 106.8456,
            'photo'     => 'data:image/jpeg;base64,' . base64_encode(str_repeat('x', 100)),
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertDatabaseMissing('attendances', ['user_id' => $this->user->id]);
    }

    public function test_user_can_clock_out_after_clocking_in(): void
    {
        // First, clock in
        Attendance::create([
            'user_id'            => $this->user->id,
            'date'               => today(),
            'clock_in'           => now()->subHours(8)->format('H:i:s'),
            'clock_in_latitude'  => -2.985,
            'clock_in_longitude' => 104.732,
            'distance_meters'    => 45,
            'location_status'    => 'in_range',
            'status'             => 'present',
            'photo_path'         => 'attendance_photos/test.jpg',
        ]);

        // Now clock out (can be from any location)
        $response = $this->actingAs($this->user)->postJson('/attendance/store', [
            'latitude'  => -2.9851,
            'longitude' => 104.7321,
            'photo'     => 'data:image/jpeg;base64,' . base64_encode(str_repeat('x', 100)),
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->user->id,
            'date'    => today()->toDateString(),
        ]);
        $this->assertNotNull(
            Attendance::where('user_id', $this->user->id)->whereDate('date', today())->first()->clock_out
        );
    }

    public function test_double_clock_in_and_out_is_blocked(): void
    {
        Attendance::create([
            'user_id'            => $this->user->id,
            'date'               => today(),
            'clock_in'           => '08:00:00',
            'clock_out'          => '17:00:00',
            'clock_in_latitude'  => -2.985,
            'clock_in_longitude' => 104.732,
            'distance_meters'    => 45,
            'location_status'    => 'in_range',
            'status'             => 'present',
            'photo_path'         => 'attendance_photos/test.jpg',
        ]);

        $response = $this->actingAs($this->user)->postJson('/attendance/store', [
            'latitude'  => -2.9851,
            'longitude' => 104.7321,
            'photo'     => 'data:image/jpeg;base64,' . base64_encode(str_repeat('x', 100)),
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_clock_in_without_photo_is_rejected(): void
    {
        $response = $this->actingAs($this->user)->postJson('/attendance/store', [
            'latitude'  => -2.9851,
            'longitude' => 104.7321,
            // missing photo
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_view_monthly_recap(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/recap');
        $response->assertStatus(200);
        $response->assertViewIs('admin.recap');
    }

    public function test_admin_recap_can_filter_by_month_year(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/recap?month=5&year=2026');
        $response->assertStatus(200);
        $response->assertViewHas('month', 5);
        $response->assertViewHas('year', 2026);
    }

    public function test_admin_can_export_recap_csv(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/recap/export?month=5&year=2026');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_haversine_distance_calculation(): void
    {
        // We test via the API — two points that are definitely > 100m apart
        $response = $this->actingAs($this->user)->postJson('/attendance/store', [
            'latitude'  => -2.990,   // ~556m away
            'longitude' => 104.732,
            'photo'     => 'data:image/jpeg;base64,' . base64_encode(str_repeat('x', 100)),
        ]);

        $response->assertStatus(422);
        $data = $response->json();
        $this->assertGreaterThan(100, $data['distance']);
    }
}
