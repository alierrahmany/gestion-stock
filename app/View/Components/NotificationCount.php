<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NotificationCount extends Component
{
    public $count;

    public function __construct()
    {
        $this->count = auth()->user()->unreadNotifications()->count();
    }

    public function render()
    {
        return view('components.notification-count');
    }
}
