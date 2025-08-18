<div class="tool-action-finalize-signature"
    x-data="tool_sign_finalize_signature"
    @event_tool_sign_finalize_signature.window="event_finalize"
    @event_loading_sign_finalize.window="event_loading"
>
    
    <div class="content-tool-action-finalize-signature p-2">
        
        <div 
            class="fixed inset-0 z-50 bg-black/25 flex items-center justify-center"
            wire:loading.flex
            @loading.window="console.log('Loading bre')"
            {{-- wire.loading.attr="style='display: flex; align-items: center; justify-content: center;'" --}}
            {{-- style="align-items: center; justify-content: center;" --}}
            {{-- class="size-full z-50 bg-black/25 fixed left-0 top-0 flex items-center justify-center" --}}
            >
            
            <div class="content-loading p-4 w-96 bg-white rounded-lg">
                
                <div class="title-loading text-center text-xl">
                    <p wire:stream='title'></p>
                </div>
                
                <div class="icon-loading flex items-center justify-center my-4">
                    <div class="icon text-4xl flex items-center justify-center size-8 text-gray-600 animate-spin">
                        <i class="fas fa-circle-notch"></i>
                    </div>
                </div>
                
                <div class="message-loading text-center mt-2">
                    <p wire:stream='message'></p>
                </div>
                
                
                <div
                    x-data="{ progress: 0 }"
                    x-show="progress"
                    class="progress-loading mt-4">
                    
                    <div wire:stream='progressAlpine' class="hidden"></div>
                    <div class="flex items-center gap-2">
                        <div class="progress-visual w-full bg-gray-200 h-2 rounded-full" wire:stream='progressElement'></div>
                        <div class="progress-text w-12 text-right">
                            <p><span x-text="Math.round(progress)"></span>%</p>
                        </div>
                    </div>
                    
                </div>
                
                <div class="additional-message-loading text-center text-sm">
                    <p wire:stream='additional_message'></p>
                </div>
                
            </div>
            
            
        </div>
        
    </div>
</div>

@once
    @script
    
        <script type="module" data-navigate-once="true">
            
            Alpine.data("tool_sign_finalize_signature", () => {
                
                return {
                    
                    status_show: false,
                    is_loaded: false ,
                    
                    current_id: null,
                    current_page: 1,
                    pdf_scale: 1,
                    scale: 1,
                    
                    title_loading: '',
                    message_loading: '',
                    additional_message: '',
                    progress: null,
                    
                    init() {

                    },
                    
                    event_finalize($e) {
                        const detail = $e?.detail;
                        console.log(detail);
                        
                        if (! detail?.pdf_sign_info||
                            ! detail?.id_signature_type
                        ) {
                            console.warn('Invalid');
                            return;
                        }
                        
                        const sign_info = detail.pdf_sign_info;
                        if (sign_info.page === undefined ||
                            sign_info.pdfSize === undefined ||
                            sign_info.qrSize === undefined ||
                            sign_info.x === undefined ||
                            sign_info.y === undefined
                        ) {
                            console.warn('Invalid');
                            return;
                        }
                        
                        const id_signature_type = detail.id_signature_type;
                        const token = @json(csrf_token());
                        
                        Livewire.dispatch('Finalize-Sign-Document', {
                            event: {
                                sign_info,
                                id_signature_type,
                                token
                            }
                        });
                    },
                    
                    
                    event_loading($e) {
                        const detail = $e?.detail[0];
                        console.log("Event detail:", detail);
                        
                        if (detail) {
                            if (detail.title) {
                                console.log(detail.title);
                                this.title_loading = detail.title;
                            }
                            if (detail.message) {
                                console.log(detail.message);
                                this.message_loading = detail.message;
                            }
                            if (detail.additional_message) {
                                console.log(detail.additional_message);
                                this.additional_message = detail.additional_message;
                            }
                            if (detail.progress !== undefined) {
                                console.log(detail.progress);
                                this.progress = detail.progress;
                            }
                        }
                    }
                    
                    
                
                } 
                
            });
            
        </script>
    
    @endscript
    
    
@endonce