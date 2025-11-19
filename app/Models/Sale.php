<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public $timestamps = false;  // ← REQUIRED

    protected $table = 'sales';

    protected $fillable = [
        'product_id',
        'qty',
        'price',
        'admin_id',
        'admin_name',
        'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
