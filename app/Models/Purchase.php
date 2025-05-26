<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity',
        'price',    
        'date',
        'reference'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    protected $appends = ['formatted_date'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'Deleted Product'
        ]);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
