<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TerPRIMA CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 50; 
            border-radius: 8px;
            overflow: hidden;
        }

        .dropdown-content a, .dropdown-content button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            width: 100%;
        }
        .dropdown-content a:hover, .dropdown-content button:hover {
            background-color: #f1f1f1;
        }
        .show {
            display: block;
        }
    </style>
</head>
<body>

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div>
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-50 ml-5 mt-3">
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="/dashboard" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                    <span class="material-icons mr-2">home</span> Beranda
                </a>
                <a href="{{ route('contents.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                    <span class="material-icons mr-2">list</span> Daftar Konten
                </a>
                <a href="{{ route('contents.completed') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                    <span class="material-icons mr-2">check_circle</span> Konten Terselesaikan
                </a>
            </nav>
        </aside>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
        <header class="relative w-full h-[80px]"> 
            <div class="absolute top-0 left-0 w-full h-[25px] bg-white z-0"></div>
            <img src="{{ asset('img/header_cms.png') }}" alt="Header" class="absolute top-0 left-0 h-full w-full object-cover z-10">
            <div class="relative z-20 flex items-center justify-between h-full px-6">
                <div></div>
                <div class="flex items-center">
                    <div class="text-white font-semibold drop-shadow mr-4">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} | {{ \Carbon\Carbon::now()->format('H:i') }} WIB
                    </div>
                    <div class="dropdown relative z-50">
                        <div id="profileDropdown" class="w-10 h-10 bg-orange-300 rounded-full flex items-center justify-center text-white font-bold cursor-pointer">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div id="dropdownMenu" class="dropdown-content z-50">
                            <div class="px-4 py-3 text-sm text-gray-900 border-b">
                                <div class="font-medium">{{ Auth::user()->name }}</div>
                                <div class="truncate">NRP: {{ Auth::user()->nrp }}</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center text-red-600 hover:bg-gray-100">
                                    <span class="material-icons mr-2" style="font-size: 18px; color:red;">logout</span>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

            <!-- Main Content -->
            <main class="flex-1 px-6 py-4 bg-[#e8f2f8]">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.getElementById('profileDropdown').addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.toggle('show');
        });

        window.addEventListener('click', function(event) {
            if (!event.target.matches('#profileDropdown') && !event.target.closest('#profileDropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        });
    </script>
</body>
</html>
