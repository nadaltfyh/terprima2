@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="p-6 bg-white rounded-xl shadow-md">

    <h1 class="text-xl font-semibold mb-4">Konten Terselesaikan</h1>

    <form action="{{ route('contents.search') }}" method="GET" class="mb-4">
    <div class="flex justify-between items-center gap-4 flex-wrap mb-4">

        <div class="flex gap-2 items-center">
            <select class="h-8 w-36 border rounded px-2 text-sm" id="bulkAction">
                <option value="">Bulk action</option>
                <option value="delete">Delete</option>
            </select>
            <button type="button" id="applyBulkAction" class="bg-red-700 text-white px-4 h-8 text-sm rounded">Apply</button>
        </div>

        <div class="flex items-center gap-2 flex-wrap">

            <input type="date" name="date" class="h-8 w-40 border rounded px-3 text-sm" />

            <select name="pilar" class="h-8 w-44 border rounded px-3 text-sm">
                <option value="">Semua kategori</option>
                <option value="News">News</option>
                <option value="Figure">Figure</option>
                <option value="Tips & Trick">Tips & Trick</option>
                <option value="Infographic">Infographic</option>
                <option value="Education">Education</option>
                <option value="Behind the Scene">Behind the Scene</option>
                <option value="Community Engagement">Community Engagement</option>
            </select>

            <button type="submit" class="bg-red-700 text-white px-4 h-8 text-sm rounded">Filter</button>

            <div class="relative w-48">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    name="search"
                    placeholder="Search"
                    class="pl-10 pr-4 h-8 w-full border rounded text-sm placeholder-gray-400"
                />
            </div>
        </div>

    </div>
</form>

    <div class="overflow-x-auto rounded-xl border border-gray-100">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2"><input type="checkbox" id="selectAll"></th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Satuan</th>
                    <th class="p-2">Pilar</th>
                    <th class="p-2">Judul</th>
                    <th class="p-2">Deskripsi</th>
                    <th class="p-2">Media</th>
<th class="p-2">Tanggal Upload</th>
<!-- <th class="p-2">Status</th> -->
<th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contents as $content)
                <tr class="border-t border-gray-100 hover:bg-gray-50 cursor-pointer content-row" data-id="{{ $content->id }}">
                    <td class="p-2" onclick="event.stopPropagation()">
                        <input type="checkbox" class="content-checkbox" value="{{ $content->id }}">
                    </td>
                    <td class="p-2">{{ $content->name }}</td>
                    <td class="p-2">{{ $content->satuan }}</td>
                    <td class="p-2">{{ $content->pilar }}</td>
                    <td class="p-2">{{ $content->judul }}</td>
                    <td class="p-2">{{ Str::limit($content->deskripsi, 50) }}</td>
                    <td class="p-2 text-center">{{ $content->media->count() ?? 0 }}</td>
<td class="p-2">{{ $content->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                    <!-- <td class="p-2">
                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                            Ditinjau
                        </span>
                    </td> -->
                    <td class="p-2" onclick="event.stopPropagation()">
                        <button 
                            class="text-red-600 hover:text-red-800 delete-content"
                            data-id="{{ $content->id }}"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contents->links() }}
    </div>

    <div id="editModal" class="fixed inset-0 bg-black/50 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-10 rounded-xl w-full max-w-4xl relative">
            <button class="absolute top-3 right-3 text-gray-500" onclick="closeModal()">✕</button>

            <form id="editForm">
                @csrf
                <input type="hidden" name="id" id="contentId">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <input name="name" id="editName" class="border p-2 rounded" placeholder="Nama">
                    <input name="satuan" id="editSatuan" class="border p-2 rounded" placeholder="Satuan">
                    <input name="pilar" id="editPilar" class="border p-2 rounded" placeholder="Pilar">
                </div>

                <input name="judul" id="editJudul" class="w-full border p-2 rounded mb-4" placeholder="Judul">

                <textarea name="deskripsi" id="editDeskripsi" class="w-full border p-2 rounded mb-4" rows="4" placeholder="Deskripsi"></textarea>

                <div id="mediaGrid" class="grid grid-cols-4 md:grid-cols-6 gap-2 mb-4">
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" id="editStatus" name="status" class="w-5 h-5 text-red-700 border-gray-300 rounded focus:ring-red-700">
                    <label for="editStatus" class="ml-2 text-gray-700">Sudah ditinjau</label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="downloadZipBtn" class="bg-green-700 text-white px-4 py-2 rounded">Unduh ZIP</button>
                    <button type="submit" class="bg-sky-700 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

<script>
    const modal = document.getElementById('editModal');
    
    // Klik pada baris untuk membuka modal edit
    document.querySelectorAll('.content-row').forEach(row => {
        row.addEventListener('click', () => {
            const id = row.getAttribute('data-id');
            openEditModal(id);
        });
    });

    function openEditModal(id) {
        fetch(`/contents/${id}`)
            .then(res => res.json())
            .then(data => {
                console.log("Data dari server:", data);
                document.getElementById('contentId').value = data.id;
                document.getElementById('editName').value = data.name;
                document.getElementById('editSatuan').value = data.satuan;
                document.getElementById('editPilar').value = data.pilar;
                document.getElementById('editJudul').value = data.judul;
                document.getElementById('editDeskripsi').value = data.deskripsi;
                document.getElementById('editStatus').checked = data.status;

                const mediaGrid = document.getElementById('mediaGrid');
                mediaGrid.innerHTML = '';
                
                if (data.media && data.media.length > 0) {
                    data.media.forEach(media => {
                        console.log("Media URL:", media.url);
                        console.log("Media file_path:", media.file_path);
                        
                        if (media.file_path) {
                            mediaGrid.innerHTML += `
                            <div class="bg-gray-100 p-4 rounded flex flex-col items-center justify-center relative group">
                                <img src="/storage/${media.file_path}" alt="" class="h-12 object-contain">
                                <button type="button" class="absolute top-1 right-1 bg-white/80 rounded p-1 shadow group-hover:block hidden" onclick="event.stopPropagation(); downloadSingleMedia('${media.id}')">
                                    <i class="fa fa-download"></i>
                                </button>
                            </div>
                            `;
                        }
                    });
                } else {
                    mediaGrid.innerHTML = '<div class="col-span-full text-center text-gray-500">Tidak ada media</div>';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('contentId').value;

        fetch(`/contents/${id}/update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: this.name.value,
                satuan: this.satuan.value,
                pilar: this.pilar.value,
                judul: this.judul.value,
                deskripsi: this.deskripsi.value,
                status: this.status.checked ? 1 : 0
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
    
    document.querySelectorAll('.delete-content').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            if (confirm('Apakah Anda yakin ingin menghapus konten ini?')) {
                const id = button.getAttribute('data-id');
                fetch(`/contents/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    });
    
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.content-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    document.getElementById('applyBulkAction').addEventListener('click', function() {
        const action = document.getElementById('bulkAction').value;
        if (action === 'delete') {
            const selectedIds = Array.from(document.querySelectorAll('.content-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length === 0) {
                alert('Pilih setidaknya satu konten');
                return;
            }
            
            if (confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} konten?`)) {
                Promise.all(selectedIds.map(id => 
                    fetch(`/contents/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json())
                )).then(() => location.reload());
            }
        }
    });
</script>

</div>
@endsection
