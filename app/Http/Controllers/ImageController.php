<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:1024'
        ]);
        $path = $request->file('file')->store('product-images', 'public');
        return response()->json(compact('path'));
    }
}
