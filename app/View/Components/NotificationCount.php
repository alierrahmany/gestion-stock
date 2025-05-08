<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class NotificationCount extends Component
{
    public $count;

    public function __construct()
    {
        $this->count = Auth::user()->notifications()->unread()->count();
    }

    public function render()
    {
        return view('components.notification-count');
    }
}
