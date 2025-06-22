<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\Cabang;
use App\Models\BarangGadai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class NasabahTebusTest extends TestCase
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

        // Create barang gadai
        $this->barangGadai = BarangGadai::create([
            'id_nasabah' => $this->nasabah->id_nasabah,
            'no_bon' => 'BON001',
            'nama_barang' => 'Laptop Test',
            'deskripsi' => 'Laptop kondisi baik',
            'harga_gadai' => 5000000,
            'tanggal_gadai' => now()->subDays(10),
            'tanggal_jatuh_tempo' => now()->addDays(20),
            'status' => 'tergadai',
            'id_user' => $this->user->id_users,
        ]);
    }

    /** @test */
    public function nasabah_can_search_for_tebus_gadai_items()
    {
        $response = $this->actingAs($this->user)->get('/tebus/cari', [
            'search' => $this->barangGadai->no_bon
        ]);

        $response->assertStatus(200);
        $response->assertSee($this->barangGadai->no_bon);
    }

    /** @test */
    public function search_returns_no_results_for_invalid_bon()
    {
        $response = $this->actingAs($this->user)->get('/tebus/cari', [
            'search' => 'INVALID'
        ]);

        $response->assertStatus(200);
        $response->assertDontSee('BON001');
    }

    /** @test */
    public function nasabah_can_access_tebus_konfirmasi_page()
    {
        $response = $this->actingAs($this->user)->get('/nasabah/konfirmasi/' . $this->barangGadai->no_bon);

        $response->assertStatus(200);
        $response->assertViewIs('nasabah.konfirmasi');
        $response->assertSee($this->barangGadai->no_bon);
        $response->assertSee($this->barangGadai->nama_barang);
    }

    /** @test */
    public function tebus_konfirmasi_page_shows_barang_details()
    {
        $response = $this->actingAs($this->user)->get('/nasabah/konfirmasi/' . $this->barangGadai->no_bon);

        $response->assertStatus(200);
        $response->assertSee($this->barangGadai->deskripsi);
        $response->assertSee(number_format($this->barangGadai->harga_gadai));
    }

    /** @test */
    public function tebus_konfirmasi_page_handles_invalid_bon_number()
    {
        $response = $this->actingAs($this->user)->get('/nasabah/konfirmasi/INVALID');

        $response->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_tebus_konfirmasi()
    {
        $response = $this->get('/nasabah/konfirmasi/' . $this->barangGadai->no_bon);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function search_works_with_empty_query()
    {
        $response = $this->actingAs($this->user)->get('/tebus/cari', [
            'search' => ''
        ]);

        $response->assertStatus(200);
    }
}
