<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TerPRIMA CMS</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_web.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="relative">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <div class="block lg:hidden">
            <img src="{{ asset('img/header.png') }}" alt="Header TerPRIMA" class="w-full">
        </div>

        <div class="lg:w-1/2 w-full bg-white p-6 lg:p-10 flex items-center justify-center">
            <div class="w-full max-w-md">

                
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-left">Selamat Datang</h2>
                <p class="mb-10 text-gray-600 text-left">Silahkan masukkan nama dan kata sandi</p>

                @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-left" role="alert">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="flex flex-col items-center">
                        <div class="mb-6 w-full">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2 text-left">Nama</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="shadow appearance-none border rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        
                        <div class="mb-6 w-full">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2 text-left">Kata Sandi</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required
                                    class="shadow appearance-none border rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pr-10">
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center">
                                    <span class="material-icons text-gray-600" id="toggleIcon">visibility_off</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="w-full">
                            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-lg w-full focus:outline-none focus:shadow-outline cursor-pointer">
                                Masuk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:w-1/2 w-full bg-center bg-cover text-white flex-col justify-between p-10 hidden lg:flex" 
            style="background-image: url('{{ asset('img/bg_login.png') }}');"></div>
    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility_off';
            }
        }
    </script>
</body>
</html>
