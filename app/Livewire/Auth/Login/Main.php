<?php

namespace App\Livewire\Auth\Login;

use App\Library\SessionHelper;

use App\Services\AuthServices;
use App\Enums\AuthField;

use App\Trait\HasNotify;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\Attributes;

class Main extends Component
{
    use HasNotify;
    
    public $inp_login;
    public $inp_password;
    
    public function mount() {
        SessionHelper::forgetSession('register_step');
        SessionHelper::forgetSession('register_step_initialized');
    }
    
    public function submit_auth() {
        $this->resetValidation();
        $this->validate(
            [
                'inp_login' => 'required|string|max:255',
                'inp_password' => 'required|string',
            ],
            [
                'inp_login.required' => 'Please enter your :attribute.',
                'inp_login.max' => ':attribute must not exceed 255 characters.',
                'inp_password.required' => 'Please enter your :attribute.',
            ],
            [
                'inp_login' => 'Email address or username',
                'inp_password' => 'Password',
            ],
        );
        
        $auth = app(AuthServices::class)->auth($this->inp_login, $this->inp_password);
        // dump(
        //     $auth,
        //     [
        //         'field' => AuthField::detect($this->inp_login)->value,
        //         'value' => $this->inp_login
        //     ], [
        //         'field' => AuthField::detect('oinfeoivwioevcoinasdofnoinefalknsfio402')->value,
        //         'value' => 'oinfeoivwioevcoinasdofnoinefalknsfio402'
        //     ], [
        //         'field' => AuthField::detect('iasnbfioubuio3e@gmail.com')->value,
        //         'value' => 'iasnbfioubuio3e@gmail.com'
        //     ]
        // );
        $dispatch = [
            'notification' => [
                'variant' => $auth->status ? 'info' : 'warning',
                'sender' => 'System',
                'title' => $auth->title,
                'message' => $auth->message,
            ],
        ];
        
        if ($auth->status) {
            
            $dispatch['redirect'] = route('app.dashboard.home');
            $dispatch['navigate'] = false;
            
        } else {
            
            $this->addError('inp_login', 'Make sure your email are correct');
            $this->addError('inp_password', 'Make sure your password are correct');
            
        }
        
        $this->dispatch('form_process', $dispatch);
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
