@push('additional-title')
    | Register
@endpush

<div class="ctr-mainFormRegister-{{ $stepRegister }}">
    <div class="cMainFormRegister-{{ $stepRegister }}">        
        
        <form
            x-data="formAuthLogin_{{ $stepRegister }}"
            wire:submit.prevent='submit_step' 
            data-form-name="Register-Step-{{ ucfirst($stepRegister) }}"
            @form_process.window="handleNextProcess($event)"
        >
            
            <div class="mainFormRegister-input">
                @php
                    $listFormConfig = json_decode(json_encode( config("custom_register_form.steps.$this->stepRegister", []) ));
                    
                    if (session()->has($sessionKey)) {
                        $sessionValue = session()->get($sessionKey);
                        if (array_key_exists($sessionKeyStep, $sessionValue)) {
                            $sessionValue = session()->get($sessionKey)[$sessionKeyStep];
                        }
                    }
                @endphp
                
                
                @foreach ($listFormConfig as $itmForm)
                    
                    @if ($itmForm->type_input == 'default')
                        @include('livewire.auth.partial.default-input-form', ['itmForm' => $itmForm])
                    @endif
                    @if ($itmForm->type_input == 'select')
                        @include('livewire.auth.partial.default-select-form', [ 'itmForm' => $itmForm, 'defaultSelect' => $sessionValue[$itmForm->name]['text'] ?? 'Select Value' ])
                    @endif
                    
                @endforeach
            </div>
            
            <div class="act-submitFormRegister-{{ $stepRegister }} mt-12">
                <div class="cAct-submitFormRegister-{{ $stepRegister }}">
                    <button 
                        type="submit"
                        class="btnActLogin px-2 py-1 rounded-full w-full relative bg-[#1565C0]"
                        wire:loading.class.remove='bg-[#1565C0]'
                        wire:loading.class.add='bg-[#1565C0]/80'
                        wire:loading.attr='disabled'
                    >
                        <div class="cBtnActLogin h-12 flex items-center justify-center">
                            
                            
                            <div class="txActBtn flex items-center justify-center visible opacity-100" wire:loading.class.remove='visible opacity-100' wire:loading.class.add='invisible opacity-0'>
                                <div class="tx text-white text-sm">
                                    <p class="inline-flex">
                                        Confirm
                                    </p>
                                </div>
                            </div>
                            
                            <div class="wrapper-incLoading absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 invisible opacity-0"  wire:loading.class.remove='invisible opacity-0' wire:loading.class.add='visible opacity-100'>
                                <div class="icnLoading size-8 flex items-center justify-center animate-spin">
                                    <div class="icn text-xl text-white/70">
                                        <i class="fas fa-circle-notch"></i>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </button>
                </div>
            </div>
            
        </form>
        
        @if($firstCheckStep)
            {{-- @dump($firstCheckStep) --}}
            {{-- <div x-data="dataDispatch: {{ $firstCheckStep }}" x-init="$dispatch('register_process', {{ $firstCheckStep }});"></div> --}}
            {{-- <div x-data="{ firstCheckStep: {{ $firstCheckStep }}, }" x-init="$dispatch('register_process', firstCheckStep); console.log(firstCheckStep);"></div> --}}
            
        @endif
        
    </div>
</div>

@once
    @push('auth-body-script')
        {{-- <script data-navigate-once>
            Alpine.data('TestCheckStep', () => {
                return {
                    init() {
                        $valueFirstCheckStep = {{ $firstCheckStep }}
                        console.log('Tes TestCheckStep')
                    }
                }
            })
        </script> --}}
        <script data-navigate-once>
            Alpine.data('formAuthLogin_{{ $stepRegister }}', () => {
                
                return {
                    isShowPassword: false,
                    
                    init() {
                        const firstCheck = '{!! $firstCheckStep !!}' ?? null;
                        if (firstCheck) {
                            const firstCheckParse = JSON.parse(firstCheck);
                            setTimeout(() => {
                                this.$dispatch('register_process', firstCheckParse);
                                $jq(this.$root).addClass('animate-pulse');
                                $jq(this.$root).find('input').prop('disabled', true);
                                $jq(this.$root).find('button').prop('disabled', true);
                            }, 150);
                        }
                        
                        this.initInputDate();
                    },
                    
                    initInputDate() {
                        this.setupInitVanillaCalendar();
                        
                        // document.addEventListener('livewire:load', () => {
                        //     this.setupInitVanillaCalendar();
                        // });

                        // Livewire.hook('message.processed', () => {
                        //     this.setupInitVanillaCalendar();
                        // });
                    },
                    
                    handleNextProcess($e) {
                        console.log('tes');
                        let valueEventParam = JSON.parse(JSON.stringify($e.detail));
                        if ( Array.isArray(valueEventParam) ) {
                            valueEventParam = valueEventParam[0];
                        }
                        
                        console.log(' ');
                        console.log('handle error process', $e);
                        console.log(' ');
                        console.log('original value handle error process: ', JSON.parse(JSON.stringify($e)));
                        console.log('detail value handle error process: ', JSON.parse(JSON.stringify(valueEventParam)));
                        console.log(' ');
                        console.log('value event param: ', valueEventParam);
                        console.log('');
                        
                        this.$dispatch('customnotify', valueEventParam.notification);
                        
                        if (valueEventParam.redirect) {
                            console.log(valueEventParam.navigate)
                            setTimeout(() => {
                                console.log('===============================');
                                console.log('Window location: ', window.location);
                                if (valueEventParam.navigate) {
                                    Livewire.navigate(valueEventParam.redirect);
                                } else {
                                    window.location.href = valueEventParam.redirect;
                                }
                            }, 500);
                        }
                    },
                    
                    handleViewPassword(e) {
                        const elmntCurr = e.currentTarget;
                        
                        const jqElemntCur = $jq(elmntCurr);
                        const jqIconAct = jqElemntCur.find('.icn');
                        
                        const idInpForm = jqElemntCur.data('idInputForm');
                        
                        const jqItmForm = jqElemntCur.closest('.itm-inpForm');
                        const jqInpForm = jqItmForm.find('input#' + idInpForm);
                        
                        this.isShowPassword = !this.isShowPassword;
                        
                        if (this.isShowPassword) {
                            jqInpForm.attr('type', 'text');
                            
                            jqIconAct.find('svg').remove();
                            jqIconAct.append(svgEye);
                            
                            
                        } else {
                            jqInpForm.attr('type', 'password');
                            
                            jqIconAct.find('svg').remove();
                            jqIconAct.append(svgEyeSlash);
                            
                        }
                    },
                    
                    handleInput($e) {
                        const $eCurTarget = $e.currentTarget;
                        
                        const $jqCurTarget = $jq($eCurTarget);
                        const $jqParElemnt = $jqCurTarget.closest('.itm-inpForm');
                        
                        if (! $jqParElemnt[0].hasAttribute('data-input-error')) {
                            return;
                        }
                        
                        const $jqInpSectionElement = $jqParElemnt.find('.ctr-inpSecForm');
                        const $jqInpSectionIconElement = $jqInpSectionElement.find('.icnInpForm');
                        
                        const $jqInpErrorSection = $jqParElemnt.find('.wrapper-additional');
                        
                        $jqParElemnt.removeAttr('data-input-error');
                        // 'outline outline-[2.5px] outline-red-600 bg-red-50' : 'focus-within:outline-blue-600 focus-within:bg-white'
                        $jqInpSectionElement.removeClass('outline outline-[2.5px] outline-red-600 bg-red-50').addClass('focus-within:outline-blue-600 focus-within:bg-white');
                        
                        // 'text-red-600' : 'text-black/70'
                        $jqInpSectionIconElement.find('.icn').removeClass('text-red-600').addClass('text-black/70');
                        
                        // 'text-red-400' : 'text-gray-400'
                        $jqCurTarget.removeClass('placeholder:text-red-400').addClass('placeholder:text-gray-400');
                        $jqInpErrorSection.empty();
                        
                    },
                    
                    setupInitVanillaCalendar() {
                        
                        console.log(' ');
                        console.log('Vanilla Calendar Init');
                        console.log(' ');
                        
                        const $findInputDate = $jq(this.$root).find('input[data-type-as="date"]');
                        const $todayDate = new Date();
                        
                        if (! $findInputDate.length) {
                            return
                        }
                        
                        $findInputDate.each(($idx, $val) => {
                            const $jqOriInputDate = $jq($val);
                            const $idInputDate = $jqOriInputDate.attr('id');
                            
                            // set input type from date to text for trigger vanilla calendar
                            $jqOriInputDate.attr('type', 'text');
                            
                            const calendarInput = new Calendar(`#${$idInputDate}`, {
                                inputMode: true,
                                dateMax: $todayDate,
                                dateToday: $todayDate,
                                // positionToInput: 'left',
                                selectedTheme: 'light',
                                selectedWeekends: [-1],
                                onChangeToInput(self) {
                                    const input = self.context.inputElement;
                                    if (!input) return;
                                    
                                    input.value = self.context.selectedDates[0] || '';
                                    
                                    if (! input.value) return;
                                    
                                    const rawDate = new Date(input.value);
                                    const day = rawDate.getDate(); const month = rawDate.toLocaleString('default', { month: 'long' }); const year = rawDate.getFullYear();
                                    input.value = `${day} ${month}, ${year}`;
                                    
                                    input.dispatchEvent(new Event('input', { bubbles: true }));
                                    input.dispatchEvent(new Event('change', { bubbles: true }));
                                },
                            });
                            
                            calendarInput.init();
                        });
                    },
                    
                    // handleNotifDispatch($e) {
                    //     console.log('handle dispatch window credential page :', JSON.parse(JSON.stringify($e)));
                    //     console.log('handle dispatch window credential page :', JSON.parse(JSON.stringify($e.detail)));
                    //     console.log('handle dispatch window credential page :', JSON.parse(JSON.stringify($e.detail[0])));
                        
                    //     this.$dispatch('customnotify', JSON.parse(JSON.stringify($e.detail[0])) );
                    // }
                    
                }
                
            });
        </script>
    @endpush
@endonce