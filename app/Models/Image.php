<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Album;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'caption',
        'image_url',
        'print'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
