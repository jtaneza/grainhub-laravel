<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users'; // ensure matches your DB
    protected $fillable = ['name', 'username', 'password', 'user_level', 'status', 'last_login'];
    public $timestamps = false; // disable timestamps if your table doesn’t have created_at/updated_at

    public function group()
    {
        // user_level in users table refers to group_level in user_groups
        return $this->belongsTo(\App\Models\Group::class, 'user_level', 'group_level');
    }
}
