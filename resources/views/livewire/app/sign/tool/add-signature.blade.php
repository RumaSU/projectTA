<div class="tool-action-add-signature w-full h-96 bg-white overflow-auto rounded-lg shadow-md shadow-black/40"
    x-data="tool_sign_add_signature"
    x-cloak
    x-transition
    x-show="status_show"
    @show_tool_sign_add_signature.window="event_show"
    @update_current_page.window="update_current_page($event)"
    @click.away="status_show = false"
>
    <div class="content-tool-action-add-signature p-2">
        

                
        
        @if (! $is_loaded)
            
            <div class="animate-pulse h-6 rounded-md bg-gray-200 w-1/4"></div>
            <div class="mt-1">
                <div class="animate-pulse h-6 rounded-md bg-gray-200 w-1/4"></div>
                <div class="animate-pulse w-72 mt-2 aspect-video rounded-md bg-gray-200"></div>
            </div>
            
        @else
            
            @php
                $scale = $doc_type === App\Enums\Documents\Signature\Type::SIGNATURE
                    ? 'aspect-video'
                    : 'aspect-square';
                
                $width = $doc_type === App\Enums\Documents\Signature\Type::SIGNATURE
                    ? 'w-96'
                    : 'w-52';
            @endphp
            
            <div class="header-content-tool">
                <div class="text-header font-semibold">
                    <p>List {{ $doc_type->label() ?? 'Signature' }}</p>
                </div>
            </div>
            
            @if (! $is_list_loaded)
                
                <div class="mt-1">
                    <div class="animate-pulse h-6 rounded-md bg-gray-200 w-1/4"></div>
                    <div class="animate-pulse {{ $width }} mt-2 {{ $scale }} rounded-md bg-gray-200"></div>
                </div>
                
                <div class="mt-1">
                    <div class="animate-pulse h-6 rounded-md bg-gray-200 w-1/4"></div>
                    <div class="animate-pulse {{ $width }} mt-2 {{ $scale }} rounded-md bg-gray-200"></div>
                </div>
                
                <div class="mt-1">
                    <div class="animate-pulse h-6 rounded-md bg-gray-200 w-1/4"></div>
                    <div class="animate-pulse {{ $width }} mt-2 {{ $scale }} rounded-md bg-gray-200"></div>
                </div>
                
            @else
                
                @if (! $is_have_signature)
                    
                    not have signature
                    <br>
                    
                @else
                    
                    
                    @if ($default_signature)
                        
                        <div class="header-default-content-tool mt-4">
                            <div class="text-default-header font-semibold text-sm">
                                <p>Default {{ $doc_type->label() ?? 'Signature' }}</p>
                            </div>
                        </div>
                        
                        @php
                            
                            $default_value = reset($default_signature);
                            
                            $default_token = $default_value[$doc_type->value]['token_original'];
                            $default_token_thumbnail = $default_value[$doc_type->value]['token_thumbnail'];
                            
                        @endphp
                        
                        <div class="item-default-signature flex items-center gap-2 mt-2 cursor-pointer bg-white hover:brightness-95 rounded-sm"
                            @click="click_add({{ json_encode($default_value[$doc_type->value]) }})"
                        >
                            
                            <div class="image-default-signature w-24 aspect-square" 
                                x-data="{ show: false, }"
                                >
                                <div 
                                    class="signatureImage rounded-lg relative size-full transition-all bg-gray-100 overflow-hidden"
                                    style="filter: blur(1px);"
                                    :style="show ? '' : `filter: blur(1px)`"
                                    :class="show ? '' : 'animate-pulse'"
                                    
                                    >
                                    <div 
                                        class="image size-full"
                                        style="background: url({{ route('drive.files.entity_signature', [ 'token' => $default_token_thumbnail ] ) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"
                                        wire:ignore.self
                                        >
                                        <img 
                                            src="{{ route('drive.files.entity_signature', [ 'token' => $default_token ] ) }}" 
                                            @load="show = true; $el.parentElement.removeAttribute('style')"
                                            alt="" 
                                            class="size-full object-contain"
                                            data-signature-image="default_signature"
                                            loading="lazy"
                                            >
                                        <div class="loading size-full bg-gray-200/40 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 invisible backdrop-blur-sm"
                                            wire:loading.class.remove='invisible'
                                        ></div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="detail-info-default-signature">
                                <div class="title-defailt-info text-sm">
                                    <p>Default {{ $doc_type->label() }}</p>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                    @endif
                    
                    @if ($list_signature)
                        
                        <div class="header-list-content-tool mt-4">
                            <div class="text-list-header font-semibold text-sm">
                                <p>List rest {{ $doc_type->label() ?? 'Signature' }}</p>
                            </div>
                        </div>
                        
                        @foreach ($list_signature as $id => $item)
                            
                            @php
                                
                                $item_token = $item[$doc_type->value]['token_original'];
                                $item_token_thumbnail = $item[$doc_type->value]['token_thumbnail'];
                                
                            @endphp
                            
                            <div class="item-list-signature flex items-center gap-2 mt-2 cursor-pointer bg-white hover:brightness-95 rounded-sm"
                                @click="click_add({{ json_encode($item[$doc_type->value]) }})"
                                
                            >
                                
                                <div class="image-list-signature w-24 aspect-square" 
                                    x-data="{ show: false, }"
                                    >
                                    <div 
                                        class="signatureImage rounded-lg relative size-full transition-all bg-gray-100 overflow-hidden"
                                        style="filter: blur(1px);"
                                        :style="show ? '' : `filter: blur(1px)`"
                                        :class="show ? '' : 'animate-pulse'"
                                        
                                        >
                                        <div 
                                            class="image size-full"
                                            style="background: url({{ route('drive.files.entity_signature', [ 'token' => $item_token_thumbnail ] ) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"
                                            wire:ignore.self
                                            >
                                            <img 
                                                src="{{ route('drive.files.entity_signature', [ 'token' => $item_token ] ) }}" 
                                                @load="show = true; $el.parentElement.removeAttribute('style')"
                                                alt="" 
                                                class="size-full object-contain"
                                                data-signature-image="list_signature"
                                                loading="lazy"
                                                >
                                            <div class="loading size-full bg-gray-200/40 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 invisible backdrop-blur-sm"
                                                wire:loading.class.remove='invisible'
                                            ></div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="detail-info-list-signature">
                                    <div class="title-defailt-info text-sm">
                                        <p>Item {{ $doc_type->label() }}</p>
                                    </div>
                                </div>
                                
                            </div>
                            
                        @endforeach
                        
                    @endif
                    
                @endif
                
            @endif
            
            
            
        @endif
        
    </div>
</div>

@once
    @script
    
        <script type="module" data-navigate-once="true">
            
            Alpine.data("tool_sign_add_signature", () => {
                
                return {
                    
                    status_show: false,
                    is_loaded: false,
                    
                    current_id: null,
                    current_page: 1,
                    
                    init() {
                        
                    },
                    
                    event_show() {
                        console.log("event shot tool action add signature");
                        this.status_show = true;
                        
                        if (! this.is_loaded) {
                            const $wire = this.$wire;
                            const $el = this.$el;
                            id = @json($id_document);
                            
                            const Observer = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    if (entry.isIntersecting) {
                                        console.log('Element viewed');
                                        this.$wire.mounting(id);
                                        
                                        Observer.unobserve($el)
                                    }
                                })
                            }, {
                                root: null,
                                threshold: 0.1
                            });
                            
                            Observer.observe($el);
                            
                            this.is_loaded = true;
                        }
                        
                    },
                    
                    update_current_page($e) {
                        const detail = $e?.detail;
                        
                        if (detail) {
                            
                            this.current_page = detail.current_page;
                            
                        }
                    },
                    
                    click_add(signature_item) {
                        
                        this.current_id = signature_item;
                        
                        Livewire.dispatch('Add-Image-To-PDF', {
                            event: {
                                signature_item,
                                page: this.current_page
                            }
                        });
                    }
                    
                    
                
                } 
                
            });
            
        </script>
    
    @endscript
    
    
@endonce