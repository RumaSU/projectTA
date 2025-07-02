<div class="ctr-navSignatureDashboard">
    <div class="cNavSignatureDashboard flex items-center justify-between">
        
        <div class="ctr-leftNavSignatureDashboard">
            <div class="cLeftNavSignatureDashboard -space-y-1">
                
                <div class="mainTitleNavSign">
                    <div class="txMain text-lg font-semibold">
                        <p>Signature Design</p>
                    </div>
                </div>
                
                <div class="descTitleNavDesign">
                    <div class="txDesc text-sm">
                        <p>Choose the signature design that will be used in your document</p>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="ctr-rightNavSignatureDashboard">
            <div class="cRightNavSignatureDashboard">
                <div class="wrapper-actAddNewSignatureDashboard relative" x-data="{ modal: false }">
                    <div class="act-addNewSignatureDashboard">
                        <button
                            type="button"
                            class="bg-gradient-to-r from-[#297DDE] to-[#004DA6] px-4 py-2 rounded-lg hover:contrast-200"
                            @click="modal = !modal"
                            @click.stop
                        >
                            <div class="cButtonAddNewSignatureDashobard flex items-center justify-between gap-6 text-white text-sm">
                                <div class="textButton">
                                    <div class="tx">
                                        <p>Create New</p>
                                    </div>
                                </div>
                                <div class="iconButton size-7 flex items-center justify-center">
                                    <div class="icon">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    
                    <div 
                        class="wrapper-listAddTypeSignature absolute top-full right-0 w-52"
                        {{-- @click.self="modal = false" --}}
                        @click.away="modal = false"
                        {{-- style="display: none" --}}
                        {{-- x-show="modal" --}}
                        {{-- @click.away="modal = false" --}}
                        >
                        <div class="ctr-listAddTypeSignature mt-1">
                            
                            <div class="cListAddTypeSignature">
                                @php
                                    $listTypes = [
                                        (object) ['type' => 'draw', 'icon' => 'fas fa-signature', 'text' => 'Draw', 'alpine' => (object) ['a_data' => 'drawnewsignatureshow'] ],
                                        (object) ['type' => 'type', 'icon' => 'fas fa-font', 'text' => 'Type', 'alpine' => (object) ['a_data' => 'typenewsignatureshow'] ],
                                        // (object) ['type' => 'upload', 'icon' => 'fas fa-upload', 'text' => 'Upload', 'alpine' => (object) ['a_data' => ''] ],
                                    ];
                                @endphp
                                
                                @foreach ($listTypes as $key => $type)
                                    <div class="itemAddTypeSignature transition-all delay-[{{ $key*100 }}ms]"
                                        style="visibility: hidden; opacity: 0; pointer-events: none; margin-right: -1rem"
                                        :style="modal ? `visibility: visible; opacity: 1; margin-right: 0` : `visibility: hidden; opacity: 0; pointer-events: none; ; margin-right: -1rem` "
                                        {{-- @click.away="modal = false" --}}
                                        @click.stop
                                        
                                        >
                                        <button 
                                            type="button"
                                            class="actItemAddTypeSignature w-full px-4 py-1 my-0.5 bg-white text-slate-700 rounded-lg hover:bg-gradient-to-r hover:from-[#297DDE] hover:to-[#004DA6] hover:text-white shadow-md shadow-black/60 border-t-2"
                                            
                                            {{-- x-show="modal" --}}
                                            data-add-type="{{ $type->type }}"
                                            @click="modal = false; $dispatch('{{ strtolower($type->alpine->a_data) }}')"
                                        >
                                            <div class="cBtnAct flex items-center gap-2">
                                                <div class="iconAdd size-8 flex items-center justify-center">
                                                    <div class="icon text-lg">
                                                        <i class="{{ $type->icon }}"></i>
                                                    </div>
                                                </div>
                                                <div class="txAdd">
                                                    <div class="tx text-sm">
                                                        <p>{{ $type->text }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                @endforeach
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
    </div>
</div>

@once
    @push('dashboard-custom-main-content')
        
        @livewire('app.signature.partial.create.draw-signature')
        @livewire('app.signature.partial.create.type-signature')
        
        
    @endpush
@endonce

@once
    @push('dashboard-body-script')
        
        <script data-navigate-once>
            
            Livewire.hook('component.init', (component, cleanup) => {
                console.log({
                    // LHookMessage: message,
                    LHookComponent: component,
                    LHookCleanup: cleanup,
                });
            })
        </script>
    @endpush
@endonce