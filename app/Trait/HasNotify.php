<?php

namespace App\Trait;

use App\Enums\CustomToastNotification;

trait HasNotify {
    
    public function notify(
        string|null $variant = null, 
        string $title, 
        string $message, 
        string|null $sender = null) {
        
        
        $this->dispatch(
            CustomToastNotification::get_dispatch_name(),
            (object) [
                'variant' => $variant ?? CustomToastNotification::get_default(),
                'sender' => $sender ?? CustomToastNotification::get_default_sender(),
                'title' => $title,
                'message' => $message,
            ]
        );
        
    }
    
}