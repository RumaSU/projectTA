@push('additional-title')
    - Document
@endpush

@push('sign-head-script')
    {{-- <script src="
    https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/wasm/openjpeg_nowasm_fallback.min.js
    "></script> --}}
@endpush

@push('sign-head-css')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/web/pdf_viewer.css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('vendor/pdf.js/web/pdf_viewer.css') }}"> --}}
    {{-- <link href="
    https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/web/pdf_viewer.min.css
    " rel="stylesheet"> --}}
@endpush

<div class="app flex-grow">
    
    <div class="c-app flex flex-col h-full">
        
        @if ($is_found)
            
            @livewire("app.sign.show", ['id_document' => $id_document, 'lazy' => true])
            
        @else
            
            @include("livewire.app.sign.not-found")
            
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