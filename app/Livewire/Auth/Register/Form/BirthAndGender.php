<?php

namespace App\Livewire\Auth\Register\Form;

use App\Library\SessionHelper;

use Livewire\Attributes;
use Livewire\Component;

class BirthAndGender extends Component
{
    // Input Form
    public $inp_fullname;
    
    // Additional Variable Components
    public $stepRegister;
    public $requestRouteName;
    public $listInputForm;
    
    public function mount() {
        $this->requestRouteName = request()->route()->getName();
        $requestExplode = explode('.', $this->requestRouteName);
        
        $this->stepRegister = end($requestExplode);
        $this->listInputForm = [
            'inp_fullname' => 'fullname',
        ];
        
        $this->initMountSession();
        dump($this->stepRegister);
    }
    
    public function submit_step() {
        $this->trimInputForm();
        $this->validate([
            'inp_fullname' => 'required|string|min:1|max:255',
        ], [
            'inp_fullname.required' => 'Please enter your full name.',
            'inp_fullname.min' => 'Your full name must contain at least 1 character.',
            'inp_fullname.max' => 'Your full name cannot be longer than 255 characters.',
        ], [
            'inp_fullname' => 'full name',
        ]);
        
        $keyStep = 'step_' . $this->stepRegister;
        
        $valueStep = [
            'route_name' => $this->requestRouteName,
            'fullname' => $this->inp_fullname,
        ];
        
        SessionHelper::UpdateSession('register_step', $keyStep, $valueStep);
        
        $this->dispatch('customnotify', (object) [
            'variant' => 'info',
            'sender' => 'System',
            'title' => 'Fullname filled',
            'message' => 'Next step to basic information',
        ]);
        
        $this->redirectRoute('auth.register.step.birth_gender', navigate: true);
    }
    
    #[Attributes\Layout('livewire.layout.auth.template')]
    public function render()
    {
        return view('livewire.auth.register.form.birth-and-gender');
    }
    
    
    
    // Private function
    private function initMountSession() {
        $sessionKey = 'register_step';
        $keyStep = 'step_' . $this->stepRegister;
        
        $getSession = SessionHelper::getSession($sessionKey);
        
        if (! $getSession) {
            return;
        }
        
        if ($getSession && array_key_exists($keyStep, $getSession)) {
            foreach($this->listInputForm as $key => $val) {
                $this->{$key} = $getSession[$keyStep][$val] ?? null;
            }
        }
    }
    
    private function trimInputForm() {
        foreach($this->listInputForm as $key => $val) {
            $this->{$key} = trim($this->{$key});
        }
    }
    
}
