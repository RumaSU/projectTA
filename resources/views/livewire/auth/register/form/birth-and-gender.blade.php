@push('additional-title')
    | Register
@endpush

<div class="ctr-mainFormRegister-{{ $stepRegister }}">
    <div class="cMainFormRegister-{{ $stepRegister }}">
        
        <form
            x-data="formAuthLogin_{{ $stepRegister }}" 
            wire:submit.prevent='submit_step' 
            data-form-name="Register-Step-{{ ucfirst($stepRegister) }}"
        >
            <div class="mainFormRegister-input">
                @php
                    $listFormConfig = json_decode(json_encode( config("custom_register_form.steps.$this->stepRegister", []) ));
                    
                @endphp
                
                
                @foreach ($listFormConfig as $itmForm)
                    
                    @if ($itmForm->type_input == 'default')
                        @include('livewire.auth.partial.default-input-form', ['itmForm' => $itmForm])
                    @endif
                    @if ($itmForm->type_input == 'select')
                        @include('livewire.auth.partial.default-select-form', ['itmForm' => $itmForm, 'defaultSelect' => 'Gender'])
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
                                        Next
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
        
        
        
    </div>
</div>


@push('auth-bottom-main-content')
    <div class="act-notHaveAccount mt-6">
        <div class="cAct-notHaveAccount flex items-center justify-center gap-1">
            <div class="descNotHaveAccount">
                <div class="txDesc">
                    <div class="tx">
                        <p>Already have an account?</p>
                    </div>
                </div>
            </div>
            <div class="href-notHaveAccount">
                <a href="{{ route('auth.login') }}" class="cHref-notHaveAccount block" wire:navigate>
                    <div class="txNotHave">
                        <div class="tx text-blue-600">
                            <p>Sign in</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div> 
@endpush

@once
    @push('auth-body-script')
        <script data-navigate-once>
            Alpine.data('formAuthLogin_{{ $stepRegister }}', () => {
                
                return {
                    isShowPassword: false,
                    isShowpickerSelect: false,
                    
                    
                    init() {
                        
                        this.initInputDate();
                        
                    },
                    
                    initInputDate() {
                        const $findInputDate = $jq(this.$root).find('input[data-type-as="date"]');
                        const $todayDate = new Date();
                        
                        if (! $findInputDate.length) {
                            return
                        }
                        
                        $findInputDate.each(($idx, $val) => {
                            const $jqInputDate = $jq($val);
                            const $idInputDate = $jqInputDate.attr('id');
                            
                            // set input type from date to text for trigger vanilla calendar
                            $jqInputDate.attr('type', 'text');
                            
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
                    
                    handleViewPassword(e) {
                        const elmntCurr = e.currentTarget;
                        
                        const jqElemntCur = $jq(elmntCurr);
                        const jqIconAct = jqElemntCur.find('.icn');
                        
                        const idInpFormLogin = jqElemntCur.data('idInputForm');
                        
                        const jqItmFormLogin = jqElemntCur.closest('.itm-inpFormLogin');
                        const jqInpFormLogin = jqItmFormLogin.find('input#' + idInpFormLogin);
                        
                        this.isShowPassword = !this.isShowPassword;
                        
                        if (this.isShowPassword) {
                            jqInpFormLogin.attr('type', 'text');
                            
                            jqIconAct.find('svg').remove();
                            jqIconAct.append(svgEye);
                            
                            
                        } else {
                            jqInpFormLogin.attr('type', 'password');
                            
                            jqIconAct.find('svg').remove();
                            jqIconAct.append(svgEyeSlash);
                            
                        }
                    },
                    
                    handleInput($e) {
                        
                        console.log($e);
                        
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
                    
                }
                
            });
        </script>
    @endpush
@endonce