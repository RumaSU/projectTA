<?php

namespace App\Livewire\Layout\Auth\Partial;

use Illuminate\Support\Str;

use Livewire\Attributes;
use Livewire\Component;

class Header extends Component
{
    public $text_main_header;
    public $text_additional_header;
    
    private $routeRegisteredFull = [
        [
            'routeMain' => 'login',
            'value' => [
                ['name' => 'login', 'mainText' => 'Welcome back!', 'additionalText' => null],
            ]
        ],
        [
            'routeMain' => 'register',
            'value' => [
                ['name' => 'basic_info', 'mainText' => 'Create an account', 'additionalText' => 'Tell us a bit about yourself — your name, birthdate, and gender'],
                // ['name' => 'fullname', 'mainText' => 'Create an account', 'additionalText' => 'Enter your fullname'],
                // ['name' => 'birth_gender', 'mainText' => 'Basic information', 'additionalText' => 'Enter your birthday and gender'],
                // ['name' => 'email_password', 'mainText' => 'Set your login details', 'additionalText' => 'Enter your email and create a strong password. This will be used to access your account later'],
                ['name' => 'credentials', 'mainText' => 'Set your login details', 'additionalText' => 'This will be used to access your account later'],
                // ['name' => 'email', 'mainText' => 'How you’ll sign in', 'additionalText' => 'Enter yout email address for signing in to your Digital Signature Account'],
                // ['name' => 'password', 'mainText' => 'Create a strong password', 'additionalText' => 'Create a strong password with a mix of letters, numbers and symbols'],
            ]
        ],
    ];
    
    public function mount() {
        
        $requestGetName = request()->route()->getName();
        $requestExplode = explode('.', $requestGetName);
        
        $arrValueRouteMain = array_column($this->routeRegisteredFull, 'routeMain');
        $idxRouteRegistered = null;
        
        foreach($arrValueRouteMain as $idx => $val) {
            if (Str::contains($requestGetName, $val)) {
                $idxRouteRegistered = $idx;
                break;
            }
        }
        
        if (!is_null($idxRouteRegistered)) {
            $routeRegisByIdx = $this->routeRegisteredFull[$idxRouteRegistered]['value'];

            $stepRoute = end($requestExplode);
            $indexRouteRegis = array_search($stepRoute, array_column($routeRegisByIdx, 'name'));

            if ($indexRouteRegis !== false) {
                $routeRegisSelected = $routeRegisByIdx[$indexRouteRegis];
                
                $this->text_main_header = $routeRegisSelected['mainText'];
                $this->text_additional_header = $routeRegisSelected['additionalText'];
            }
        }
    }
    
    public function render()
    {
        return view('livewire.layout.auth.partial.header');
    }
}
