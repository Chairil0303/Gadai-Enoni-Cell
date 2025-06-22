<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\Cabang;
use App\Models\TermsCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class NasabahTermsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create cabang
        $cabang = Cabang::create([
            'nama_cabang' => 'Cabang Test',
            'alamat' => 'Jl. Test No. 1',
            'kontak' => '0211234567',
        ]);

        // Create user
        $this->user = User::create([
            'nama' => 'Nasabah Test',
            'username' => 'nasabahtest',
            'password' => Hash::make('password123'),
            'role' => 'Nasabah',
            'id_cabang' => $cabang->id_cabang,
        ]);

        // Create nasabah
        $this->nasabah = Nasabah::create([
            'id_user' => $this->user->id_users,
            'nama' => 'Nasabah Test',
            'nik' => '1234567890123456',
            'alamat' => 'Alamat Nasabah Test',
            'telepon' => '081234567890',
            'status_blacklist' => false,
        ]);

        // Create terms and conditions
        $this->terms = TermsCondition::create([
            'title' => 'Syarat dan Ketentuan Gadai',
            'content' => 'Ini adalah syarat dan ketentuan untuk layanan gadai.',
        ]);
    }

    /** @test */
    public function nasabah_can_access_terms_and_conditions_page()
    {
        $response = $this->actingAs($this->user)->get('/nasabah/terms');

        $response->assertStatus(200);
        $response->assertViewIs('nasabah.terms');
        $response->assertSee('Syarat dan Ketentuan');
    }

    /** @test */
    public function terms_page_shows_terms_content()
    {
        $response = $this->actingAs($this->user)->get('/nasabah/terms');

        $response->assertStatus(200);
        $response->assertSee($this->terms->title);
        $response->assertSee($this->terms->content);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_terms_page()
    {
        $response = $this->get('/nasabah/terms');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function terms_page_handles_empty_terms()
    {
        // Delete existing terms
        TermsCondition::truncate();

        $response = $this->actingAs($this->user)->get('/nasabah/terms');

        $response->assertStatus(200);
        $response->assertViewIs('nasabah.terms');
    }
}
