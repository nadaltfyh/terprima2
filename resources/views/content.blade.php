<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerPRIMA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-white text-gray-700">
    <div class="flex flex-col lg:flex-row min-h-screen">

        <div class="block lg:hidden">
            <img src="{{ asset('img/header.png') }}" alt="Header TerPRIMA" class="w-full">
        </div>

        <div class="lg:w-1/3 w-full bg-no-repeat bg-center bg-cover text-white flex flex-col justify-between pt-28 lg:pt-10 px-6 hidden lg:flex"
             style="background-image: url('{{ asset('img/bg.png') }}');">
            <div></div>
            <div class="text-sm text-center text-gray-400 mt-8 lg:hidden">
                <p>Copyright © TerPRIMA. 2025. Supported by Point of View</p>
            </div>
        </div>

        <div class="lg:w-2/3 w-full p-6 sm:p-10 flex flex-col justify-between mt-10 lg:mt-20">
        <div>
        <h2 class="text-2xl font-bold mb-6 text-gray-700 text-center lg:text-left">
            PORTAL SOCIAL MEDIA CONTENT COLLECTION TERPRIMA
        </h2>

                <form action="{{ route('contents.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4 text-gray-600">
                        <div>
                            <label for="name" class="block font-medium">Nama</label>
                            <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label for="satuan" class="block font-medium">Satuan</label>
                            <input type="text" name="satuan" id="satuan" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label for="pilar" class="block font-medium">Pilar</label>
                            <select name="pilar" id="pilar" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Pilar --</option>
                                <option value="News">News</option>
                                <option value="Figure">Figure</option>
                                <option value="Tips & Trick">Tips & Trick</option>
                                <option value="Infographic">Infographic</option>
                                <option value="Education">Education</option>
                                <option value="Behind the Scene">Behind the Scene</option>
                                <option value="Community Engagement">Community Engagement</option>
                            </select>
                        </div>
                        <div>
                            <label for="judul" class="block font-medium">Judul</label>
                            <input type="text" name="judul" id="judul" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label for="deskripsi" class="block font-medium">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
                        </div>
                        <div>
                            <label for="media" class="block font-medium">Upload Media</label>
                            <div
                                id="customUpload"
                                class="mt-3 w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-300 transition"
                            >
                                <i class="fas fa-cloud-upload-alt text-gray-600 text-2xl"></i>
                            </div>
                            <input type="file" name="media[]" id="fileInput" multiple class="hidden">
                            <p id="fileCount" class="text-sm text-gray-500 mt-1"></p>
                        </div>

                        <div class="pt-4 text-right">
                        <button type="submit" class="w-full sm:w-auto bg-red-700 text-white rounded-full px-6 py-2 hover:bg-red-800">
                            Kirim konten
                        </button>
                    </div>

                    </div>
                </form>
            </div>

            <div class="text-sm text-center text-gray-400 mt-8 lg:hidden">
            <p>Copyright © TerPRIMA. 2025. Supported by Point of View</p>
        </div>

        </div>
    </div>

    @if(session('success'))
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="relative w-[90%] max-w-md h-[540px] flex flex-col items-center p-6"
             style="background: url('{{ asset('img/bg_modal.png') }}') no-repeat center center; background-size: contain;">
             
            <img src="{{ asset('img/icon_check.png') }}" class="w-20 h-20 mt-20 mb-4" alt="Icon Sukses" />
            <p class="text-lg font-semibold text-center mb-4 text-white">Data berhasil terupload</p>
            <button onclick="document.getElementById('successModal').remove()"
                class="bg-white text-red-700 font-bold py-2 px-6 rounded-full hover:bg-gray-100 transition">
                Selesai
            </button>
        </div>
    </div>
    @endif

    <script>
        const fileInput = document.getElementById('fileInput');
        const customUpload = document.getElementById('customUpload');
        const fileCount = document.getElementById('fileCount');

        customUpload.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            fileCount.textContent = `Total file dipilih: ${files.length}`;
        });
    </script>
</body>
</html>
