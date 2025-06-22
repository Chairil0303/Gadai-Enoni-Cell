@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-32"></div>
            <div class="px-6 py-4 relative">
                <div class="absolute -top-16 left-6">
                    <div class="w-32 h-32 rounded-full border-4 border-white bg-white overflow-hidden">
                        <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="ml-36">
                    <h2 class="text-3xl font-bold text-gray-800">{{ $nasabah->nama }}</h2>
                    <p class="text-gray-600">{{ auth()->user()->cabang->nama_cabang }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-id-card text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">NIK</p>
                            <p class="font-medium">{{ $nasabah->nik }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="font-medium">{{ $nasabah->alamat }}</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Nomor Telepon</p>
                            <p class="font-medium">{{ $nasabah->telepon }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Username</p>
                            <p class="font-medium">{{ auth()->user()->username }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Status Akun</h3>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full {{ $nasabah->status_blacklist ? 'bg-red-500' : 'bg-green-500' }} mr-3"></div>
                <p class="font-medium">{{ $nasabah->status_blacklist ? 'Akun Terblokir' : 'Akun Aktif' }}</p>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Ubah Password</h3>
                <button id="togglePasswordBtn" onclick="togglePasswordForm()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Ubah Password
                </button>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="passwordForm" class="hidden">
                <form id="updatePasswordForm" action="{{ route('nasabah.update-password') }}" method="POST" autocomplete="new-password">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" required autocomplete="off"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                onblur="validateCurrentPassword()">
                            <div id="current_password_error" class="hidden text-red-600 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" required autocomplete="off"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                onblur="validateNewPassword()">
                            <div id="new_password_error" class="hidden text-red-600 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required autocomplete="off"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                onblur="validatePasswordConfirmation()">
                            <div id="confirm_password_error" class="hidden text-red-600 text-sm mt-1"></div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="submit" id="submitBtn" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Simpan Password Baru
                            </button>
                            <button type="button" onclick="cancelPasswordForm()" class="w-full sm:w-auto bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                                Batalkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordForm() {
    const form = document.getElementById('passwordForm');
    const btn = document.getElementById('togglePasswordBtn');
    form.classList.toggle('hidden');
    if (form.classList.contains('hidden')) {
        btn.style.display = '';
    } else {
        btn.style.display = 'none';
    }
}

function cancelPasswordForm() {
    document.getElementById('passwordForm').classList.add('hidden');
    document.getElementById('togglePasswordBtn').style.display = '';
    // Reset form and errors
    document.getElementById('updatePasswordForm').reset();

    // Reset all error messages
    document.getElementById('current_password_error').classList.add('hidden');
    document.getElementById('new_password_error').classList.add('hidden');
    document.getElementById('confirm_password_error').classList.add('hidden');

    // Reset all input field styles
    const inputs = ['current_password', 'new_password', 'new_password_confirmation'];
    inputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        input.classList.remove('border-red-500', 'border-green-500');
        input.classList.add('border-gray-300');
    });
}

// Function to validate current password with AJAX
function validateCurrentPassword() {
    const currentPassword = document.getElementById('current_password').value;
    const errorDiv = document.getElementById('current_password_error');
    const inputField = document.getElementById('current_password');

    if (currentPassword.trim() === '') {
        errorDiv.textContent = 'Password saat ini harus diisi';
        errorDiv.classList.remove('hidden');
        inputField.classList.remove('border-gray-300');
        inputField.classList.add('border-red-500');
        return false;
    }

    // Show loading state
    errorDiv.textContent = 'Memverifikasi password...';
    errorDiv.classList.remove('hidden');
    errorDiv.classList.remove('text-red-600');
    errorDiv.classList.add('text-blue-600');

    // Make AJAX request to validate password
    fetch('{{ route("nasabah.validate-password") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            current_password: currentPassword
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            errorDiv.textContent = '✓ Password valid';
            errorDiv.classList.remove('text-blue-600', 'text-red-600');
            errorDiv.classList.add('text-green-600');
            inputField.classList.remove('border-red-500');
            inputField.classList.add('border-green-500');
        } else {
            errorDiv.textContent = data.message;
            errorDiv.classList.remove('text-blue-600', 'text-green-600');
            errorDiv.classList.add('text-red-600');
            inputField.classList.remove('border-gray-300', 'border-green-500');
            inputField.classList.add('border-red-500');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = 'Terjadi kesalahan saat memverifikasi password';
        errorDiv.classList.remove('text-blue-600', 'text-green-600');
        errorDiv.classList.add('text-red-600');
        inputField.classList.remove('border-gray-300', 'border-green-500');
        inputField.classList.add('border-red-500');
    });

    return true;
}

// Function to validate new password
function validateNewPassword() {
    const newPassword = document.getElementById('new_password').value;
    const currentPassword = document.getElementById('current_password').value;
    const errorDiv = document.getElementById('new_password_error');
    const inputField = document.getElementById('new_password');

    if (newPassword.trim() === '') {
        errorDiv.textContent = 'Password baru harus diisi';
        errorDiv.classList.remove('hidden');
        inputField.classList.remove('border-gray-300', 'border-green-500');
        inputField.classList.add('border-red-500');
        return false;
    }

    if (newPassword.length < 6) {
        errorDiv.textContent = 'Password baru minimal 6 karakter';
        errorDiv.classList.remove('hidden');
        inputField.classList.remove('border-gray-300', 'border-green-500');
        inputField.classList.add('border-red-500');
        return false;
    }

    // Check if new password is same as current password
    if (currentPassword && newPassword === currentPassword) {
        errorDiv.textContent = 'Password baru tidak boleh sama dengan password saat ini';
        errorDiv.classList.remove('hidden');
        inputField.classList.remove('border-gray-300', 'border-green-500');
        inputField.classList.add('border-red-500');
        return false;
    }

    errorDiv.classList.add('hidden');
    inputField.classList.remove('border-red-500');
    inputField.classList.add('border-green-500');
    return true;
}

// Function to validate password confirmation
function validatePasswordConfirmation() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const errorDiv = document.getElementById('confirm_password_error');
    const inputField = document.getElementById('new_password_confirmation');

    if (confirmPassword.trim() === '') {
        errorDiv.textContent = 'Konfirmasi password harus diisi';
        errorDiv.classList.remove('hidden');
        inputField.classList.remove('border-gray-300', 'border-green-500');
        inputField.classList.add('border-red-500');
        return false;
    }

    if (newPassword !== confirmPassword) {
        errorDiv.textContent = 'Konfirmasi password tidak sama';
        errorDiv.classList.remove('hidden');
        inputField.classList.remove('border-gray-300', 'border-green-500');
        inputField.classList.add('border-red-500');
        return false;
    }

    errorDiv.classList.add('hidden');
    inputField.classList.remove('border-red-500');
    inputField.classList.add('border-green-500');
    return true;
}

// Add event listener for form submission
document.getElementById('updatePasswordForm').addEventListener('submit', function(e) {
    let isValid = true;

    // Validate current password
    if (!validateCurrentPassword()) {
        isValid = false;
    }

    // Validate new password
    if (!validateNewPassword()) {
        isValid = false;
    }

    // Validate password confirmation
    if (!validatePasswordConfirmation()) {
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        alert('Mohon perbaiki kesalahan pada form sebelum melanjutkan!');
        return false;
    }

    setTimeout(() => {
        document.getElementById('passwordForm').classList.add('hidden');
        document.getElementById('togglePasswordBtn').style.display = '';
    }, 100);
});

// Add real-time validation for current password
let passwordValidationTimeout;
document.getElementById('current_password').addEventListener('input', function() {
    clearTimeout(passwordValidationTimeout);

    if (this.value.trim() !== '') {
        // Add debounce to avoid too many AJAX requests
        passwordValidationTimeout = setTimeout(() => {
            validateCurrentPassword();
        }, 500);
    } else {
        document.getElementById('current_password_error').classList.add('hidden');
        this.classList.remove('border-red-500', 'border-green-500');
        this.classList.add('border-gray-300');
    }
});

// Add onblur validation for immediate feedback
document.getElementById('current_password').addEventListener('blur', function() {
    if (this.value.trim() !== '') {
        validateCurrentPassword();
    }
});

// Add real-time validation for new password
document.getElementById('new_password').addEventListener('input', function() {
    if (this.value.trim() !== '') {
        validateNewPassword();
        // Also validate confirmation if it has value
        if (document.getElementById('new_password_confirmation').value.trim() !== '') {
            validatePasswordConfirmation();
        }
    } else {
        document.getElementById('new_password_error').classList.add('hidden');
        this.classList.remove('border-red-500', 'border-green-500');
        this.classList.add('border-gray-300');
    }
});

// Add real-time validation for password confirmation
document.getElementById('new_password_confirmation').addEventListener('input', function() {
    if (this.value.trim() !== '') {
        validatePasswordConfirmation();
    } else {
        document.getElementById('confirm_password_error').classList.add('hidden');
        this.classList.remove('border-red-500', 'border-green-500');
        this.classList.add('border-gray-300');
    }
});
</script>
@endsection
