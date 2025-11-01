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

    protected $fillable = [
        'product_id',
        'qty',
        'price',
        'date',
        'admin_name',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Automatically set Manila timezone when retrieving the date.
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Manila');
    }

    /**
     * Each sale belongs to one product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Format the sale date/time nicely.
     */
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('Y-m-d H:i:s') : null;
    }

    /**
     * Get cashier/admin name with fallback.
     */
    public function getCashierNameAttribute()
    {
        return $this->admin_name ?? 'N/A';
    }

    /**
     * Combined display info accessor.
     */
    public function getDisplayInfoAttribute()
    {
        return [
            'admin' => $this->cashier_name,
            'date'  => $this->formatted_date,
        ];
    }
}