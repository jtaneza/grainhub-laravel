<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;

class MediaController extends Controller
{
    public function index()
    {
        $media_files = Media::all();
        return view('media.index', compact('media_files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_upload.*' => 'required|image|max:5120', // max 5MB
        ]);

        if ($request->hasFile('file_upload')) {
            foreach ($request->file('file_upload') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/products', $filename);

                Media::create([
                    'file_name' => $filename,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('media.index')->with('success', 'Photo(s) uploaded successfully.');
    }

    public function destroy(Media $media)
    {
        if ($media->file_name) {
            Storage::delete('public/products/' . $media->file_name);
        }

        $media->delete();
        return redirect()->route('media.index')->with('success', 'Photo deleted successfully.');
    }
}
