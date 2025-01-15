<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_de_question',
        'question',
        'reponse',
        'video_url',
        'likes_count',
    ];

    // Accesseurs pour manipuler les attributs (par exemple, pour formater les données si nécessaire)
    public function getLikesCountAttribute($value)
    {
        return (int) $value;
    }
}
