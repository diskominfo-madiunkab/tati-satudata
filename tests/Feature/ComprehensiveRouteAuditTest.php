<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ComprehensiveRouteAuditTest extends TestCase
{
    /**
     * Uji seluruh Halaman Publik untuk memastikan HTTP 200 (Bebas Error 500)
     */
    public function test_all_public_pages_render_without_error(): void
    {
        $publicRoutes = [
            '/',
            '/katalog-data',
            '/kode-referensi',
            '/kode-referensi?tab=desa',
            '/kode-referensi?tab=puskesmas',
            '/kode-referensi?tab=sdsn',
            '/regulasi',
            '/geoportal',
            '/publikasi-guest',
            '/infografis-guest',
            '/visualisasi-guest',
            '/tentang',
            '/login',
            '/api/v1/datasets',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    /**
     * Uji Halaman Internal Administrator
     */
    public function test_administrator_pages_render_without_error(): void
    {
        $user = User::whereHas('roles', fn($q) => $q->where('name', 'administrator'))->first() ?? User::first();
        if ($user && method_exists($user, 'assignRole') && !$user->hasRole('administrator')) {
            $user->assignRole('administrator');
        }

        $adminRoutes = [
            '/data_administrator',
            '/data_administrator/create',
            '/kelola-visualisasi',
            '/kelola-regulasi',
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $this->assertContains($response->getStatusCode(), [200, 302], "Route {$route} failed with {$response->getStatusCode()}");
        }
    }

    /**
     * Uji Seluruh Halaman Internal Walidata
     */
    public function test_walidata_pages_render_without_error(): void
    {
        $user = User::whereHas('roles', fn($q) => $q->where('name', 'walidata'))->first() ?? User::first();
        if ($user && method_exists($user, 'assignRole') && !$user->hasRole('walidata')) {
            $user->assignRole('walidata');
        }

        $walidataRoutes = [
            '/d_walidata',
            '/data_walidata/draft',
            '/data_walidata/create',
            '/data_walidata/standar-data',
            '/data_walidata/pengumpulan',
            '/data_walidata/verifikasi',
            '/data_walidata/publikasi',
            '/d_walidata/rekap',
        ];

        foreach ($walidataRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $this->assertContains($response->getStatusCode(), [200, 302], "Route {$route} failed with {$response->getStatusCode()}");
        }
    }

    /**
     * Uji Seluruh Halaman Internal Produsen Data
     */
    public function test_produsen_pages_render_without_error(): void
    {
        $user = User::whereHas('roles', fn($q) => $q->where('name', 'produsen'))->first() ?? User::first();
        if ($user && method_exists($user, 'assignRole') && !$user->hasRole('produsen')) {
            $user->assignRole('produsen');
        }

        $produsenRoutes = [
            '/d_produsen',
            '/data_produsen/draft',
            '/data_produsen/create',
            '/data_produsen/standar-data',
            '/data_produsen/pengumpulan',
            '/data_produsen/panduan',
        ];

        foreach ($produsenRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $this->assertContains($response->getStatusCode(), [200, 302], "Route {$route} failed with {$response->getStatusCode()}");
        }
    }

    /**
     * Uji Halaman Pembina & Walidata Pendukung
     */
    public function test_pembina_and_pendukung_pages_render_without_error(): void
    {
        $pembina = User::whereHas('roles', fn($q) => $q->where('name', 'pembina'))->first() ?? User::first();
        if ($pembina && method_exists($pembina, 'assignRole') && !$pembina->hasRole('pembina')) {
            $pembina->assignRole('pembina');
        }

        $routes = [
            '/data_walidata/draft',
            '/data_walidata/standar-data',
            '/d_walidata/rekap',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($pembina)->get($route);
            $this->assertContains($response->getStatusCode(), [200, 302], "Route {$route} failed with {$response->getStatusCode()}");
        }
    }
}
