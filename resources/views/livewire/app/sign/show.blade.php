<div 
    class="flex flex-col flex-grow"
    x-data="pdf_sign_document"
    @status_pdf_load.window="pdf_loaded($event)"
    @process_pdf_load.window="pdf_process_load($event)"
    @visible_pdf_page_change.window="pdf_visible_page"
    
    @signature_added_to_pdf.window="pdf_add_sign"
    >
    
    {{-- <div class="header-filename text-center">
        <div class="text-filename">
            <p>{{ $filename }}</p>
        </div>
    </div> --}}
    
    <div class="header-sign sticky top-0 z-20"
        x-show="status_pdf_load"
    >
        <div class="c-header-sign ">
            
            <div class="main-header-sign px-4 py-2 flex items-center justify-between bg-slate-200 shadow-md shadow-black/40">
                <div class="left-header-sign shrink-0 lg:w-1/4">
                    
                    <div class="header-number-page"
                    >
                        <div class="content-header-number-page flex items-center gap-2 size-fit">
                            <div class="action-header-change-page flex items-center gap-2">
                                <div class="goto-prev-page">
                                    <button 
                                        class="size-8 flex items-center justify-center rounded-md bg-gray-100"
                                        @click="pdf_prev_page"
                                        
                                        :class="current_page === 1 ? 'opacity-80' : 'hover:bg-white'"
                                        :disabled="current_page === 1"
                                        >
                                        
                                        <div class="action-icon">
                                            <div class="icon">
                                                <i class="fas fa-chevron-up"></i>
                                            </div>
                                        </div>
                                        
                                    </button>
                                </div>
                                
                                <div class="goto-next-page">
                                    <button
                                        class="size-8 flex items-center justify-center rounded-md bg-gray-100"
                                        @click="pdf_next_page"
                                        
                                        :class="current_page === total_page ? 'opacity-80' : 'hover:bg-white'"
                                        :disabled="current_page === total_page"
                                    >
                                        <div class="action-icon">
                                            <div class="icon">
                                                <i class="fas fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            
                            <div 
                                class="number-change-page flex items-center gap-1 bg-gray-200 rounded-md overflow-hidden">
                                <div class="current-number-page bg-gray-50 px-2 py-1 min-w-8 text-center">
                                    <div class="text-current-page">
                                        <p x-text="current_page">0</p>
                                    </div>
                                </div>
                                <div class="total-number-page bg-gray-50 px-2 py-1 min-w-8 text-center">
                                    <div class="text-total-page">
                                        <p x-text="total_page">0</p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    
                </div>
                
                <div class="center-header-sign hidden xl:block  flex-grow">
                    <div class="content-center-header flex items-center justify-center gap-2">
                        <div class="text-filename text-center">
                            <p>{{ $filename }}</p>
                        </div>
                        {{-- <div class="type-signature-document px-2 py-1 bg-gray-600 select-none rounded-lg">
                            <div class="text-type text-sm text-white">
                                <p>{{ $doc_type->get_style()['text'] }}</p>
                            </div>
                        </div> --}}
                    </div>
                </div>
                
                <div class="right-header-sign shrink-0 lg:w-1/4 flex items-center justify-end"
                    x-data="tool_sign_document"
                    >
                    <div class="content-right-header-sign">
                        
                        @if ($this->doc_type !== \App\Enums\Documents\Signature\Type::UNCATEGORIZED)
                            
                            <div class="tool-add-signature">
                                <button class="flex items-center gap-2 border border-black rounded px-2"
                                    @click="tool_add_signature"
                                >
                                    
                                    <div class="icon-signature flex items-center justify-center size-8 ">
                                        <div class="icon text-xl">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="text-tool text-sm">
                                        <p>Add {{ $doc_type->label() }}</p>
                                    </div>
                                    
                                </button>
                            </div>
                            
                        @endif
                            
                    </div>
                </div>
            </div>
            
            @if ($doc_type === \App\Enums\Documents\Signature\Type::UNCATEGORIZED)
                @php
                    $messageNotConfig = $is_owner
                        ? "select the signature type."
                        : "please contact the owner.";
                        
                @endphp
                <div class="type-document-not-configured bg-yellow-50 px-4 py-2 flex items-center gap-2"
                    x-show="status_pdf_load"
                    x-data="configure_sign_type_document"
                >
                    <div class="main-text text-sm text-yellow-800">
                        <p>This document type is not yet configured, {{ $messageNotConfig }}</p>
                    </div>
                    
                    @if ($is_owner)
                        <button class="block px-4 py-1 bg-yellow-600 rounded-md"
                            @click="configure"
                            >
                            <div class="text-action text-yellow-100 text-xs">
                                <p>Configure</p>
                            </div>
                        </button>
                    @endif
                    
                </div>
                
            @endif
            
        </div>
    </div>
    
    
    
    <div class="main-sign mt-2"
        x-show="status_pdf_load"
        >
        
        <div class="content-main-sign flex items-center justify-center relative">
            
            <div class="container-left-main-sign fixed top-14 z-10">
                
            </div>
            
            
            <div class="container-view-sign pdfViewer z-0"
                id="container-id-view-sign">
                {{-- content pdf --}}
            </div>
            
            
            <div class="container-right main-sign fixed right-0 z-10 transition-all w-full lg:w-96"
                x-data="{ isSticky: false }"
                x-init="window.addEventListener('scroll', () => isSticky = window.scrollY > 42 )"
                :class="isSticky ? 'top-14 max-h-[calc(100%-3.75rem)]' : 'top-32 max-h-[calc(100%-8.25rem)]' "
            >
                @if ($this->doc_type !== \App\Enums\Documents\Signature\Type::UNCATEGORIZED)
                    @livewire('app.sign.tool.add-signature', ['id_document' => $id_document])
                @endif
                
                
            </div>
            
            
        </div>
        
        
    </div>
    
    <div 
        class="loading-element-pdf bg-white px-8 py-4 fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center justify-center shadow-md shadow-black/40 rounded-md"
        x-ref="loading_element_status"
        
        >
        <div class="content-loading-element-pdf">
            
            <div class="icon-loading relative flex items-center justify-center">
                <div class="icon animate-spin text-2xl text-gray-400">
                    <i class="fas fa-circle-notch"></i>
                </div>
            </div>
            
            <div class="text-loading text-center mt-2">
                <p>Preparing your document...</p>
                <p class="text-sm text-gray-500">
                    Rendering page 
                    {{-- <span x-text="processed_page"></span> of <span x-text="total_page"></span> --}}
                </p>
            </div>
        </div>
    </div>
    
    
    @if ($doc_type === \App\Enums\Documents\Signature\Type::UNCATEGORIZED)
        
        @livewire('app.sign.tool.configure-type', ['id_document' => $id_document])
        
    @endif
    
    
</div>


@once
    
    @push('sign-body-script')

        @if (
            $this->document_version ||
            $this->file_entity ||
            $this->file_disk_entity ||
            $this->file_disk ||
            $this->file_disk_token
        )
            @script
                
                <script type="module" data-navigate-once="true">
                    const $route_file = @json(route('drive.files.entity_document', ['token' => $file_disk_token->token]))
                    
                    const origin = getURL()['origin'];
                    let pathname = "sign/" + @json($document_version->id_document);
                    let filename = @json($filename);
                    let urlupdate = origin + '/' + pathname + '/' + filename;
                    windowReplacestate(null, '', urlupdate);
                    
                    initPDFViewer($route_file, 'container-id-view-sign');
                </script>
                
                
            @endscript
        @endif
        
        
        @script
            
            <script type="module" data-navigate-once="true">
                Alpine.data('pdf_sign_document', () => {
                    
                    return {
                        status_pdf_load: false,
                        is_pdf_error: false,
                        
                        
                        total_page: 0,
                        processed_page: 0,
                        current_page: 0,
                        
                        init() {
                            
                        },
                        
                        pdf_next_page() {
                            
                            this.current_page += 1;
                            if (this.current_page > this.total_page) {
                                this.current_page = this.total_page;
                            }
                            
                            this.$dispatch('update_current_page', {
                                current_page: this.current_page
                            });
                            
                            renderPage(this.current_page);
                        },
                        
                        pdf_prev_page() {
                            this.current_page -= 1;
                            if (this.current_page < 1) {
                                this.current_page = 1;
                            }
                            this.$dispatch('update_current_page', {
                                current_page: this.current_page
                            });
                            
                            
                            renderPage(this.current_page);
                        },
                        
                        pdf_loaded($e) {
                            const detail = $e?.detail;
                            
                            this.status_pdf_load = true;
                            this.$refs.loading_element_status.remove();
                            this.current_page = 1;
                            
                        },
                        
                        pdf_process_load($e) {
                            const detail = $e?.detail;
                            
                            if (detail) {
                                
                                this.total_page = detail.total_page;
                                this.processed_page = detail.current_page;
                                this.current_page = detail.current_page;
                            }
                        },
                        
                        pdf_visible_page($e) {
                            const detail = $e?.detail;
                            
                            if (detail) {
                                
                                this.current_page = detail.current_page;
                                this.$dispatch('update_current_page', {
                                    current_page: detail.current_page
                                });
                                
                            }
                        },
                        
                        
                        pdf_add_sign($e) {
                            
                            const detail = $e?.detail;
                            
                            if (detail) {
                                const data = detail[0];
                                
                                
                                
                                // const base64 = `data:${data.mime};base64,${data.base64}`;
                                const base64 = data.base64;
                                
                                console.log(base64);
                                console.log(data);
                                
                                addSignatureToPage(data.page, data.x, data.y, base64)
                                
                            }
                        }
                        
                    };
                    
                });
                
                
            </script>
            
        @endscript
        
        
        @script
            
            <script type="module" data-navigate-once="true">
                
                Alpine.data("tool_sign_document", () => {
                    
                    return {
                        
                        
                        init() {
                            
                            
                        },
                        
                        tool_add_signature() {
                            
                            this.$dispatch("show_tool_sign_add_signature");
                            
                        },
                    }
                    
                });
                
            </script>
        
        @endscript
        
        @if ($doc_type === \App\Enums\Documents\Signature\Type::UNCATEGORIZED && $is_owner)
            @script
                
                <script type="module" data-navigate-once="true">
                    
                    Alpine.data('configure_sign_type_document', () => {
                        
                        
                        return {
                            
                            init() {

                            },
                            
                            configure() {
                                this.$dispatch("show_configure_type_signature");
                            }
                            
                        }
                        
                    })
                    
                </script>
                
            @endscript
            
        @endif
        
    @endpush
    
    
@endonce
