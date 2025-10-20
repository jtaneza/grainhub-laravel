<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['file_name', 'file_type', 'file_size'];

    public $timestamps = false; // ✅ Disable Laravel timestamps

    // Optional: accessor for URL
    public function getUrlAttribute()
    {
        return asset('storage/products/' . $this->file_name);
    }
}
