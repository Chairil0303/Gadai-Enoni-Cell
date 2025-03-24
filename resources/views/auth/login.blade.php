<x-guest-layout>
    <style>
        /* Background dengan gradient */
        /* Background dengan gradient */
            body, html {
                font-family: 'Poppins', Arial, sans-serif;
                width: 100%; /* ✅ Pastikan lebar penuh */
                height: 100vh; /* ✅ Pastikan tinggi penuh */
                margin: 0;
                padding: 0;
                color: #333;
                overflow: hidden; /* ✅ Hindari overflow */
                background-size: cover; /* ✅ Pastikan gradient menutupi seluruh layar */
            }


        /* Container form */
        .login-container {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px; /* ✅ Perbaiki ukuran container */
            box-sizing: border-box;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: auto; /* ✅ Pastikan container di tengah */
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.3);
        }

        h2 {
            margin-bottom: 1.5rem;
            font-size: 28px;
            color: #4CAF50;
            text-align: center;
            font-weight: 600;
        }

        /* Styling form input */
        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            padding-left: 40px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.2);
        }

        /* Icon pada input */
        .form-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        /* Tombol login */
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn-login:hover {
            background-color: #45a049;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }

        /* Pesan error */
        .error-message {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 400px) {
            .login-container {
                padding: 2rem;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>

    <div class="login-container">
        <h2>Enoni Cell</h2>

        <!-- Session Status -->
        <x-auth-session-status class="error-message" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <i class="fas fa-user"></i>
                <label for="username">Username</label>
                <input id="username" type="text" name="username" autocomplete="off" required autofocus>
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-login">
                    Login
                </button>
            </div>
        </form>
    </div>
    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK',
                timer: 5000
            });
        });
    </script>
@endif
    <!-- Font Awesome untuk icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</x-guest-layout>
