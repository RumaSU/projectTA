<div 
    class="itm-inpForm" 
    aria-labelledby="{{ $itmForm->label->l_ariaLabelledBy }}"
    {{ $errors->first($itmForm->error->key) ? 'data-input-error' : '' }}
    {{-- x-data="{showSelectModal: false, textSelectModal: '{{ ${$itmForm->wire_model->var_model} ?  $itmForm->input->i_select_registered[array_search(${$itmForm->wire_model->var_model}, array_column($itmForm->input->i_select_registered, 'value'))]->text : ('Select ' . $defaultSelect ?? ' Value') }}', }" --}}
    x-data="{showSelectModal: false, textSelectModal: '{{ $defaultSelect ?? 'Select Value' }}', }"
>

    {{-- @php
        dump(array_column($itmForm->input->i_select_registered, 'value'));
        dump(array_search(${$itmForm->wire_model->var_model}, array_column($itmForm->input->i_select_registered, 'value')));
        dump( $itmForm->input->i_select_registered[array_search(${$itmForm->wire_model->var_model}, array_column($itmForm->input->i_select_registered, 'value'))]->text );
        dump($itmForm);
    @endphp --}}
    
    <div 
        class="cItm-inpForm" 
        data-name-form="{{ $itmForm->name }}"
        >
        
        <div class="wrapper-mainInpForm relative">
            <div class="ctr-mainInpForm">
                <div class="cMainInpForm">
                    
                    
                    <label 
                        for="{{ $itmForm->input->i_id }}"
                        class="lbl-txInpForm flex"
                        @click="showSelectModal = !showSelectModal"
                        
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
                        {{-- x-data="{ isOpenSelect: false, textSelected: gender }" --}}
                    >
                        <div class="cInpSecForm flex items-center">
                            
                            <label 
                                class="ctr-labelMainInpSecForm flex-grow cursor-text px-2"
                                @click="showSelectModal = !showSelectModal"
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
                                        class="setInpForm flex-grow py-1 {{ (property_exists($itmForm->input, 'i_addiSingleAct')) ? 'mr-5' : '' }}"
                                    >
                                        <div class="mainSetInp">
                                            <button 
                                                class="btnInpSelectForm w-full p-2.5 outline-none bg-transparent border-0" 
                                                type="button"
                                                data-id-select="{{ $itmForm->input->i_id }}"
                                            >
                                                <div class="cBtnInpSelectForm flex items-center justify-between">
                                                    <div class="txValueSelectForm">
                                                        <div class="txValue text-sm">
                                                            <p 
                                                                x-text="textSelectModal"
                                                                {{-- x-ref="selectText_{{ $itmForm->input->i_id }}" --}}
                                                            >Select Value</p>
                                                        </div>
                                                    </div>
                                                    <div class="icnBtnSelect">
                                                        <div class="icn text-xs">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </button>
                                            
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
            
            <div 
                class="wrapper-listOptionSelect w-full absolute top-full z-10"
                @click.away="showSelectModal = false"
                x-show="showSelectModal" 
                style="display: none"
                
                >
                <div class="ctr-listOptionSelect bg-white rounded-sm shadow-sm shadow-black/40 mt-1 py-2 overflow-auto">
                    <div class="cListOptionSelect">
                        @foreach ($itmForm->input->i_select_registered as $optionSelect)
                            <div 
                                class="ctr-itmOptionSelect group"
                                
                                >
                                <div class="cItmOptionSelect">
                                    <label for="{{ $optionSelect->id }}" class="lblOptionSelect block px-2 py-1.5 group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-slate-200">
                                        <div class="cLblOptionSelect flex items-center gap-2">
                                            <div class="icnOption rounded-full border-2 border-gray-400 bg-transparent group-has-[:checked]:bg-blue-100 group-has-[:checked]:border-blue-600">
                                                <div class="ballOption size-3 flex items-center justify-center">
                                                    <div class="ballOption size-1/2 rounded-full bg-blue-600 hidden group-has-[:checked]:block"></div>
                                                </div>
                                            </div>
                                            <div class="txOptionSelect">
                                                <div class="tx text-sm">
                                                    <p>{{ $optionSelect->text }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <input 
                                        id="{{ $optionSelect->id }}"
                                        type="radio" 
                                        name="{{ $itmForm->label->l_ariaLabelledBy }}" 
                                        class="sr-only"
                                        value="{{ $optionSelect->value }}"
                                        {{-- @change="$refs.selectText_{{ $itmForm->input->i_id }}.textContent = $event.target.dataset.textOption" --}}
                                        @change="textSelectModal = $event.target.dataset.textOption; showSelectModal = false; handleInput($event)"
                                        data-text-option="{{ $optionSelect->text }}"
                                        wire:model.defer='{{ $itmForm->wire_model->var_model }}'
                                        {{ $optionSelect->value == $itmForm->wire_model->var_model ? 'selected' : '' }}
                                        
                                        {{-- {{ $optionSelect->selected ? 'selected' : '' }} --}}
                                    >
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="wrapper-additional min-h-6 mt-0.5">
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