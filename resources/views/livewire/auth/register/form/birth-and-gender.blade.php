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
                
                    <div 
                        class="itm-inpFormLogin mt-1" 
                        aria-labelledby="{{ $itmForm->label->l_ariaLabelledBy }}"
                        {{ $errors->first($itmForm->error->key) ? 'data-input-error' : '' }}
                        
                        >
                        <div class="cItm-inpFormLogin" data-name-form="{{ $itmForm->name }}">
                            
                            <div class="ctr-mainInpFormLogin">
                                <div class="cMainInpFormLogin">
                                    
                                    
                                    <label 
                                        for="{{ $itmForm->input->i_id }}"
                                        class="lbl-txInpFormLogin flex"
                                        
                                        >
                                        <div class="txLblInpForm ml-4">
                                            <div class="txLbl text-sm text-gray-800">
                                                <p>{{ $itmForm->label->l_text }}</p>
                                            </div>
                                        </div>
                                        
                                        @if ($itmForm->input->i_required)
                                            <div class="statusInpRequired">
                                                <div class="icnRequire">
                                                    <div class="icn text-red-600 text-[0.5rem]">
                                                        <i class="fas fa-asterisk"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        
                                    </label>
                                    
                                    <div 
                                        class="ctr-inpSecFormLogin mt-0.5 border border-gray-400 rounded-full overflow-hidden focus-within:border-transparent focus-within:outline focus-within:outline-[2.5px] {{ $errors->has($itmForm->error->key) ? 'outline outline-[2.5px] outline-red-600 bg-red-50' : 'focus-within:outline-blue-600 focus-within:bg-white' }}"
                                        data-input-error="{{ $errors->has($itmForm->error->key) ? 'true' : 'false' }}"
                                        data-error-message="{{ $errors->first($itmForm->error->key) }}"
                                    >
                                        <div class="cInpSecFormLogin flex items-center">
                                            
                                            <label class="ctr-labelMainInpSecFormLogin flex-grow cursor-text pl-2">
                                                <div class="cLabelMainInpSecFormLogin flex items-center">
                                                    <div class="icnInpFormLogin">
                                                        <div class="icnInp flex items-center justify-center size-8">
                                                            <div class="icn text-lg {{ $errors->has($itmForm->error->key) ? 'text-red-600' : 'text-black/70' }}">
                                                                <i class="{{ $itmForm->icon }}"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div 
                                                        class="setInpFormLogin flex-grow py-1 {{ !(property_exists($itmForm->input, 'i_addiSingleAct')) ? 'mr-5' : '' }}"
                                                    >
                                                        <div class="mainSetInp">
                                                            <input 
                                                                type="{{ $itmForm->input->i_type }}" 
                                                                name="{{ $itmForm->label->l_ariaLabelledBy }}" 
                                                                id="{{ $itmForm->input->i_id }}" 
                                                                placeholder="{{ $itmForm->input->i_placeholder }}"
                                                                {{ $itmForm->input->i_required ? 'required' : '' }}
                                                                wire:model.defer='{{ $itmForm->wire_model->var_model }}'
                                                                class="text-sm w-full p-2.5 border-0 outline-none bg-transparent placeholder:text-xs placeholder:{{ $errors->first($itmForm->error->key) ? 'text-red-400' : 'text-gray-400' }}"
                                                                @input='handleInput'
                                                                autocomplete="off"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                            
                                            @if (property_exists($itmForm->input, 'i_addiSingleAct'))
                                                <div 
                                                    class="additionalSingleActInpFormLogin shrink-0"
                                                >
                                                    <div class="cAddiSingleActInpFormLogin">
                                                        <button 
                                                            type="button"
                                                            data-id-input-form="{{ $itmForm->input->i_id }}"
                                                            class="actButtonAddiInpFormLogin pr-2"
                                                            x-on:{{ $itmForm->input->i_alpine->alp_listen }}="{{ $itmForm->input->i_alpine->alp_func }}"
                                                            >
                                                            <div class="btnActInpFormLogin">
                                                                <div class="icnActAddiFormLogin flex items-center justify-center size-8">
                                                                    <div class="icn text-black/70">
                                                                        <i class="{{ $itmForm->input->i_addiSingleAct->default_icon }}"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="wrapper-additional h-6 mt-0.5">
                                @error($itmForm->error->key)
                                    <div class="ctr-errorInpFormLogin ml-4">
                                        <div class="cErrorInpFormLogin text-red-600">
                                            <div class="txErrorInpFormLogin">
                                                <div class="txError text-sm">
                                                    <p>{{ $message }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @enderror
                            </div>
                            
                            
                        </div>
                    </div>
                    
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
                const svgEyeSlash = `<svg class="svg-inline--fa fa-eye-slash" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye-slash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"></path></svg>`
                const svgEye = `<svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"></path></svg>`;
                return {
                    isShowPassword: false,
                    form_inputs: [
                        {
                            name: 'email',
                            value: '',
                            errMessage: '',
                        },
                        {
                            name: 'password',
                            value: '',
                            errMessage: '',
                        },
                    ],
                    jsInp_email: '',
                    jsInp_password: '',
                    
                    init() {
                        console.log('Alpine form registered step-{{ $stepRegister }} initialized')
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
                        const $eTarget = $e.target;
                        const $eCurTarget = $e.currentTarget;
                        
                        const $jqCurTarget = $jq($eCurTarget);
                        const $jqParElemnt = $jqCurTarget.closest('.itm-inpFormLogin');
                        
                        if (! $jqParElemnt[0].hasAttribute('data-input-error')) {
                            return;
                        }
                        
                        const $jqInpSectionElement = $jqParElemnt.find('.ctr-inpSecFormLogin');
                        const $jqInpSectionIconElement = $jqInpSectionElement.find('.icnInpFormLogin');
                        
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