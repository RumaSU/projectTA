<?php

namespace App\Livewire\Auth\Register\Form;

use App\Library\SessionHelper;
use App\Library\UserHelper;
use App\Library\Helper as LibHelper;

use App\Models\Users;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

use Livewire\Attributes;
use Livewire\Component;
use Nette\Utils\Json;

class Credentials extends Component
{
    // Input Form
    public $inp_email;
    public $inp_password;
    public $inp_password_confirmation;
    public $sessionKey = 'register_step';
    public $sessionKeyStep;
    
    // Additional Variable Components
    public $stepRegister;
    public $requestRouteName;
    public $listInputForm;
    public $listStepKeyRegistered;
    public $firstCheckStep;
    
    // Additional Private Variable
    private $listInputSelect = [];
    
    
    public function mount() {
        $this->requestRouteName = request()->route()->getName();
        $requestExplode = explode('.', $this->requestRouteName);
        
        $this->stepRegister = end($requestExplode);
        $this->listInputForm = [
            'inp_email' => 'email',
            'inp_password' => 'password',
            'inp_password_confirmation' => 'confirm_password',
        ];
        $this->sessionKeyStep = 'step_' . $this->stepRegister;
        
        $this->listStepKeyRegistered = [
            'id' => 'auth.register.step.basic_info', 
            'step_basic_info' => 'auth.register.step.basic_info',
        ]; // step_credentials not used because it directly uses the components variable
        
        
        $this->initMountSession();
        $statusCheckStep = $this->checkAllStepRegister();
        if (! $statusCheckStep->status) {
            $dataDispatch = [
                'notification' => [
                    'variant' => 'warning',
                    'sender' => 'System',
                    'title' => 'Incomplete Registration Step',
                    'message' => $statusCheckStep->message,
                ],
                'redirect' => route($statusCheckStep->route),
                'navigate' => true,
            ];
            
            $this->firstCheckStep = json_encode($dataDispatch);
            
            
            // $this->dispatchProses('form_process', $dataDispatch);
            
            return;
        }
        
    }
    
    public function submit_step() {
        $statusCheckValidateSubmit = $this->checkAllStepRegister();
        if (! $statusCheckValidateSubmit->status) {
            $dataDispatch = [
                'notification' => [
                    'variant' => 'warning',
                    'sender' => 'System',
                    'title' => 'Incomplete Registration Step',
                    'message' => $statusCheckValidateSubmit->message,
                ],
                'redirect' => route($statusCheckValidateSubmit->route),
                'navigate' => true,
            ];
            
            $this->firstCheckStep = json_encode($dataDispatch);
            return;
        }
        
        $this->trimInputForm();
        $this->validate([
            'inp_email' => 'required|email|max:255|unique:users,email',
            'inp_password' => 'required|string|min:8',
            'inp_password_confirmation' => 'required|string|min:8|same:inp_password',
        ], [
            'inp_email.required' => 'Please enter your email address.',
            'inp_email.email' => 'Please enter a valid email address.',
            'inp_email.max' => 'Email must not exceed 255 characters.',

            'inp_password.required' => 'Please enter a password.',
            'inp_password.min' => 'Password must be at least 8 characters.',

            'inp_password_confirmation.required' => 'Please confirm your password.',
            'inp_password_confirmation.min' => 'Confirmation must be at least 8 characters.',
            'inp_password_confirmation.same' => 'Password and confirmation must match.',
        ], [
            'inp_email' => 'Email',
            'inp_password' => 'Password',
            'inp_password_confirmation' => 'Confirmation Password',
        ]);
        
        $statusCheckStep = $this->checkAllStepRegister();
        if (! $statusCheckStep->status) {
            $dataDispatch = [
                'notification' => [
                    'variant' => 'warning',
                    'sender' => 'System',
                    'title' => 'Incomplete Registration Step',
                    'message' => $statusCheckStep->message,
                ],
                'redirect' => route($statusCheckStep->route),
                'navigate' => true,
            ];
            
            $this->dispatchProses('form_process', $dataDispatch);
            
            return;
        }
        
        $this->dispatchNotification('info', 'All Steps Completed', $statusCheckStep->message);
        
        $statusStore = $this->storeDataUser();
        if (! $statusStore->status) {
            if ($statusStore->route) {
                $dataDispatch = [
                    'notification' => [
                        'variant' => 'warning',
                        'sender' => 'System',
                        'title' => 'Something error when save data',
                        'message' => $statusStore->message,
                    ],
                    'redirect' => route($statusStore->route),
                    'navigate' => true,
                ];
                
                $this->dispatchProses('form_process', $dataDispatch);
            }
            
            return;
        }
        
        $dataDispatch = [
            'notification' => [
                'variant' => 'success',
                'sender' => 'System',
                'title' => 'Account Created Successfully',
                'message' => $statusStore->message,
            ],
            'redirect' => null,
            // 'redirect' => route('dashboard.home'),
            'navigate' => false,
        ];
        $this->dispatchProses('form_process', $dataDispatch);
        
        
        $tryFirstAuth = $this->authRegister();
        SessionHelper::forgetRelatedSession($this->sessionKey);
        
        $this->dispatchProses('form_process', $tryFirstAuth);
    }
    
    #[Attributes\Layout('livewire.layout.auth.template')]
    public function render()
    {
        return view('livewire.auth.register.form.credentials');
    }
    
    
    
    
    // Private function
    private function storeDataUser() {
        $sessionData = SessionHelper::getSession($this->sessionKey);
        $jsonDESessionData = json_decode(json_encode($sessionData));
        
        $dataStep_basicInfo = $jsonDESessionData->step_basic_info;
                
        $uuidUser = $jsonDESessionData->id;
        $checkUuid = LibHelper::checkDuplicateValue($uuidUser, 'id_user', Users\User::class);
        
        if ($checkUuid) {
            $uuidUser = LibHelper::generateUniqueUuId(column: 'id_user', model: Users\User::class);
        }
        
        $usernameUserLetter = UserHelper::createUsername($jsonDESessionData->step_basic_info->fullname, true);
        
        // Store data
        try {
            // Check birthdate format
            if (! Carbon::hasFormat($dataStep_basicInfo->birthday, 'j F, Y')) {
                $exampleFormat = Carbon::now()->format('j F, Y');
                $throwError = (object) [
                    'message' => "The birthdate format is invalid. Please make sure it follows this format: $exampleFormat.",
                    'route' => $dataStep_basicInfo->route_name,
                ];
                throw new \Exception(
                    json_encode($throwError)
                );
            }
            $birthdate = Carbon::createFromFormat('j F, Y', $dataStep_basicInfo->birthday)->format('Y-m-d');
            
            // store user
            $storeUser = Users\User::create([
                'id_user' => $uuidUser,
                'email' => $this->inp_email,
                'username' => $usernameUserLetter,
                'password' => Hash::make($this->inp_password), 
            ]);
            
            
            
            if (! $storeUser) {
                $throwError = (object) [
                    'message' => 'An error occurred while creating your account. Please try again.',
                    'route' => null,
                ];
                throw new \Exception(
                    json_encode($throwError)
                );
            }
            
            // store user personal / basic info
            $storeUserPersonal = Users\UserPersonal::create([
                'id_user' => $uuidUser,
                'fullname' => $dataStep_basicInfo->fullname,
                'gender' => $dataStep_basicInfo->gender->text,
                'birthdate' => $birthdate,
            ]);
            
            if (! $storeUserPersonal) {
                $throwError = (object) [
                    'message' => 'We couldn’t save your personal information. Please return to the "Basic Info" step and try again.',
                    'route' => 'auth.register.step.basic_info',
                ];
                throw new \Exception(
                    json_encode($throwError)
                );
            }
            
            return (object) [
                'status' => true,
                'message' => 'Thanks for registering. Your account is ready and we’re setting things up for you...',
                'route' => null,
            ];
            
        } catch(\Exception $e) {
            $messageException = json_decode($e->getMessage());
            $this->rollbackUser($uuidUser);
            return (object) [
                'status' => false,
                'message' => $messageException->message ?? 'Something error',
                'route' => $messageException->route ?? null,
            ];
        }
    }
    
    private function rollbackUser($uuidUser) {
        Users\User::where('id_user', '=', $uuidUser)->delete();
    }
    
    private function authRegister() {
        $credentialsAuth = [
            'email' => $this->inp_email,
            'password' => $this->inp_password,
        ];
        
        $statusAuthAttempt = UserHelper::authUser($credentialsAuth);
        
        if (! $statusAuthAttempt->status) {
            $dataDispatch = [
                'notification' => [
                    'variant' => 'warning',
                    'sender' => 'System',
                    'title' => 'We Couldn’t Log You In',
                    'message' => 'Your account was created successfully, but we couldn’t log you in automatically. Please log in manually to continue.',
                ],
                'redirect' => route('auth.login'),
                'navigate' => true,
            ];
            return $dataDispatch;
        }
        
        return [
            'notification' => [
                'variant' => 'success',
                'sender' => 'System',
                'title' => 'Welcome Aboard!',
                'message' => 'Your account is all set! We’ve logged you in and are taking you to your dashboard.',
            ],
            'redirect' => route('app.dashboard.home'),
            'navigate' => false,
        ];
    }
    
    private function checkAllStepRegister() {
        if (! SessionHelper::hasSession($this->sessionKey)) {
            return (object) [
                'status' => false,
                'message' => 'We couldn’t find your registration progress. Please start from the beginning.',
                'route' => 'auth.register',
            ];
        }
        
        
        $sessionData = SessionHelper::getSession($this->sessionKey);
        
        foreach($this->listStepKeyRegistered as $stepKey => $routeKey) {
            
            if (! array_key_exists($stepKey, $sessionData)) {
                $stepMessage = str_replace("_", " ", str_replace("step_", "", $stepKey));
                
                return (object) [
                    'status' => false,
                    'message' => 'You must complete the "' . ucwords( $stepMessage ) . '" step.', // fail message
                    'route' => $routeKey,
                ];
            }
            
        }
        
        return (object) [
            'status' => true,
            'message' => 'Great job! You’ve completed all the steps. We’re now saving your credentials. Hang tight...', // success message
            'route' => null,
        ];
    }
    
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
    
    private function lowercaseValueInput($nameInput) {
        return strtolower($this->{$nameInput});
    }
    
    private function dispatchProses($dispatchName, $data) {
        $this->dispatch($dispatchName, $data);
    }
    
    private function dispatchNotification($variant = 'info', $title, $message) {
        $acceptVariant = ['info', 'success', 'warning', 'danger'];
        if (! in_array($variant, $acceptVariant)) $variant = 'info';
        $this->dispatch('customnotify', (object) [
            'variant' => $variant,
            'sender' => 'System',
            'title' => $title,
            'message' => $message,
        ]);
    }
    
}
