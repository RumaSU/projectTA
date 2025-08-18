<div class="item-info-signed-certificate even:bg-gray-200">
    <div class="header-item-info-signed-certificate border-b border-b-gray-400 flex items-center gap-2 pb-1">
        <div class="title-info-audit">
            <div class="text-title">
                <p>Certificate</p>
            </div>
        </div>
        
        <div class="info-count size-6 bg-blue-600 rounded-md flex items-center justify-center">
            <div class="text-count text-white text-sm">
                <p>{{ $count ?? 1 }}</p>
            </div>
        </div>
    </div>
    
    @php
        $outputSigner = 'N/A';
        if (! empty($signer)) {
            $outputSigner = "
                {$signer->userPersonal->fullname}
                <a href='mailto:{$signer->email}' class='text-blue-600 bg-blue-50 px-2 py-1 rounded'>&lt;{$signer->email}&gt;</a>
            ";
        }
        
        $outputFingerprint = "
            <code class='bg-gray-100 px-2 py-1 rounded text-sm block break-words'>
                {$certificate->fingerprint}
            </code>
        ";

    @endphp
    
    <div class="content-info-signed-certificate text-sm mt-2 space-y-1">
        
        @include('livewire.app.audit.component.item', ['label' => 'Signer', 'value' => $outputSigner])
        @include('livewire.app.audit.component.item', ['label' => 'Fingerprint', 'value' => $outputFingerprint])
        @include('livewire.app.audit.component.item', ['label' => 'Serial Number', 'value' => $certificate->serial_number])
        @include('livewire.app.audit.component.item', ['label' => 'Issuer', 'value' => $certificate->issuer])
        @include('livewire.app.audit.component.item', ['label' => 'Country Name', 'value' => $certificateIdentity->country_name])
        @include('livewire.app.audit.component.item', ['label' => 'State Name', 'value' => $certificateIdentity->state_or_province_name])
        
        <div class="info-signed-identifier mt-2">
            <div class="item-info flex items-center gap-2">
                <div class="label-item w-40 text-right font-medium">
                    <p>QR Identifier</p>
                </div>
                <div class="value-item inline-flex items-center gap-2">
                    <code class='bg-gray-100 px-2 py-1 rounded text-sm block break-words'>
                        {{ $signedIdentifier->identifier }}
                    </code>
                </div>
            </div>
        </div>
        
    </div>
    
    
</div>