<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory;

    protected $table = 'purchases';
    protected $fillable = [
        'product_id',
        'client_id',
        'quantity',
        'total_price',
        'purchase_date'
    ];
    protected $casts = [
        'purchase_date' => 'datetime',
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
    public function getPurchaseDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}
