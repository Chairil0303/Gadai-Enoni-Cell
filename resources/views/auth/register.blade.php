<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="nama">Nama</label>
            <input id="nama" type="text" name="nama" required autofocus>
        </div>

        <div>
            <label for="username">Username</label>
            <input id="username" type="text" name="username" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="Superadmin">Superadmin</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <div>
            <button type="submit">
                Register
            </button>
        </div>
    </form>

    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('login') }}"; // Redirect ke halaman login
            });
        </script>
    @endif
</x-guest-layout>
