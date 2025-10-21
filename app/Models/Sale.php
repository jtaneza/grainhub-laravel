<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    public $timestamps = false;

    // Include admin name + time tracking
    protected $fillable = [
        'product_id',
        'qty',
        'price',
        'date',
        'admin_name',
    ];

    /**
     * Automatically cast 'date' as Carbon instance for easy formatting.
     */
    protected $casts = [
    'date' => 'datetime',
];


    /**
     * Each sale belongs to one product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Format the sale date/time when displayed in 24-hour format.
     */
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('Y-m-d H:i:s') : null;
    }

    /**
     * Convenient accessor to get cashier name with fallback.
     */
    public function getCashierNameAttribute()
    {
        return $this->admin_name ?? 'N/A';
    }

    /**
     * Combined accessor for display in tables.
     */
    public function getDisplayInfoAttribute()
    {
        return [
            'admin' => $this->admin_name,
            'date'    => $this->formatted_date,
        ];
    }
}
