<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id'
    ];

    public function user()
    {
        // Un post pertenece a un usuario
        return $this->belongsTo(User::class)->select(['name', 'username']);
    }

    public function comentarios()
    {
        //un post va tener multiples comentarios
        return $this->hasMany(Comentario::class);
    }

    public function likes()
    {
        //Un post tiene muchos likes
        return $this->hasMany(Like::class);
    }

    public function checkLike (User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }


}
