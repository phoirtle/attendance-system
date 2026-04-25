<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'test@heartstrings.id',
            'password' => bcrypt('password'),
            'role'     => 'user',
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@heartstrings.id',
            'password' => 'password',
            'role'     => 'user',
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_login_and_redirected_to_dashboard(): void
    {
        $admin = User::factory()->admin()->create([
            'email'    => 'admin@heartstrings.id',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@heartstrings.id',
            'password' => 'password',
            'role'     => 'admin',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_role_mismatch_is_rejected(): void
    {
        User::factory()->create([
            'email'    => 'user@heartstrings.id',
            'password' => bcrypt('password'),
            'role'     => 'user',
        ]);

        $response = $this->post('/login', [
            'email'    => 'user@heartstrings.id',
            'password' => 'password',
            'role'     => 'admin', // wrong role
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_wrong_password_is_rejected(): void
    {
        User::factory()->create([
            'email'    => 'user@heartstrings.id',
            'password' => bcrypt('password'),
            'role'     => 'user',
        ]);

        $response = $this->post('/login', [
            'email'    => 'user@heartstrings.id',
            'password' => 'wrong-password',
            'role'     => 'user',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_protected_routes(): void
    {
        $this->get('/attendance')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $this->get('/admin/dashboard')->assertForbidden();
        $this->get('/admin/recap')->assertForbidden();
    }
}
