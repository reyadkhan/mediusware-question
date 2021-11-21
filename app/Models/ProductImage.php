<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = ['file_path', 'thumbnail'];

    protected static function booted()
    {
        static::deleting(function($image) {
            Storage::delete($image->file_path);
        });
    }
}
