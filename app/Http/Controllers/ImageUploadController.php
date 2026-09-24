<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096', // 4MB max
        ]);

        $path = $request->file('image')->store('public/images');

        $image = Image::create([
            'path' => $path,
        ]);

        return response()->json(['url' => Storage::url($path)]);
    }
}
