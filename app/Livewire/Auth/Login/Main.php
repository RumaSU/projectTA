<?php

namespace App\Livewire\Auth\Login;

use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\Attributes;

class Main extends Component
{
    public $inp_login;
    public $inp_password;
    
    public function submit_auth() {
        $this->resetValidation();
        $validate_auth = $this->validateAuthForm();
        
        if ($validate_auth->fails()) {
            
            foreach ($validate_auth->errors()->messages() as $field => $messages) {
                
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
                
            }
            
            return;
        }
        
        $field = $this->getFieldType($this->inp_login);
        
        if (Auth::attempt([$field => $this->inp_login, 'password' => $this->inp_password])) {
            session()->regenerate();
            
            $this->dispatchNotification('success', 'Welcome back!', 'Authentication successful.');
            
            
            return;
        }
        
        $this->dispatchNotification('danger', 'Oops!', 'Invalid credentials. Please check your login and try again.');
    }
    
    #[Attributes\Layout('livewire.layout.auth.template')]
    public function render()
    {
        return view('livewire.auth.login.main');
    }
    
    
    // Private Function
    private function validateAuthForm() {
        $validateData = [
            'inp_login'  => $this->inp_login,
            'inp_password' => $this->inp_password,
        ];
        
        $validateRules = [
            'inp_login' => 'required|string|max:255',
            'inp_password' => 'required|string',
        ];
        
        $validateMessages = [
            'inp_login.required' => 'Please enter your email address or username.',
            'inp_login.max' => 'Email address or username must not exceed 255 characters.',
            'inp_password.required' => 'Please enter your password.',
        ];
        
        $validator = Validator::make($validateData, $validateRules, $validateMessages);
        
        return $validator;
    }
    
    private function getFieldType($val_login) {
        if (filter_var($val_login, FILTER_VALIDATE_EMAIL)) return 'email';
        return 'username';
    }
    
    // info, success, warning, danger
    private function dispatchNotification($variant = 'info', $title, $message) {
        $this->dispatch('customnotify', (object) [
            'variant' => $variant,
            'sender' => 'System',
            'title' => $title,
            'message' => $message,
        ]);
    }
}
