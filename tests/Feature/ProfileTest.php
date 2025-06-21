<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\Cabang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class ProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $nasabah;
    protected $cabang;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations and seeders for a clean database state
        Artisan::call('migrate:fresh');

        // Create cabang
        $this->cabang = Cabang::factory()->create([
            'nama_cabang' => 'Cabang Test',
            'alamat' => 'Alamat Test',
            'kontak' => '081234567890'
        ]);

        // Create user
        $this->user = User::factory()->create([
            'nama' => 'Test User',
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'role' => 'Nasabah',
            'id_cabang' => $this->cabang->id_cabang
        ]);

        // Create nasabah
        $this->nasabah = Nasabah::factory()->create([
            'id_user' => $this->user->id_users,
            'nama' => 'Test User',
            'nik' => '1234567890123456',
            'alamat' => 'Alamat Test',
            'telepon' => '081234567890',
            'status_blacklist' => false
        ]);
    }

    /** @test */
    public function user_can_access_nasabah_profile_page()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertStatus(200);
        $response->assertViewIs('nasabah.profile');
        $response->assertViewHas('nasabah');
    }

    /** @test */
    public function nasabah_profile_page_displays_correct_user_information()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee($this->nasabah->nama);
        $response->assertSee($this->nasabah->nik);
        $response->assertSee($this->nasabah->alamat);
        $response->assertSee($this->nasabah->telepon);
        $response->assertSee($this->user->username);
        $response->assertSee($this->cabang->nama_cabang);
    }

    /** @test */
    public function nasabah_profile_page_shows_active_account_status()
    {
        $this->nasabah->update(['status_blacklist' => false]);
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('Akun Aktif');
        $response->assertDontSee('Akun Terblokir');
    }

    /** @test */
    public function nasabah_profile_page_shows_blacklisted_account_status()
    {
        $this->nasabah->update(['status_blacklist' => true]);
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('Akun Terblokir');
        $response->assertDontSee('Akun Aktif');
    }

    /** @test */
    public function password_form_is_hidden_by_default_on_nasabah_profile()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('id=\"passwordForm\" class=\"hidden\"');
    }

    /** @test */
    public function user_can_successfully_update_password_with_valid_data()
    {
        $response = $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'password123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect(route('nasabah.profil'));
        $response->assertSessionHas('success', 'Password berhasil diperbarui');

        // Verify password was actually updated
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    /** @test */
    public function password_update_fails_with_incorrect_current_password()
    {
        $response = $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect(route('nasabah.profil'));
        $response->assertSessionHasErrors(['current_password']);
        $response->assertSessionHasErrors(['current_password' => 'Password saat ini tidak sesuai']);
    }

    /** @test */
    public function password_update_fails_with_new_password_same_as_current()
    {
        $response = $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'password123',
            'new_password' => 'password123',
            'new_password_confirmation' => 'password123'
        ]);

        $response->assertRedirect(route('nasabah.profil'));
        $response->assertSessionHasErrors(['new_password']);
        $response->assertSessionHasErrors(['new_password' => 'Password baru tidak boleh sama dengan password saat ini']);
    }

    /** @test */
    public function password_update_fails_with_mismatched_confirmation()
    {
        $response = $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'password123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'differentpassword'
        ]);

        $response->assertRedirect(route('nasabah.profil'));
        $response->assertSessionHasErrors(['new_password']);
    }

    /** @test */
    public function password_update_fails_with_short_new_password()
    {
        $response = $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'password123',
            'new_password' => '123',
            'new_password_confirmation' => '123'
        ]);

        $response->assertRedirect(route('nasabah.profil'));
        $response->assertSessionHasErrors(['new_password']);
    }

    /** @test */
    public function password_update_fails_with_empty_current_password()
    {
        $response = $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => '',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect(route('nasabah.profil'));
        $response->assertSessionHasErrors(['current_password']);
    }

    /** @test */
    public function password_update_fails_with_empty_new_password()
    {
        $response = $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'password123',
            'new_password' => '',
            'new_password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect(route('nasabah.profil'));
        $response->assertSessionHasErrors(['new_password']);
    }

    /** @test */
    public function password_validation_endpoint_returns_valid_response_for_correct_password()
    {
        $response = $this->actingAs($this->user)->postJson(route('nasabah.validate-password'), [
            'current_password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'message' => 'Password valid'
        ]);
    }

    /** @test */
    public function password_validation_endpoint_returns_invalid_response_for_incorrect_password()
    {
        $response = $this->actingAs($this->user)->postJson(route('nasabah.validate-password'), [
            'current_password' => 'wrongpassword'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => false,
            'message' => 'Password saat ini tidak sesuai'
        ]);
    }

    /** @test */
    public function password_validation_endpoint_returns_error_for_empty_password()
    {
        $response = $this->actingAs($this->user)->postJson(route('nasabah.validate-password'), [
            'current_password' => ''
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function nasabah_profile_page_has_correct_csrf_token()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('name=\"_token\"');
        $response->assertSee('name=\"csrf-token\"');
    }

    /** @test */
    public function nasabah_profile_page_has_autocomplete_disabled_for_password_fields()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('autocomplete=\"off\"');
        $response->assertSee('autocomplete=\"new-password\"');
    }

    /** @test */
    public function nasabah_profile_page_has_required_validation_attributes()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('required');
    }

    /** @test */
    public function nasabah_profile_page_has_correct_form_action()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('action=\"/nasabah/update-password\"');
    }

    /** @test */
    public function nasabah_profile_page_has_correct_method_override()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('name=\"_method\" value=\"PUT\"');
    }

    /** @test */
    public function nasabah_profile_page_displays_success_message_after_password_update()
    {
        // First update password
        $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'password123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ]);

        // Then check if success message is displayed
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('Password berhasil diperbarui');
    }

    /** @test */
    public function nasabah_profile_page_displays_error_messages_for_validation_failures()
    {
        // Try to update with wrong current password
        $this->actingAs($this->user)->put(route('nasabah.update-password'), [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ]);

        // Check if error message is displayed
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('Password saat ini tidak sesuai');
    }

    /** @test */
    public function nasabah_profile_page_has_javascript_functions_for_validation()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('function validateCurrentPassword()');
        $response->assertSee('function validateNewPassword()');
        $response->assertSee('function validatePasswordConfirmation()');
        $response->assertSee('function togglePasswordForm()');
        $response->assertSee('function cancelPasswordForm()');
    }

    /** @test */
    public function nasabah_profile_page_has_event_listeners_for_real_time_validation()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('addEventListener');
        $response->assertSee('onblur');
        $response->assertSee('oninput');
    }

    /** @test */
    public function nasabah_profile_page_has_ajax_validation_endpoint()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('nasabah/validate-password');
    }

    /** @test */
    public function nasabah_profile_page_has_debounce_functionality()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('setTimeout');
        $response->assertSee('clearTimeout');
    }

    /** @test */
    public function nasabah_profile_page_has_visual_feedback_classes()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('border-red-500');
        $response->assertSee('border-green-500');
        $response->assertSee('text-red-600');
        $response->assertSee('text-green-600');
    }

    /** @test */
    public function nasabah_profile_page_has_form_reset_functionality()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('reset()');
        $response->assertSee('classList.add(\'hidden\')');
    }

    /** @test */
    public function nasabah_profile_page_prevents_form_submission_with_invalid_data()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('preventDefault()');
        $response->assertSee('return false');
    }

    /** @test */
    public function nasabah_profile_page_has_loading_state_for_ajax_validation()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('Memverifikasi password...');
        $response->assertSee('text-blue-600');
    }

    /** @test */
    public function nasabah_profile_page_has_error_handling_for_ajax_failures()
    {
        $response = $this->actingAs($this->user)->get(route('nasabah.profil'));

        $response->assertSee('catch(error)');
        $response->assertSee('Terjadi kesalahan saat memverifikasi password');
    }
}
