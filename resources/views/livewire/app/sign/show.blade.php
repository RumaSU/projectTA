<div 
    class="flex flex-col flex-grow"
    x-data="pdf_sign_document"
    @status_pdf_load.window="pdf_loaded($event)"
    @process_pdf_load.window="pdf_process_load($event)"
    @visible_pdf_page_change.window="pdf_visible_page"
    
    @update_pdf_sign_add.window="pdf_add_sign"
    @update_pdf_sign_info.window="pdf_sign_info"
    
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
                    <div class="content-right-header-sign flex items-center gap-2">
                        
                        @if ($doc_type !== \App\Enums\Documents\Signature\Type::UNCATEGORIZED)
                            
                            @if ($doc_status !== \App\Enums\Documents\Signature\Status::COMPLETED &&
                                 $doc_status !== \App\Enums\Documents\Signature\Status::REJECTED
                            )
                                
                                <div class="tool-add-signature">
                                    <button class="border border-blue-500 text-blue-500 px-4 py-0.5 rounded-lg flex items-center gap-2 hover:bg-blue-50"
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
                                
                                @if ($id_signature_type)
                                    
                                    <div class="tool-finalize-signature">
                                        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-0.5 rounded-lg flex items-center gap-2"
                                            @click="pdf_finalize_sign('{{ $id_signature_type }}')"
                                        >
                                            
                                            <div class="icon-signature flex items-center justify-center size-8 ">
                                                <div class="icon">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="text-tool text-sm">
                                                <p>Finalize</p>
                                            </div>
                                            
                                        </button>
                                    </div>
                                    
                                @endif
                                
                            @endif
                            
                            @if ($doc_status === \App\Enums\Documents\Signature\Status::COMPLETED ||
                                $doc_status === \App\Enums\Documents\Signature\Status::REJECTED
                            )
                                <div class="info-status-signature px-4 py-2.5 rounded-lg {{ $doc_status->get_style()['background'] }}">
                                    <div class="textStatus text-xs {{ $doc_status->get_style()['textColor'] }}">
                                        <p>{{ $doc_status->get_style()['text'] }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if (
                                \App\Utils\ModelUtils::createInstanceModel(\App\Models\Documents\Signed::class)
                                    ->where('id_document', '=', $id_document)
                                    ->exists()
                            )
                                
                                <div class="info-status-signature px-4 py-2.5 rounded-lg bg-green-600 flex items-center gap-2">
                                    <div class="icon-signed flex items-center justify-center text-white">
                                        <div class="icon text-xs">
                                            <i class="fas fa-signature"></i>
                                        </div>
                                    </div>
                                    <div class="textStatus text-xs text-white">
                                        <p>Document Signed</p>
                                    </div>
                                </div>
                                
                            @endif
                            
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
        wire:ignore
        
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
                @if ($doc_type !== \App\Enums\Documents\Signature\Type::UNCATEGORIZED)
                    @livewire('app.sign.tool.add-signature', ['id_document' => $id_document])
                @endif
                
                
            </div>
            
            
        </div>
        
        
    </div>
    
    @if (! $id_signature_type)
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
    @endif
    
    
    
    @if ($doc_type === \App\Enums\Documents\Signature\Type::UNCATEGORIZED)
        @livewire('app.sign.tool.configure-type', ['id_document' => $id_document])
    @endif
    
    @if ($id_signature_type)
        @livewire('app.sign.tool.finalize', ['id_document' => $id_document])
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
                        pdf_scale: 1,
                        
                        value_pdf_sign_info: null,
                        
                        init() {
                            
                        },
                        
                        pdf_next_page() {
                            this.current_page += 1;
                            if (this.current_page > this.total_page) {
                                this.current_page = this.total_page;
                            }
                            
                            this.pdf_render_page();
                        },
                        
                        pdf_prev_page() {
                            this.current_page -= 1;
                            if (this.current_page < 1) {
                                this.current_page = 1;
                            }
                            
                            this.pdf_render_page();
                        },
                        
                        pdf_render_page() {
                            this.$dispatch('update_current_page', {
                                current_page: this.current_page,
                                pdf_scale: this.pdf_scale
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
                                this.pdf_scale = detail.pdfScale || 1;
                                
                                
                                this.$dispatch('update_current_page', {
                                    current_page: this.current_page,
                                    pdf_scale: this.pdf_scale
                                });
                                
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
                                
                                addSignatureToPage(data.page, data.x, data.y, base64)
                                
                            }
                        },
                        
                        pdf_sign_info($e) {
                            const detail = $e?.detail;
                            if (detail) {
                                this.value_pdf_sign_info = detail;
                            }
                        },
                        
                        pdf_finalize_sign(id_signature_type) {
                            if (! this.value_pdf_sign_info) {
                                return;
                            }
                            
                            const detail = {
                                pdf_sign_info: this.value_pdf_sign_info,
                                id_signature_type
                            }
                            
                            this.$dispatch('event_tool_sign_finalize_signature', detail);
                            
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
