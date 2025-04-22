<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'contact',
        'address'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    public function sales()
    {
        return $this->hasMany(Sale::class, 'client_id');
    }
}
