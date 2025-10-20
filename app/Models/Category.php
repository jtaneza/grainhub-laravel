<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; // matches old table
    protected $fillable = ['name'];
    public $timestamps = false; // disable created_at / updated_at
}
