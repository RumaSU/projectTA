<?php

namespace App\Livewire\Auth\Register\Form;

use App\Library\SessionHelper;

use Illuminate\Validation\Rule;

use Livewire\Attributes;
use Livewire\Component;

class BasicInformation extends Component
{
    // Input Form
    public $inp_fullname;
    public $inp_birthday;
    public $inp_gender;
    
    // Additional Variable Components
    public $stepRegister;
    public $requestRouteName;
    public $listInputForm;
    public $sessionKey = 'register_step';
    public $sessionKeyStep;
    
    // additional private Variable
    private $listInputSelect = ['inp_gender'];
    private $validSelectGender = ['male', 'female', 'not_say'];
    
    public function mount() {
        $this->requestRouteName = request()->route()->getName();
        $requestExplode = explode('.', $this->requestRouteName);
        
        $this->stepRegister = end($requestExplode);
        $this->listInputForm = [
            'inp_fullname' => 'fullname',
            'inp_birthday' => 'birthday',
            'inp_gender' => 'gender',
        ];
        $this->sessionKeyStep = 'step_' . $this->stepRegister;
        
        if (! SessionHelper::hasSession('register_step')) {
            SessionHelper::putSession('register_step_initialized', true);
        }
        
        $this->initMountSession();
        
    }
    
    public function popstateInit() {
        $this->initMountSession();
    }
    
    public function submit_step() {
        $this->trimInputForm();
        $this->validate([
            'inp_fullname' => 'required|string|min:1|max:255',
            'inp_birthday' => 'required',
            'inp_gender' => ['required', Rule::in($this->validSelectGender)],
        ], [
            'inp_fullname.required' => 'Please enter your :attribute.',
            'inp_fullname.min' => 'Your :attribute must contain at least 1 character.',
            'inp_fullname.max' => 'Your :attribute cannot be longer than 255 characters.',
            
            'inp_birthday.required' => 'Please enter your :attribute',
            
            'inp_gender.required' => 'Please select your :attribute.',
            'inp_gender.in' => 'Please make sure to select the :attribute that is registered.',
        ], [
            'inp_fullname' => 'Fullname',
            'inp_birthday' => 'Birthday',
            'inp_gender' => 'Gender',
        ]);
        
        
        
        $keyStep = 'step_' . $this->stepRegister;
        
        $valueStep = $this->setValueStepSession();
        
        SessionHelper::UpdateSession('register_step', $keyStep, $valueStep);
        
        $this->dispatch('customnotify', (object) [
            'variant' => 'info',
            'sender' => 'System',
            'title' => 'Basic Information filled',
            'message' => 'Next step to credential details',
        ]);
        
        $this->redirectRoute('auth.register.step.credentials', navigate: true);
    }
    
    #[Attributes\Layout('livewire.layout.auth.template')]
    public function render()
    {
        return view('livewire.auth.register.form.basic-information');
    }
    
    
    
    // Private function
    private function initMountSession() {
        $getSession = SessionHelper::getSession($this->sessionKey);
        
        if (! $getSession) {
            return;
        }
        
        if ($getSession && array_key_exists($this->sessionKeyStep, $getSession)) {
            foreach($this->listInputForm as $key => $val) {
                $valueSession = $getSession[$this->sessionKeyStep][$val];
                
                if (gettype($valueSession) == 'array') {
                    $this->{$key} = $valueSession['value'];
                }
                if (gettype($valueSession) == 'string') {
                    $this->{$key} = $valueSession;
                }
                
                
            }
        }
    }
    
    private function setValueStepSession() { 
        $valueStep = [
            'route_name' => $this->requestRouteName,
        ];
        
        foreach($this->listInputForm as $key => $val) { 
            
            $tempValue = $this->{$key};
            
            if (in_array($key, $this->listInputSelect)) {
                $tempValue = $this->setValueSelectStepSession($key, $val,$tempValue);
            }
            
            $valueStep[$val] = $tempValue;
        }
        
        
        return $valueStep;
    }
    
    private function setValueSelectStepSession($keyWire, $valWire, $val) {
        $listInput = json_decode(json_encode( config("custom_register_form.steps.$this->stepRegister", []) ));
        
        // $optionSelect = array_filter($listOptionSelect, function ($var) use ($valWire) { return ($var['name'] == $valWire); } );
        $idxSelectInput = array_search( $valWire, array_column($listInput, 'name') );
        $idxOption = array_search( $val, array_column($listInput[$idxSelectInput]->input->i_select_registered, 'value') );
        
        $option = $listInput[$idxSelectInput]->input->i_select_registered[$idxOption];
        // $idxOption = array_search($val, array_column($listOptionSelect->input->i_select_registered, 'value'));
        
        return [
            'text' => $option->text,
            'value' => $option->value,
        ];
    }
    
    private function trimInputForm() {
        foreach($this->listInputForm as $key => $val) {
            $this->{$key} = trim($this->{$key});
        }
    }
    
}
