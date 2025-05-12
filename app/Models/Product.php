<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'categorie_id',
        'date',
        'file_name',
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    protected $appends = ['image_url', 'current_stock', 'average_price'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getCurrentStockAttribute()
    {
        $totalPurchased = $this->purchases->sum('quantity');
        $totalSold = $this->sales->sum('quantity');
        return $totalPurchased - $totalSold;
    }

    public function getAveragePriceAttribute()
    {
        if ($this->purchases->count() > 0) {
            return $this->purchases->avg('price');
        }
        return 0;
    }

    public function getImageUrlAttribute()
    {
        if ($this->file_name === 'no_image.jpg') {
            return asset('images/default-product.png');
        }
        return Storage::url('products/'.$this->file_name);
    }

    protected function defaultImageUrl()
    {
        return asset('storage/products/no_image.jpg');
    }

    public function hasLowStock()
    {
        return $this->current_stock <= 5;
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get all sale items for the product
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
