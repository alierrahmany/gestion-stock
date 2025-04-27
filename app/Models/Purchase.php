<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Purchase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory;

    protected $table = 'purchases';
    protected $fillable = [
        'reference',
        'supplier_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'purchase_date'    // Make sure this matches your database column name
    ];
    protected $casts = [
        'purchase_date' => 'datetime',  // Ensure proper date casting
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];
    protected $dates = [
        'purchase_date'
    ];
    protected $appends = ['total_price'];
    protected $hidden = ['created_at', 'updated_at'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->product->price;
    }
}
