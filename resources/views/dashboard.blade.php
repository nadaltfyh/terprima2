@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-semibold mb-6">Dashboard TerPRIMA</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Konten -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-500 text-sm">Total Konten</h2>
                    <p class="text-3xl font-bold">{{ $totalContents }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Konten Ditinjau -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-500 text-sm">Konten Ditinjau</h2>
                    <p class="text-3xl font-bold">{{ $completedContents }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Konten Belum Ditinjau -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-500 text-sm">Konten Belum Ditinjau</h2>
                    <p class="text-3xl font-bold">{{ $pendingContents }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Total Media -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-500 text-sm">Total Media</h2>
                    <p class="text-3xl font-bold">{{ $totalMedia }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- <div class="mt-8 bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-lg font-semibold mb-4">Informasi Sistem</h2>
        <p class="text-gray-600">
            Selamat datang di dashboard TerPRIMA. Sistem ini digunakan untuk mengelola konten dan media yang akan ditampilkan di aplikasi TerPRIMA.
        </p>
        <div class="mt-4 flex space-x-4">
            <a href="{{ route('contents.index') }}" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 transition">
                Lihat Daftar Konten
            </a>
            <a href="{{ route('contents.completed') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">
                Lihat Konten Terselesaikan
            </a>
        </div>
    </div> -->
</div>
@endsection
