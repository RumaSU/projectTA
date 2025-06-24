<div 
    class="itm-inpForm mt-1" 
    aria-labelledby="{{ $itmForm->label->l_ariaLabelledBy }}"
    {{ $errors->first($itmForm->error?->key) ? 'data-input-error' : '' }}
    
    >
    <div class="cItm-inpForm" data-name-form="{{ $itmForm->name }}">
        
        <div class="ctr-mainInpForm">
            <div class="cMainInpForm">
                
                
                <label 
                    for="{{ $itmForm->input->i_id }}"
                    class="lbl-txInpForm flex"
                    
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
                    class="ctr-inpSecForm mt-0.5 border border-gray-400 rounded-full overflow-hidden focus-within:border-transparent focus-within:outline focus-within:outline-[2.5px] {{ $errors->has($itmForm->error->key) ? 'outline outline-[2.5px] outline-red-600 bg-red-50' : 'focus-within:outline-blue-600 focus-within:bg-white' }}"
                    data-input-error="{{ $errors->has($itmForm->error->key) ? 'true' : 'false' }}"
                    data-error-message="{{ $errors->first($itmForm->error->key) }}"
                    x-data
                >
                    <div class="cInpSecForm flex items-center">
                        
                        <label 
                            class="ctr-labelMainInpSecForm flex-grow cursor-text pl-2"
                        >
                            <div class="cLabelMainInpSecForm flex items-center">
                                
                                @if (property_exists($itmForm, 'icon'))
                                    <div class="icnInpForm">
                                        <div class="icnInp flex items-center justify-center size-8">
                                            <div class="icn text-lg {{ $errors->has($itmForm->error->key) ? 'text-red-600' : 'text-black/70' }}">
                                                <i class="{{ $itmForm->icon }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div 
                                    class="setInpForm flex-grow py-1 {{ !(property_exists($itmForm->input, 'i_addiSingleAct')) ? 'mr-5' : '' }}"
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
                                            data-type-as="{{ $itmForm->input->i_type_as }}"
                                        >
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        @if (property_exists($itmForm->input, 'i_addiSingleAct'))
                            <div 
                                class="additionalSingleActInpForm shrink-0"
                            >
                                <div class="cAddiSingleActInpForm">
                                    <button 
                                        type="button"
                                        data-id-input-form="{{ $itmForm->input->i_id }}"
                                        class="actButtonAddiInpForm pr-2"
                                        x-on:{{ $itmForm->input->i_alpine->alp_listen }}="{{ $itmForm->input->i_alpine->alp_func }}"
                                        >
                                        <div class="btnActInpForm">
                                            <div class="icnActAddiForm flex items-center justify-center size-8">
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
                <div class="ctr-errorInpForm ml-4">
                    <div class="cErrorInpForm text-red-600">
                        <div class="txErrorInpForm">
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