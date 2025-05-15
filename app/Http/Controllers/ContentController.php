<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function create(){
        return view('content');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'satuan'    => 'required|string|max:255',
            'pilar'     => 'required|string|max:255',
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'media.*'   => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf|max:512000', 
        ]);

        $content = Content::create([
            'name'      => $request->name,
            'satuan'    => $request->satuan,
            'pilar'     => $request->pilar,
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('uploads/media', 'public');
                $content->media()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('contents.create')->with('success', 'Konten berhasil dikirim!');
    }

    public function index()
    {
        $contents = Content::with('media')->paginate(10);
        return view('contents.index', compact('contents'));
    }

    public function edit($id)
    {
        $content = Content::findOrFail($id);
        return view('contents.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'satuan'    => 'required|string|max:255',
            'pilar'     => 'required|string|max:255',
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status'    => 'nullable|boolean',
        ]);

        $content = Content::findOrFail($id);
        $content->update($request->only(['name', 'satuan', 'pilar', 'judul', 'deskripsi', 'status']));

        return redirect()->route('contents.index')->with('success', 'Konten berhasil diperbarui!');
    }

    public function showJson($id)
    {
        $content = Content::with('media')->findOrFail($id);
        
        $response = [
            'id' => $content->id,
            'name' => $content->name,
            'satuan' => $content->satuan,
            'pilar' => $content->pilar,
            'judul' => $content->judul,
            'deskripsi' => $content->deskripsi,
            'status' => $content->status,
            'media' => []
        ];
        
        if ($content->media && $content->media->count() > 0) {
            foreach ($content->media as $media) {
                $response['media'][] = [
                    'id' => $media->id,
                    'file_path' => $media->file_path,
                    'url' => asset('storage/' . $media->file_path)
                ];
            }
        }
        
        return response()->json($response);
    }

    public function completed()
    {
        $contents = Content::with('media')->where('status', true)->paginate(10);
        return view('contents.completed', compact('contents'));
    }

    public function dashboard()
    {
        $totalContents = Content::count();
        $completedContents = Content::where('status', true)->count();
        $pendingContents = $totalContents - $completedContents;
        $totalMedia = \App\Models\Media::count();
        
        return view('dashboard', compact('totalContents', 'completedContents', 'pendingContents', 'totalMedia'));
    }
    
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        
        foreach ($content->media as $media) {
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
        }
        
        $content->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function search(Request $request)
    {
        $query = Content::with('media');
        
        if ($request->filled('pilar')) {
            $query->where('pilar', $request->pilar);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('satuan', 'like', "%{$keyword}%")
                  ->orWhere('pilar', 'like', "%{$keyword}%")
                  ->orWhere('judul', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status == 'completed');
        }
        
        $contents = $query->paginate(10)->withQueryString();
        
        if ($request->ajax()) {
            return view('contents.table', compact('contents'))->render();
        }
        
        return view('contents.index', compact('contents'));
    }
    
    public function downloadMedia($id)
    {
        $content = Content::with('media')->findOrFail($id);
        
        if ($content->media->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada media untuk diunduh');
        }
        
        $zipFileName = 'media_' . $content->id . '_' . time() . '.zip';
        $zipFilePath = storage_path('app/public/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/public/temp'))) {
            mkdir(storage_path('app/public/temp'), 0755, true);
        }
        
        $zip = new \ZipArchive();
        
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
            foreach ($content->media as $media) {
                $mediaPath = storage_path('app/public/' . $media->file_path);
                
                if (file_exists($mediaPath)) {
                    // Get the original filename from the path
                    $originalFileName = basename($media->file_path);
                    $zip->addFile($mediaPath, $originalFileName);
                }
            }
            
            $zip->close();
            
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        }
        
        return redirect()->back()->with('error', 'Gagal membuat file ZIP');
    }
    
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada konten yang dipilih']);
        }
        
        foreach ($ids as $id) {
            $content = Content::find($id);
            
            if ($content) {
                foreach ($content->media as $media) {
                    if (Storage::disk('public')->exists($media->file_path)) {
                        Storage::disk('public')->delete($media->file_path);
                    }
                }
                
                $content->delete();
            }
        }
        
        return response()->json(['success' => true, 'message' => 'Konten berhasil dihapus']);
    }
}
