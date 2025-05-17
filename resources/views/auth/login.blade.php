<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TerPRIMA CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="relative">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Header for mobile -->
        <div class="block lg:hidden">
            <img src="{{ asset('img/logo.png') }}" alt="Logo TerPRIMA" class="w-full max-w-[200px] mx-auto mt-6">
        </div>

        <!-- Form section -->
        <div class="lg:w-1/2 w-full bg-white p-6 lg:p-10 flex items-center justify-center">
            <div class="w-full max-w-md">

                
                <h2 class="text-2xl font-bold text-gray-800 mb-2 text-left">Selamat Datang</h2>
                <p class="mb-10 text-gray-600 text-left">Silahkan masukkan nama dan NRP</p>

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
                            <label for="nrp" class="block text-gray-700 text-sm font-bold mb-2 text-left">NRP (Nomor Registrasi Pokok)</label>
                            <input id="nrp" type="text" name="nrp" required
                                class="shadow appearance-none border rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        
                        <div class="w-full">
                            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-lg w-full focus:outline-none focus:shadow-outline">
                                Masuk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Background section - hidden on mobile -->
        <div class="lg:w-1/2 w-full bg-center bg-cover text-white flex-col justify-between p-10 hidden lg:flex" 
            style="background-image: url('{{ asset('img/bg_login.png') }}');"></div>
    </div>
</body>
</html>
