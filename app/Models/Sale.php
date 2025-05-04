<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'client_id',
        'quantity',
        'price',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'Deleted Product'
        ]);
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault([
            'name' => 'Unknown Client'
        ]);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('d/m/Y') : 'N/A';
    }

    public function getTotalAmountAttribute()
    {
        return $this->quantity * $this->price;
    }
}
