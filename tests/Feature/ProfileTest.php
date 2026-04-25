<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->user = User::factory()->create([
            'name'       => 'Test User',
            'department' => 'Engineering',
            'password'   => bcrypt('password'),
        ]);
    }

    public function test_profile_hub_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/profile');
        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
    }

    public function test_profile_photo_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/profile/photo');
        $response->assertStatus(200);
    }

    public function test_profile_photo_can_be_updated(): void
    {
        $fakePhoto = 'data:image/jpeg;base64,' . base64_encode(str_repeat('A', 200));

        $response = $this->actingAs($this->user)->post('/profile/photo', [
            'photo' => $fakePhoto,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertNotNull($this->user->fresh()->photo_path);
    }

    public function test_password_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/profile/password');
        $response->assertStatus(200);
    }

    public function test_password_can_be_changed(): void
    {
        $response = $this->actingAs($this->user)->post('/profile/password', [
            'current_password'      => 'password',
            'password'              => 'NewPass123',
            'password_confirmation' => 'NewPass123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewPass123', $this->user->fresh()->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $response = $this->actingAs($this->user)->post('/profile/password', [
            'current_password'      => 'wrong-password',
            'password'              => 'NewPass123',
            'password_confirmation' => 'NewPass123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_details_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/profile/details');
        $response->assertStatus(200);
    }

    public function test_personal_details_can_be_updated(): void
    {
        $response = $this->actingAs($this->user)->post('/profile/details', [
            'name'       => 'Updated Name',
            'department' => 'Marketing',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id'         => $this->user->id,
            'name'       => 'Updated Name',
            'department' => 'Marketing',
        ]);
    }

    public function test_activity_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/profile/activity');
        $response->assertStatus(200);
        $response->assertViewIs('profile.activity');
    }

    public function test_name_is_required_for_details_update(): void
    {
        $response = $this->actingAs($this->user)->post('/profile/details', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
