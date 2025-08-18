@push('additional-title')
    - Document
@endpush

@push('audit-head-script')
    {{-- <script src="
    https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/wasm/openjpeg_nowasm_fallback.min.js
    "></script> --}}
@endpush

@push('audit-head-css')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/web/pdf_viewer.css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('vendor/pdf.js/web/pdf_viewer.css') }}"> --}}
    {{-- <link href="
    https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/web/pdf_viewer.min.css
    " rel="stylesheet"> --}}
@endpush

<div class="app flex-grow flex justify-center">
    
    <div class="c-app w-full lg:w-3/4 flex flex-col min-h-full">
        
        @if ($can_access)
            
            @livewire("app.audit.audit.show", ['id_document' => $id_document, 'lazy' => true])
            
        @else
            
            @if ($is_found)
                @include('livewire.app.audit.forbidden')
            @else
                @include("livewire.app.audit.not-found")
            @endif
            
        @endif
        
        
        
    </div>
    
</div>


@once
    @push('sign-body-script')
        <script data-navigate-once="true">
            
            console.log("Tes")
            
        </script>
        
        @vite("resources/js/pdf-viewer.js")
    @endpush
@endonce