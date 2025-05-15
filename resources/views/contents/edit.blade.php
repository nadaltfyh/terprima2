@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Edit Konten</h2>
    <form method="POST" action="{{ route('contents.update', $content->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label>Name</label>
            <input name="name" value="{{ $content->name }}" class="w-full border p-2 rounded">
        </div>
        <div class="mb-4">
            <label>Satuan</label>
            <input name="satuan" value="{{ $content->satuan }}" class="w-full border p-2 rounded">
        </div>
        <div class="mb-4">
            <label>Pilar</label>
            <input name="pilar" value="{{ $content->pilar }}" class="w-full border p-2 rounded">
        </div>
        <div class="mb-4">
            <label>Judul</label>
            <input name="judul" value="{{ $content->judul }}" class="w-full border p-2 rounded">
        </div>
        <div class="mb-4">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="w-full border p-2 rounded">{{ $content->deskripsi }}</textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
