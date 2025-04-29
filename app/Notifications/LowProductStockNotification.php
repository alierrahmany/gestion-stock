<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class LowProductStockNotification extends Notification
{
    use Queueable;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $urgencyLevel = $this->getUrgencyLevel($this->product->quantity);

        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => $this->product->quantity,
            'message' => "⚠️ Stock Faible: {$this->product->name} n'a plus que {$this->product->quantity} unité(s) en stock!",
            'urgency' => $urgencyLevel,
            'icon' => $this->getUrgencyIcon($urgencyLevel),
            'created_at' => now()->toIso8601String()
        ];
    }

    private function getUrgencyLevel($quantity): string
    {
        if ($quantity === 0) return 'critique';
        if ($quantity <= 2) return 'urgent';
        return 'warning';
    }

    private function getUrgencyIcon($level): string
    {
        return match ($level) {
            'critique' => '🚨',
            'urgent' => '⚠️',
            default => '⚠️',
        };
    }
}
