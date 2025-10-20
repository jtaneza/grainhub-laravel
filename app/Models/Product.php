<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'quantity',
        'buy_price',
        'sale_price',
        'media_id',
        'category_id',
        'supplier_id',
        'date',
    ];

    // ✅ Disable Laravel timestamps because your table doesn't have created_at / updated_at
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * ✅ Accessor: Format the date like the old Grainhub system
     * Example: "September 24, 2025, 12:57:53 pm"
     */
    public function getFormattedDateAttribute()
    {
        return $this->date
            ? Carbon::parse($this->date)->setTimezone('Asia/Manila')->format('F j, Y, g:i:s a')
            : null;
    }

    /** ✅ Relationships */
    public function category()
    {
        // 🔧 FIXED TYPO: Should be 'category_id', not 'categorie_id'
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * ✅ Accessor: Get full image URL or fallback image
     */
    public function getImageUrlAttribute()
    {
        if ($this->media && $this->media->file_name) {
            return asset('uploads/products/' . $this->media->file_name);
        }

        return asset('uploads/products/default.png'); // fallback image
    }
}
