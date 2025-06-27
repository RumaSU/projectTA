<?php

namespace App\Livewire\Auth\Login;

use App\Library\SessionHelper;
use App\Library\UserHelper;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\Attributes;

class Main extends Component
{
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
        
        // $validate_auth = $this->validateAuthForm();
        
        // if ($validate_auth->fails()) {
            
        //     foreach ($validate_auth->errors()->messages() as $field => $messages) {
                
        //         foreach ($messages as $message) {
        //             $this->addError($field, $message);
        //         }
                
        //     }
            
        //     return;
        // }
        
        $field = $this->getFieldType($this->inp_login);
        
        $credentialsAuth = [
            $field => $this->inp_login,
            'password' => $this->inp_password,
        ];
        
        $statusAuthAttempt = UserHelper::authUser($credentialsAuth);
        
        if (! $statusAuthAttempt->status) {
            $dataDispatch = [
                'notification' => [
                    'variant' => 'warning',
                    'sender' => 'System',
                    'title' => $statusAuthAttempt->title,
                    'message' => $statusAuthAttempt->message,
                ],
                // 'redirect' => route($statusCheckStep->route),
                // 'navigate' => true,
            ];
            $this->addError('inp_login', 'Make sure your email are correct');
            $this->addError('inp_password', 'Make sure your password are correct');
            $this->dispatchProses('form_process', $dataDispatch);
            // $this->dispatchNotification('danger', $statusAuthAttempt->title, $statusAuthAttempt->message);
            
            return;
        }
        
        
        
        $dataDispatch = [
            'notification' => [
                'variant' => 'success',
                'sender' => 'System',
                'title' => $statusAuthAttempt->title,
                'message' => $statusAuthAttempt->message,
            ],
            'redirect' => route('app.dashboard.home'),
            'navigate' => false,
        ];
        $this->dispatchProses('form_process', $dataDispatch);
        // $this->dispatchNotification('success', $statusAuthAttempt->title, $statusAuthAttempt->message);
        
        // $this->authUserLogin();
        
        // $field = $this->getFieldType($this->inp_login);
        
        // if (Auth::attempt([$field => $this->inp_login, 'password' => $this->inp_password])) {
        //     session()->regenerate();
            
        //     $this->dispatchNotification('success', 'Welcome back!', 'Authentication successful.');
            
            
        //     return;
        // }
        
        // $this->dispatchNotification('danger', 'Oops!', 'Invalid credentials. Please check your login and try again.');
    }
    
    #[Attributes\Layout('livewire.layout.auth.template')]
    public function render()
    {
        return view('livewire.auth.login.main');
    }
    
    
    // Private Function
    private function authUserLogin() {
        $field = $this->getFieldType($this->inp_login);
        
        $credentialsAuth = [
            $field => $this->inp_login,
            'password' => $this->inp_password,
        ];
        
        
    }
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
    
    private function dispatchProses($dispatchName, $data) {
        $this->dispatch($dispatchName, $data);
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
