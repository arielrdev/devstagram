<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;


class ImagenController extends Controller
{
    //
    public function store(Request $request)
    {
        $imagen = $request->file('file');

        //Genera el nombre unico para cada img
        $nombreImagen = Str::uuid() . "." . $imagen->extension(); 

        //Imagen en memoria
        $imagenServidor = Image::configure(['driver' => 'imagick']);
        $imagenServidor = Image::make($imagen);
        $imagenServidor->fit(1000, 1000); //recortar la imagen

        //Path 
        $imagenPath = public_path('uploads') . '/' . $nombreImagen;
        $imagenServidor->save($imagenPath); //Guardar la imagen el servidor

        return response()->json(['imagen' => $nombreImagen]);
    }
}
