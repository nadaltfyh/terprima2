<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Routes - Content Upload Form
Route::get('/content', [ContentController::class, 'create'])->name('contents.create');
Route::post('/upload', [ContentController::class, 'store'])->name('contents.upload');

// Protected Routes - CMS
// Route utama diarahkan ke /content, bisa diakses publik
Route::get('/', function () {
    return redirect('/content');
});

Route::middleware(['auth'])->group(function () {
    // Route beranda/dashboard CMS
    Route::get('/dashboard', [ContentController::class, 'dashboard'])->name('dashboard');

    // Rute dengan parameter harus didefinisikan setelah rute statis
    Route::get('/contents/search', [ContentController::class, 'search'])->name('contents.search');
    Route::get('/media/download/{id}', [ContentController::class, 'downloadIndividualMedia'])->name('media.download');
    Route::get('/contents/completed', [ContentController::class, 'completed'])->name('contents.completed');
    Route::get('/contents', [ContentController::class, 'index'])->name('contents.index');
    Route::post('/contents', [ContentController::class, 'store'])->name('contents.store');

    // Rute dengan parameter
    Route::get('/contents/{id}/edit', [ContentController::class, 'edit'])->name('contents.edit');
    Route::get('/contents/{id}/json', [ContentController::class, 'showJson']);
    
    // Fix: Remove duplicate route definition
    Route::post('/contents/{id}/update', [ContentController::class, 'update'])->name('contents.update');
    
    Route::delete('/contents/{id}', [ContentController::class, 'destroy'])->name('contents.destroy');
    Route::get('/contents/{id}/download-media', [ContentController::class, 'downloadMedia'])->name('contents.download-media');
    Route::post('/contents/bulk-delete', [ContentController::class, 'bulkDelete'])->name('contents.bulk-delete');
    Route::get('/contents/{id}', function ($id) {
        $content = \App\Models\Content::with('media')->findOrFail($id);
        return response()->json([
            'id' => $content->id,
            'name' => $content->name,
            'satuan' => $content->satuan,
            'pilar' => $content->pilar,
            'judul' => $content->judul,
            'deskripsi' => $content->deskripsi,
            'status' => (bool)$content->status,
            'media' => $content->media->map(fn($m) => [
                'id' => $m->id,
                'url' => asset('storage/' . $m->file_path),
                'file_path' => $m->file_path
            ])
        ]);
    });
});