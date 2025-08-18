<div
    x-data="audit_document"
    class="my-8 p-4 rounded-lg"
>
    {{-- ================= MAIN INFO ================= --}}
    {{-- <div class="main-info-audit bg-white p-4 shadow-sm shadow-black/40  rounded-lg"> --}}
    <div class="main-info-audit">
        <div class="header-main-info-audit flex items-center justify-between border-b border-b-gray-400 pb-2">
            <div class="title-info-audit ">
                <div class="text-title text-2xl font-semibold">
                    <p>Document Information</p>
                </div>
            </div>
            
            @if ($can_view_audit)
                <div class="view-audit-document">
                    <a href="{{ route('app.documents.audit', ['id' => $id_document]) }}" 
                        class="block bg-blue-600 text-white px-4 py-1.5 rounded-lg"
                        target="_blank" rel="noopener noreferrer"
                        >
                        <div class="text-view text-sm">
                            <p>View Audit</p>
                        </div>
                    </a>
                </div>
            @endif
        </div>
        
        <div class="info-document mt-6 text-sm space-y-1" wire:ignore wire:stream='mainInfoAudit'>
            @for ($i = 0; $i < 5; $i++)
                @include('livewire.app.audit.component.skeleton')
            @endfor
        </div>
        
    </div>
    
    <div class="audit-info-signed mt-6">
        <div class="header-info-signed">
            <div class="title-info-audit border-b border-b-gray-400 flex items-center gap-2">
                <div class="text-title text-2xl font-semibold">
                    <p>Signed Information</p>
                </div>
            </div>
            
        </div>
        
        <div class="info-signed mt-6 text-sm space-y-1" wire:ignore wire:stream='signedInfo'>
            @for ($i = 0; $i < 5; $i++)
                @include('livewire.app.audit.component.skeleton')
            @endfor
        </div>
        
        
        <div class="info-signed-certificate ml-4 mt-8" wire:ignore wire:stream='signedCertificateInfo'>
            @include('livewire.app.audit.component.skeleton')
        </div>
        
    </div>
    
</div>


@once
    
    @push('audit-body-script')
        
        @script
            
            <script type="module" data-navigate-once="true">
                
                Alpine.data('audit_document', () => {
                    
                    return {
                        init() {
                            console.log('view audit document');
                            Livewire.dispatch('Stream-Audit-Main-Info');
                            Livewire.dispatch('Stream-Audit-Signed-Info');
                        }
                    };
                    
                });
                
            </script>
            
        @endscript
        
    @endpush
    
@endonce