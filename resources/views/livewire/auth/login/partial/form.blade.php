<div class="mainFormLogin-input">
    @php
        $listForm = [
            (object) [
                'name' => 'email-or-id',
                'icon' => 'fas fa-envelope',
                'label' => (object) [
                    'l_ariaLabelledBy' => 'emailIdForm',
                    'l_text' => 'Email or ID',
                ],
                'input' => (object) [
                    'i_type' => 'email',
                    'i_id' => 'emailIdFormLogin',
                    'i_required' => true,
                    'i_placeholder' => 'Email address / ID',
                ],
            ],
            (object) [
                'name' => 'password-form',
                'icon' => 'fas fa-lock',
                'label' => (object) [
                    'l_ariaLabelledBy' => 'passwordForm',
                    'l_text' => 'Password',
                ],
                'input' => (object) [
                    'i_type' => 'password',
                    'i_id' => 'passwordFormLogin',
                    'i_required' => true,
                    'i_placeholder' => 'Password',
                    'i_addiSingleAct' => (object) [
                        'text' => 'Show Password',
                        'default_icon' => 'fas fa-eye-slash',
                        
                        'alpine' => (object) [
                            'alp_listen' => '',
                            'alp_func' => ''
                        ],
                    ],
                ],
            ],
        ];
    @endphp
    
    @foreach ($listForm as $itmForm)
        
        <div 
            class="itm-inpFormLogin my-2" 
            aria-labelledby="{{ $itmForm->label->l_ariaLabelledBy }}"
            
            >
            <div class="cItm-inpFormLogin" data-name-form="{{ $itmForm->name }}">
                <label 
                    for="{{ $itmForm->input->i_id }}"
                    class="lbl-txInpFormLogin flex"
                    
                    >
                    <div class="txLblInpForm">
                        <div class="txLbl text-sm">
                            <p>{{ $itmForm->label->l_text }}</p>
                        </div>
                    </div>
                    <div class="statusInpRequired">
                        <div class="icnRequire">
                            <div class="icn text-red-600 text-[0.5rem]">
                                <i class="fas fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                </label>
                
                <div 
                    class="ctr-inpSecFormLogin border border-black rounded-full overflow-hidden focus-within:outline focus-within:outline-[1.5px] focus-within:outline-blue-600"
                    >
                    <div class="cInpSecFormLogin flex items-center">
                        
                        <label class="ctr-mainInpSecFormLogin flex-grow cursor-text">
                            <div class="cLabelMainInpSecFormLogin flex items-center">
                                <div class="icnInpFormLogin">
                                    <div class="icnInp flex items-center justify-center size-8">
                                        <div class="icn text-lg">
                                            <i class="{{ $itmForm->icon }}"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="setInpFormLogin flex-grow {{ !(property_exists($itmForm->input, 'i_addiSingleAct')) ? 'mr-5' : '' }}">
                                    <div class="mainSetInp">
                                        <input 
                                            type="{{ $itmForm->input->i_type }}" 
                                            name="{{ $itmForm->label->l_ariaLabelledBy }}" 
                                            id="{{ $itmForm->input->i_id }}" 
                                            placeholder="{{ $itmForm->input->i_placeholder }}"
                                            {{ $itmForm->input->i_required ? 'required' : '' }}
                                            {{-- class="text-sm w-full py-1 border-0 outline-none bg-transparent autofill:!bg-transparent autofill:!text-sm " --}}
                                            class="text-sm w-full p-2.5 border-0 outline-none bg-transparent bg-input-autofill"
                                            {{-- {{ $itmForm->input->i_type == 'password' ? 'autocomplete=new-password' : '' }} --}}
                                            {{-- aria-autocomplete="none" --}}
                                            autocomplete="new-password"
                                        >
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        @if (property_exists($itmForm->input, 'i_addiSingleAct'))
                            <div class="additionalSingleActInpFormLogin shrink-0">
                                <div class="cAddiSingleActInpFormLogin">
                                    <button 
                                        type="button"
                                        data-id-input-form="{{ $itmForm->input->i_id }}"
                                        >
                                        <div class="btnActInpFormLogin">
                                            <div class="icnActFormLogin flex items-center justify-center size-8">
                                                <div class="icn">
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
        
    @endforeach
</div>