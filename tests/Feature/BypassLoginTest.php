<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BypassLoginTest extends TestCase
{
    public function test_bypass_login_as_administrator(): void
    {
        $response = $this->get('/bypass-login/administrator');
        $response->assertRedirect(route('d_administrator'));
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->hasRole('administrator') || Auth::user()->role_id == 1);
    }

    public function test_bypass_login_as_walidata(): void
    {
        $response = $this->get('/bypass-login/walidata');
        $response->assertRedirect(route('d_walidata'));
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->hasRole('walidata') || in_array(Auth::user()->role_id, [2, 4]));
    }

    public function test_bypass_login_as_produsen(): void
    {
        $response = $this->get('/bypass-login/produsen');
        $response->assertRedirect(route('d_produsen'));
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->hasRole('produsen') || Auth::user()->role_id == 3);
    }

    public function test_bypass_login_as_pembina(): void
    {
        $response = $this->get('/bypass-login/pembina');
        $response->assertRedirect(route('d_walidata'));
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->hasRole('pembina') || in_array(Auth::user()->role_id, [2, 4]));
    }

    public function test_bypass_login_as_walidatapendukung(): void
    {
        $response = $this->get('/bypass-login/walidatapendukung');
        $response->assertRedirect(route('d_walidata'));
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->hasRole('walidatapendukung') || in_array(Auth::user()->role_id, [2, 4]));
    }

    public function test_bypass_login_invalid_role_returns_404(): void
    {
        $response = $this->get('/bypass-login/invalid_role_name');
        $response->assertStatus(404);
    }
}
