<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $img = $request->file('file');

        $imageName = Str::uuid() . '.' . $img->extension();

        $imageServer = Image::make($img);
        $imageServer->fit(1000, 1000);

        $imagePath = public_path('uploads') . '/' . $imageName;
        $imageServer->save( $imagePath );

        return response()->json(['image' => $imageName]);
    }
}
