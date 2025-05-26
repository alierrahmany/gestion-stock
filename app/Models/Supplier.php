<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'email',
        'contact',
        'address'
    ];



    /**
     * Get all products from this supplier
     */
    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class, 'supplier_id');
    }





}
