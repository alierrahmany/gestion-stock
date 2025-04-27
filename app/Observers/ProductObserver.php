<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowProductStockNotification;

class ProductObserver
{
    public function updated(Product $product)
    {
        if ($product->quantity <= 1 && $product->getOriginal('quantity') > 1) {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new LowProductStockNotification($product));
            }
        }
    }
}
