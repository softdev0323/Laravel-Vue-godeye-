<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;


class Album extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'year', 'cover', 'completed'];

    /**
     * Define the relationship: An album has many images.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
