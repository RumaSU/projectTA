<div class="wrapper-configure-type-signature flex items-center justify-center bg-black/20 size-full fixed z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 "
    x-data="configure_type_signature"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    x-show="status_show"
    @show_configure_type_signature.window="event_show($event)"
    @listen_is_redirect_to.window="withRedirect($event); close()"
    @click.away="close"
>
    <div class="configure-type-signature bg-white p-4 rounded-lg shadow-md shadow-black/40 lg:max-w-[28rem]">
        <div class="content-configure-type-signature">
            
            <div class="header-configure-type text-center border-b pb-2">
                <div class="main-text text-xl font-semibold">
                    <p>Select Signature Type</p>
                </div>
                <div class="description-text text-sm text-gray-600 mt-1">
                    <p>Please select the type of signature to be used in the document.</p>
                </div>
            </div>
            
            <div class="main-configure-type flex items-center justify-center gap-4 mt-4">
                
                @php
                    $list_type = \App\Enums\Documents\Signature\Type::get_cases();
                    $list_type = collect($list_type)
                        ->filter(fn($t) => $t !== \App\Enums\Documents\Signature\Type::UNCATEGORIZED);
                @endphp
                
                @foreach ($list_type as $type)
                    
                    <label class="item-configure-type block cursor-pointer group">
                        <div class="content-item-configure">
                            <div class="icon-configure-type flex items-center justify-center w-24 aspect-[3/4] rounded-md bg-gray-200 group-has-[:checked]:bg-blue-100">
                                <div class="icon text-4xl group-has-[:checked]:text-blue-800">
                                    @if ($type === \App\Enums\Documents\Signature\Type::SIGNATURE)
                                        <i class="fas fa-signature"></i>
                                    @elseif ($type === \App\Enums\Documents\Signature\Type::PARAF)
                                        <i class="fas fa-pen-fancy"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="text-configure-type text-center text-sm">
                                <p>{{ $type->label() }}</p>
                            </div>
                            
                        </div>
                        
                        <input type="radio" value="{{ $type->value }}" x-model="type" class="sr-only">
                    </label>
                    
                @endforeach
                
                
            </div>
            
            <div class="list-info-configure-type mt-8 space-y-2">
                <div class="info-configure-type bg-yellow-50 text-yellow-700 text-sm p-3 rounded-md border border-yellow-200">
                    <p>
                        <i class="fas fa-info-circle mr-1"></i> 
                        The signature type determines how and when a document is signed.
                    </p>
                </div>
                <div class="info-configure-type bg-red-50 text-red-700 text-sm p-3 rounded-md border border-red-200">
                    <p>
                        <i class="fas fa-triangle-exclamation mr-1"></i>
                        <strong>Please choose carefully:</strong> 
                        You can only set the signature type <u>once</u>. After it's configured, it cannot be changed.
                    </p>
                </div>
            </div>
            
            <div class="action-configure-type flex items-center justify-center gap-2 mt-8">
                
                <div class="close-configure-type">
                    <button
                        @click="close"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm w-24 flex items-center justify-center transition"
                        type="button"
                        wire:loading.attr='disabled'
                    >
                        <div class="active-close-configure-type">
                            <div class="text-action">
                                <p>Close</p>
                            </div>
                        </div>
                        
                    </button>
                </div>
                
                <div class="save-configure-type">
                    <button
                        @click="change"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm w-24 flex items-center justify-center transition"
                        type="button"
                        wire:loading.attr='disabled'
                    >
                        <div class="active-close-configure-type">
                            <div class="text-action">
                                <p>Save</p>
                            </div>
                        </div>
                    </button>
                </div>
                
            </div>
            
        </div>
    </div>
</div>


@once
    @script
    
        <script type="module" data-navigate-once="true">
            
            Alpine.data("configure_type_signature", () => {
                const notifyListener = @json(\App\Enums\CustomToastNotification::get_dispatch_name());
                
                return {
                    
                    id_document: '',
                    status_show: false,
                    is_loaded: false,
                    type: '',
                    
                    
                    init() {
                        
                        this.id_document = @json($id_document) ?? null;
                        
                    },
                    
                    event_show($e) {
                        console.log("event shot tool action add signature");
                        console.log($e);
                        console.log($e.detail);
                        if (typeof $e === 'object') {
                            const has_detail = 'detail' in $e;
                            
                            if (has_detail) {
                                const length = Object.keys($e.detail).length;
                                const id_document = $e.detail?.id_document ?? $e.detail[0]?.id_document;
                                if (id_document && !this.id_document) {
                                    this.id_document = id_document;
                                }
                            }
                        }
                        
                        this.status_show = true;
                        
                        document.body.classList.add("overflow-hidden");
                        document.body.style.overflow = "hidden";
                    },
                    
                    change() {
                        if (! this.type) return;
                        if (! this.id_document) {
                            this.$dispatch(notifyListener, {
                                variant: 'danger',
                                title: 'Oops!',
                                message: '',
                            });
                            
                            return;
                        } 
                        
                        this.$wire.change_type(this.type, this.id_document);
                    },
                    
                    close() {
                        this.status_show = false;
                        this.type = '';
                        
                        document.body.classList.remove("overflow-hidden");
                        document.body.style.overflow = "auto";
                    },
                    
                    withRedirect($e) {
                        console.log('KOTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT');
                        
                        if (typeof $e !== 'object') {
                            return;
                        }
                        
                        const url = $e?.detail[0]?.url;
                        if (! url) {
                            return;
                        }
                        
                        setTimeout(() => {
                            window.open(url, '_blank');
                        }, 1000);
                    }
                
                } 
                
            });
            
        </script>
     
    @endscript
@endonce