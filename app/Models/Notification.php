<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'action_user_id', 'message', 'read', 'type'];


    public static $types = [
        'product' => 'Products',
        'sale' => 'Sales',
        'purchase' => 'Purchases'
    ];
    // User who performed the action
    public function actionUser()
    {
        return $this->belongsTo(User::class, 'action_user_id');
    }

    // User who receives the notification
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    public function getTypeBadgeAttribute()
{
    return match($this->type) {
        'product' => '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Product</span>',
        'sale' => '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Sale</span>',
        'purchase' => '<span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Purchase</span>',
        default => '<span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">System</span>'
    };
}
}
