<?php

namespace App\Livewire\Auth\Register\Form;

use App\Library\SessionHelper;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Livewire\Attributes;
use Livewire\Component;

use Carbon\Carbon;

class BirthAndGender extends Component
{
    // Input Form
    public $inp_birthday;
    public $inp_gender;
    
    // Additional Variable Components
    public $stepRegister;
    public $requestRouteName;
    public $listInputForm;
    
    // additional private Variable
    private $validSelectGender = ['male', 'female', 'not_say'];
    
    public function mount() {
        $this->requestRouteName = request()->route()->getName();
        $requestExplode = explode('.', $this->requestRouteName);
        
        $this->stepRegister = end($requestExplode);
        $this->listInputForm = [
            'inp_birthday' => 'birthday',
            'inp_gender' => 'gender',
        ];
        
        $this->initMountSession();
    }
    
    public function submit_step() {
        $this->trimInputForm();
        
        $this->validate([
            'inp_birthday' => 'required',
            'inp_gender' => ['required', Rule::in($this->validSelectGender)],
        ], [
            'inp_birthday.required' => 'Please enter your :attribute',
            'inp_gender.required' => 'Please select your :attribute.',
            'inp_gender.in' => 'Please make sure to select the :attribute that is registered.',
        ], [
            'inp_birthday' => 'Birthday',
            'inp_gender' => 'Gender',
        ]);
        
        foreach($this->listInputForm as $key => $val) {
            dump((object)[
                $key => $this->{$key},
            ]);
        }
        
        // $keyStep = 'step_' . $this->stepRegister;
        
        // $valueStep = [
        //     'route_name' => $this->requestRouteName,
        //     'fullname' => $this->inp_fullname,
        // ];
        
        // SessionHelper::UpdateSession('register_step', $keyStep, $valueStep);
        
        // $this->dispatch('customnotify', (object) [
        //     'variant' => 'info',
        //     'sender' => 'System',
        //     'title' => 'Fullname filled',
        //     'message' => 'Next step to basic information',
        // ]);
        
        // $this->redirectRoute('auth.register.step.birth_gender', navigate: true);
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
