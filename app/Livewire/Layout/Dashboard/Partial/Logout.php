<?php

namespace App\Livewire\Layout\Dashboard\Partial;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    
    public function logout() {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('auth.login');
    }
    
    public function render()
    {
        return view('livewire.layout.dashboard.partial.logout');
    }
}
