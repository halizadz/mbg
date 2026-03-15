<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'password' => 'Password@123',
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Operator Test',
            'email'    => 'operator@test.com',
            'password' => 'Password@123',
            'role'     => 'operator',
        ]);
    }

    /** @test */
    public function halaman_login_bisa_diakses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_bisa_login_dengan_kredensial_benar(): void
    {
        $response = $this->post('/', [
            'email'    => 'admin@test.com',
            'password' => 'Password@123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function user_tidak_bisa_login_dengan_password_salah(): void
    {
        $response = $this->post('/', [
            'email'    => 'admin@test.com',
            'password' => 'salah12345',
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function user_bisa_logout(): void
    {
        $user = User::where('email', 'admin@test.com')->first();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function dashboard_tidak_bisa_diakses_tanpa_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_bisa_akses_user_management(): void
    {
        $admin = User::where('email', 'admin@test.com')->first();

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
    }

    /** @test */
    public function operator_tidak_bisa_akses_user_management(): void
    {
        $operator = User::where('email', 'operator@test.com')->first();

        $response = $this->actingAs($operator)->get('/users');
        $response->assertStatus(403);
    }

    /** @test */
    public function is_admin_mengecek_role_bukan_email(): void
    {
        $admin = User::where('email', 'admin@test.com')->first();
        $operator = User::where('email', 'operator@test.com')->first();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($operator->isAdmin());

        // Buat user baru dengan email berbeda tapi role admin
        $admin2 = User::create([
            'name'     => 'Admin Baru',
            'email'    => 'admin2@test.com',
            'password' => 'Password@123',
            'role'     => 'admin',
        ]);

        $this->assertTrue($admin2->isAdmin());
    }
}
