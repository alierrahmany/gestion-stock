<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowProductStockNotification;

class ProductObserver
{
    public function updating(Product $product)
    {
        if ($product->isDirty('quantity')) {
            $newQuantity = $product->quantity;
            $oldQuantity = $product->getOriginal('quantity');

            // Only notify if quantity is decreasing to 1 or less
            if ($newQuantity <= 1 && $oldQuantity > 1) {
                // Get all admin and manager users
                $users = User::whereIn('role', ['admin', 'gestionnaire'])->get();

                // Send notification to each user
                foreach ($users as $user) {
                    $user->notify(new LowProductStockNotification($product));
                }
            }
        }
    }

    public function updated(Product $product)
    {
        // Check if quantity was changed
        if ($product->isDirty('quantity')) {
            $newQuantity = $product->quantity;

            // Notify when quantity is 5 or less (changed from 1)
            if ($newQuantity <= 5) {
                $users = User::whereIn('role', ['admin', 'gestionnaire'])->get();

                foreach ($users as $user) {
                    // Check if a similar notification doesn't already exist
                    $existingNotification = $user->notifications()
                        ->where('type', LowProductStockNotification::class)
                        ->where('data->product_id', $product->id)
                        ->whereNull('read_at')
                        ->first();

                    if (!$existingNotification) {
                        $user->notify(new LowProductStockNotification($product));
                    }
                }
            }
        }
    }
}
