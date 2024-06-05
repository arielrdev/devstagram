<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PerfilController extends Controller implements HasMiddleware
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function middleware():array
    {
        return [
            new Middleware('auth')
        ];
    }

    public function index() 
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        //Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => ['required', 'unique:users,username, '.auth()->user()->id, 'min:3', 'max:20']
        ]);

        if($request->imagen) {
            $imagen = $request->file('imagen');

            //Genera el nombre unico para cada img
            $nombreImagen = Str::uuid() . "." . $imagen->extension(); 

            //Imagen en memoria
            $imagenServidor = Image::configure(['driver' => 'imagick']);
            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000); //recortar la imagen

            //Path 
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath); //Guardar la imagen el servidor
        }

        //Guardar cambios
        $usuario = User::find(auth()->user()->id);

        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->save();

        //Redireccionar
        return redirect()->route('posts.index', $usuario->username);


    }
}
