@php
    $LstNavAdditionalSidebar = [
        (object) array(
            'titleNav' => 'uploadSign',
            'descNav' => 'Upload & Sign',
            'descColor' => 'text-[#533F00]',
            'bgNav' => 'bg-gradient-to-r from-[#FFCA28] to-[#D4A927]',
            'icon' => 'fas fa-upload',
            'iconColor' => 'text-[#533F00]',
            'routeNav' => '#',
            'activeRoute' => '',
            'wireNavigate' => true,
        ),
        (object) array(
            'titleNav' => 'verifyDocument',
            'descNav' => 'Verify Document',
            'descColor' => 'text-white',
            'bgNav' => 'bg-gradient-to-r from-[#297DDE] to-[#004DA6]',
            'icon' => 'fas fa-file-circle-check',
            'iconColor' => 'text-white',
            'routeNav' => '#',
            'activeRoute' => '',
            'wireNavigate' => true,
        ),
    ];
@endphp

@foreach ($LstNavAdditionalSidebar as $itmNavAdditionalSidebar)
    <div class="itm-additionalActionNav">
        <a href="{{ $itmNavAdditionalSidebar->routeNav }}"
            class="{{ implode('', explode(' ', $itmNavAdditionalSidebar->titleNav)) }}FieldDashboard 
                block py-2 px-6 overflow-hidden relative transition-all group rounded-r-xl
                {{ $itmNavAdditionalSidebar->bgNav }}
                "
            role="link"
            aria-label="Navigate to {{ ucwords($itmNavAdditionalSidebar->descNav) }}"
            {{ $itmNavAdditionalSidebar->wireNavigate ? 'wire:navigate' : '' }}>
                
            <div class="
                c{{ ucfirst(implode('', explode(' ', $itmNavAdditionalSidebar->titleNav))) }}FieldDashboard 
                flex items-center gap-6">
                
                <div class="
                    icn{{ ucfirst(implode('', explode(' ', $itmNavAdditionalSidebar->titleNav))) }}
                    size-8 flex items-center justify-center" role="img" aria-label="Icon {{ ucwords($itmNavAdditionalSidebar->titleNav) }}">
                    <div class="text-lg text-center {{ $itmNavAdditionalSidebar->iconColor }}">
                        <i class="{{ $itmNavAdditionalSidebar->icon }}"></i>
                    </div>
                </div>
                <div class="
                    txLblAction text-sm hidden xl:block 
                    {{ $itmNavAdditionalSidebar->descColor }}">
                    
                    <p>{{ ucfirst($itmNavAdditionalSidebar->descNav) }}</p>
                </div>
            </div>
        </a>
    </div>
@endforeach