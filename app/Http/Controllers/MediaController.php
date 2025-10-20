<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display all uploaded media files.
     */
    public function index()
    {
        $media_files = Media::all();
        return view('media.index', compact('media_files'));
    }

    /**
     * Store uploaded media file(s).
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_upload' => 'required',
            'file_upload.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ✅ Normalize the input (support both single and multiple uploads)
        $files = is_array($request->file('file_upload'))
            ? $request->file('file_upload')
            : [$request->file('file_upload')];

        foreach ($files as $file) {
            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $uploadPath = public_path('uploads/products');

            // Create folder if not exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Move file to upload directory
            $file->move($uploadPath, $fileName);

            // ✅ Save to database (timestamps disabled in Media model)
            Media::create([
                'file_name' => $fileName,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(), // optional but good to include
            ]);
        }

        return redirect()->route('media.index')->with('success', 'Photo(s) uploaded successfully.');
    }

    /**
     * Delete media file.
     */
    public function destroy(Media $media)
    {
        $filePath = public_path('uploads/products/' . $media->file_name);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $media->delete();

        return redirect()->route('media.index')->with('success', 'Photo deleted successfully.');
    }
}
