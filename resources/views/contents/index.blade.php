@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Add CSRF token meta tag to the head -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-6 bg-white rounded-xl shadow-md">

    <h1 class="text-xl font-semibold mb-4">Daftar Konten</h1>

    <form action="{{ route('contents.search') }}" method="GET" class="mb-4">
    <div class="flex justify-between items-center gap-4 flex-wrap mb-4">

        <div class="flex gap-2 items-center">
            <select class="h-8 w-36 border rounded px-2 text-sm" id="bulkAction">
                <option value="">Bulk action</option>
                <option value="delete">Delete</option>
            </select>
            <button type="button" id="applyBulkAction" class="bg-red-700 hover:bg-red-800 text-white px-4 h-8 text-sm rounded cursor-pointer">Apply</button>
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

            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-4 h-8 text-sm rounded cursor-pointer">Filter</button>

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
                        <!-- Changed: Remove the status check from checkbox, this is only for selection -->
                        <input type="checkbox" class="content-checkbox" value="{{ $content->id }}">
                    </td>
                    <td class="p-2">{{ $content->name }}</td>
                    <td class="p-2">{{ $content->satuan }}</td>
                    <td class="p-2">{{ $content->pilar }}</td>
                    <td class="p-2">{{ $content->judul }}</td>
                    <td class="p-2">{{ Str::limit($content->deskripsi, 50) }}</td>
                    <td class="p-2 text-center">{{ $content->media->count() ?? 0 }}</td>
                    <td class="p-2">{{ $content->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                    <!-- Uncommented: Show status visually -->
                    <!-- <td class="p-2">
                        <span class="px-2 py-1 rounded-full text-xs {{ $content->status ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $content->status ? 'Ditinjau' : 'Belum ditinjau' }}
                        </span>
                    </td> -->
                    <td class="p-2" onclick="event.stopPropagation()">
                    <button class="text-red-600 hover:text-red-800 delete-content cursor-pointer" data-id="{{ $content->id }}">
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
            <button class="absolute top-3 right-3 text-gray-500 cursor-pointer" onclick="closeModal()">✕</button>

            <form id="editForm">
                @csrf
                <input type="hidden" name="id" id="contentId">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <input name="name" id="editName" class="border p-2 rounded" placeholder="Nama">
                    <input name="satuan" id="editSatuan" class="border p-2 rounded" placeholder="Satuan">
                    <input name="pilar" id="editPilar" class="border p-2 rounded" placeholder="Pilar">
                </div>

                <div class="relative mb-4">
                <input name="judul" id="editJudul" class="w-full border p-2 rounded pr-10" placeholder="Judul">
                <button type="button" onclick="copyToClipboard('editJudul')" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black cursor-pointer">
                    <i class="fa-solid fa-clipboard"></i>
                </button>
            </div>

            <div class="relative mb-4">
                <textarea name="deskripsi" id="editDeskripsi" class="w-full border p-2 rounded pr-10" rows="4" placeholder="Deskripsi"></textarea>
                <button type="button" onclick="copyToClipboard('editDeskripsi')" class="absolute right-2 top-2 text-gray-500 hover:text-black cursor-pointer">
                    <i class="fa-solid fa-clipboard"></i>
                </button>
            </div>

                <div id="mediaGrid" class="grid grid-cols-4 md:grid-cols-6 gap-2 mb-4">
                    <!-- JS will populate this -->
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" id="editStatus" name="status" value="1" class="w-5 h-5 text-red-700 border-gray-300 rounded focus:ring-red-700 cursor-pointer">
                    <label for="editStatus" class="ml-2 text-gray-700">Sudah ditinjau</label>
                </div>

                <div class="flex justify-end gap-2">
                    <!-- <button type="button" class="bg-red-700 text-white px-4 py-2 rounded" onclick="closeModal()">Keluar</button> -->
                    <button type="submit" class="bg-sky-700 hover:bg-sky-800 text-white px-4 py-2 rounded cursor-pointer">Simpan</button>
                    <button type="button" id="downloadZipBtn" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded cursor-pointer">Unduh ZIP</button>                </div>
            </form>
        </div>
    </div>

    <div id="deleteConfirmModal" class="fixed inset-0 bg-black/50 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md relative">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
            <p class="mb-6">Apakah Anda yakin ingin menghapus konten ini?</p>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded" onclick="closeDeleteModal()">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="bg-red-700 text-white px-4 py-2 rounded">Hapus</button>
            </div>
        </div>
    </div>

    <div id="bulkDeleteConfirmModal" class="fixed inset-0 bg-black/50 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md relative">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus Massal</h3>
            <p class="mb-6">Apakah Anda yakin ingin menghapus semua konten yang dipilih?</p>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded cursor-pointer" onclick="closeBulkDeleteModal()">Batal</button>
                <button type="button" id="confirmBulkDeleteBtn" class="bg-red-700 text-white px-4 py-2 rounded cursor-pointer">Hapus</button>
            </div>
        </div>
    </div>

    <div id="imagePreviewModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="relative bg-white rounded-xl p-2 max-w-xl w-full shadow-lg">
        <button onclick="closeImagePreview()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl z-10 cursor-pointer">
            <i class="fas fa-times"></i>
        </button>
        <img id="previewImage" src="" class="max-h-[70vh] w-auto mx-auto rounded-lg object-contain" />
    </div>
</div>


<script>
    const modal = document.getElementById('editModal');
    
    document.querySelectorAll('.content-row').forEach(row => {
        row.addEventListener('click', () => {
            const id = row.getAttribute('data-id');
            openEditModal(id);
        });
    });

    document.querySelectorAll('.delete-content').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = button.getAttribute('data-id');
            openDeleteModal(id);
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
                    const mediaElement = document.createElement('div');
                    mediaElement.className = 'bg-gray-100 p-2 rounded flex flex-col items-center relative group';
                    
                    // Determine file type and display accordingly
                    const fileExt = media.file_path.split('.').pop().toLowerCase();
                    let previewContent = '';
                    
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                        previewContent = `<img src="/storage/${media.file_path}" 
                            alt="" 
                            class="h-20 w-full object-contain cursor-pointer previewable" 
                            data-src="/storage/${media.file_path}">`;                    
                    
                    } else if (['mp4', 'mov'].includes(fileExt)) {
                        previewContent = `
                            <video class="h-20 w-full object-contain">
                                <source src="/storage/${media.file_path}" type="video/${fileExt}">
                                Browser tidak mendukung video.
                            </video>
                        `;
                    } else if (fileExt === 'pdf') {
                        previewContent = `
                            <div class="h-20 w-full flex items-center justify-center bg-white">
                                <i class="fas fa-file-pdf text-4xl text-red-500"></i>
                            </div>
                        `;
                    } else {
                        previewContent = `
                            <div class="h-20 w-full flex items-center justify-center bg-white">
                                <i class="fas fa-file text-4xl text-gray-500"></i>
                            </div>
                        `;
                    }
                    
                    mediaElement.innerHTML = `
                        ${previewContent}
                        <a href="/storage/${media.file_path}" 
                        download="${media.file_path.split('/').pop()}"
                        class="absolute top-2 right-2 bg-black/50 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-download text-xs"></i>
                        </a>
                    `;                    
                    
                    mediaGrid.appendChild(mediaElement);
                });
            } else {
                mediaGrid.innerHTML = '<div class="col-span-full text-center text-gray-500">Tidak ada media</div>';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        })
        .catch(error => {
            console.error('Error fetching content:', error);
            alert('Error loading content. Please try again.');
        });
}


    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }


    document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const id = document.getElementById('contentId').value;
    
    // Add CSRF token to formData instead of headers - this is important for multipart/form-data
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Make sure we're not adding duplicate tokens if the form already has a CSRF field
    if (!formData.has('_token')) {
        formData.append('_token', token);
    }
    
    fetch(`/contents/${id}/update`, {
        method: 'POST',
        body: formData
        // Don't set Content-Type header when sending FormData - the browser will set it automatically with proper boundaries
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeModal();
            window.location.reload(); // Reload the page after successful update
        } else {
            alert('Update failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating content. Please try again.');
    });
});

    // Download ZIP functionality
    document.getElementById('downloadZipBtn').addEventListener('click', function() {
        const contentId = document.getElementById('contentId').value;
        window.location.href = `/contents/${contentId}/download-media`;
    });

    // Delete confirmation modal functions
    function openDeleteModal(id) {
        const deleteModal = document.getElementById('deleteConfirmModal');
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        
        document.getElementById('confirmDeleteBtn').onclick = function() {
            deleteContent(id);
        };
    }

    function closeDeleteModal() {
        const deleteModal = document.getElementById('deleteConfirmModal');
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
    }

    // Fixed delete function to handle errors and properly handle the CSRF token
    function deleteContent(id) {
        // Get the CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (!token) {
            console.error('CSRF token not found');
            alert('Error: CSRF token not found. Please refresh the page and try again.');
            return;
        }
        
        fetch(`/contents/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeDeleteModal();
                window.location.reload(); // Reload the page after successful delete
            } else {
                alert('Failed to delete content: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting content. Please try again.');
        });
    }

    // Bulk action functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.content-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });

    document.getElementById('applyBulkAction').addEventListener('click', function() {
        const action = document.getElementById('bulkAction').value;
        if (action === 'delete') {
            const selectedIds = Array.from(document.querySelectorAll('.content-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length === 0) {
                alert('Pilih setidaknya satu konten untuk dihapus');
                return;
            }
            openBulkDeleteModal(selectedIds);
        }
    });

    function openBulkDeleteModal(ids) {
        const bulkDeleteModal = document.getElementById('bulkDeleteConfirmModal');
        bulkDeleteModal.classList.remove('hidden');
        bulkDeleteModal.classList.add('flex');
        
        document.getElementById('confirmBulkDeleteBtn').onclick = function() {
            bulkDeleteContents(ids);
        };
    }

    function closeBulkDeleteModal() {
        const bulkDeleteModal = document.getElementById('bulkDeleteConfirmModal');
        bulkDeleteModal.classList.add('hidden');
        bulkDeleteModal.classList.remove('flex');
    }

    // Fixed bulk delete function to properly handle errors
    function bulkDeleteContents(ids) {
        // Get the CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (!token) {
            console.error('CSRF token not found');
            alert('Error: CSRF token not found. Please refresh the page and try again.');
            return;
        }
        
        fetch('/contents/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeBulkDeleteModal();
                window.location.reload(); // Reload the page after successful bulk delete
            } else {
                alert('Failed to delete contents: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting contents. Please try again.');
        });
    }

    function copyToClipboard(elementId) {
        const el = document.getElementById(elementId);
        el.select();
        el.setSelectionRange(0, 99999); 

        navigator.clipboard.writeText(el.value).then(() => {
            alert("Teks berhasil disalin!");
        }).catch(err => {
            alert("Gagal menyalin teks");
            console.error(err);
        });
    }

    document.addEventListener('click', function (e) {
    if (e.target.classList.contains('previewable')) {
        const src = e.target.getAttribute('data-src');
        const previewModal = document.getElementById('imagePreviewModal');
        const previewImage = document.getElementById('previewImage');
        previewImage.src = src;
        previewModal.classList.remove('hidden');
        previewModal.classList.add('flex');
        }
    });

        function closeImagePreview() {
            const previewModal = document.getElementById('imagePreviewModal');
            previewModal.classList.add('hidden');
            previewModal.classList.remove('flex');
            document.getElementById('previewImage').src = '';
        }

</script>

</div>
@endsection