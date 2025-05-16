@php
    $navColorInbox = [
        'bg-gradient-to-tr from-[#004DA6] to-[#297DDE] text-white',
        'border border-black',
    ];
    $lstNavInboxDashboard = [
        (object) [
            'icon' => 'fas fa-inbox',
            'label' => 'Inbox',
            'routeNav' => route('inbox.main'),
            'activeRoute' => 'main',
            'wireNavigate' => true,
        ],
        (object) [
            'icon' => 'fas fa-file-export',
            'label' => 'Sent',
            'routeNav' => route('inbox.main'),
            'activeRoute' => 'sent',
            'wireNavigate' => true,
        ],
        (object) [
            'icon' => 'fas fa-file-pen',
            'label' => 'Draft',
            'routeNav' => route('inbox.main'),
            'activeRoute' => 'draft',
            'wireNavigate' => true,
        ],
    ];
@endphp

<div class="lstNavInboxDashboard flex gap-2">
    @foreach ($lstNavInboxDashboard as $itmNavInboxDashboard)
        <div class="itmNavInboxDashboard-{{ $itmNavInboxDashboard->label }}">
            <a href="{{ $itmNavInboxDashboard->routeNav }}" 
                {{ $itmNavInboxDashboard->wireNavigate ? 'wire:navigate' : "" }}
                {{-- wire:current='{{ $navColorInbox[0] }}' --}}
                class="href-ItmNavInboxDashboard-{{ $itmNavInboxDashboard->label }}
                    block px-4 py-2 w-36 rounded-md 
                    {{ Str::contains(Route::currentRouteName(), $itmNavInboxDashboard->activeRoute) ? $navColorInbox[0] : $navColorInbox[1] }}
                ">
                
                <div class="cHrefNavInboxDashboard-{{ $itmNavInboxDashboard->label }}
                    flex items-center gap-4">
                    <div class="iconNavInbox">
                        <div class="icon">
                            <i class="{{ $itmNavInboxDashboard->icon }}"></i>
                        </div>
                    </div>
                    <div class="txLabelNavInbox">
                        <div class="txLabel">
                            <p>{{ $itmNavInboxDashboard->label }}</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

