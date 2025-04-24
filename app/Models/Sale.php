<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $fillable = ['product_id', 'qty', 'price', 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    // app/Models/Sale.php

protected $casts = [
    'date' => 'date', // or 'datetime' if you need time information
];

    
}

