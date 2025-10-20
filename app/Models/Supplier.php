<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    // ⛔ Disable created_at / updated_at
    public $timestamps = false;

    protected $fillable = [
        'name',
        'contact',
        'email',
        'address',
    ];
}
