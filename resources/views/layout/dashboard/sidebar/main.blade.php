@php
    $routeActive = 'bg-[#0D7BF9]';
    $routeNotActive = 'hover:bg-[#0D7BF9]';
    
    $routeStickActive = 'visible opacity-100';
    $routeStickNotActive = 'invisible opacity-0 group-hover:visible group-hover:opacity-100';
@endphp

<div class="mainNavAside">
    <div class="itmNvAside">
        <a href="{{ route('dashboard.home') }}"
            {{-- class="homeFieldDashboard block p-2 text-gray-300 rounded-lg overflow-hidden relative transition-all group {{ Route::is('dashboard.home') ? $routeActive : $routeNotActive }}" --}}
            class="homeFieldDashboard block py-2 px-4 text-white rounded-lg overflow-hidden relative transition-all group {{ Route::is('dashboard.home') ? $routeActive : $routeNotActive }}"
            role="link"
            aria-label="Navigate to Home Dashboard"
            wire:navigate>
            
            <div class="cHomeFieldDashboard flex items-center gap-4">
                <div class="icnHomeDashboard size-8 flex items-center justify-center" role="img" aria-label="Icon Home Dashboard">
                    <ag-icon class="text-lg text-center">
                        <i class="fas fa-house"></i>
                    </ag-icon>
                </div>
                <div class="txLblAction text-sm hidden xl:block">
                    <p>Home</p>
                </div>
            </div>
            <div class="stickActive w-1.5 h-3/4 rounded-full bg-[#E6BF3C] absolute left-0 top-1/2 -translate-y-1/2 transition-all {{ Route::is('dashboard.home') ? $routeStickActive : $routeStickNotActive }}"></div>
        </a>
    </div>
</div>

@php
    $LstNavGroupAside = [
        (object) array(
            'titleGroup' => 'general',
            'lstNavAside' => [
                // (object) array(
                //     'titleNav' => 'inbox',
                //     'icon' => 'fas fa-inbox',
                //     'routeNav' => route('inbox.main'),
                //     'activeRoute' => 'inbox',
                //     'wireNavigate' => true,
                // ),
                (object) array(
                    'titleNav' => 'mail',
                    'icon' => 'fas fa-envelope',
                    'routeNav' => route('mail.main'),
                    'activeRoute' => 'mail',
                    'wireNavigate' => true,
                ),
                (object) array(
                    'titleNav' => 'documents',
                    'icon' => 'fas fa-book-open',
                    'routeNav' => '',
                    'activeRoute' => 'documents',
                    'wireNavigate' => true,
                ),
                (object) array(
                    'titleNav' => 'my Signature',
                    'icon' => 'fas fa-signature',
                    'routeNav' => '',
                    'activeRoute' => '',
                    'wireNavigate' => true,
                ),
                (object) array(
                    'titleNav' => 'archives',
                    'icon' => 'fas fa-box-archive',
                    'routeNav' => '',
                    'activeRoute' => '',
                    'wireNavigate' => true,
                ),
            ],
        ),
        (object) array(
            'titleGroup' => 'settings',
            'lstNavAside' => [
                // (object) array(
                //     'titleNav' => 'account',
                //     'icon' => 'fas fa-user',
                //     'routeNav' => '',
                //     'activeRoute' => 'account.overview',
                //     'wireNavigate' => true,
                // ),
                (object) array(
                    'titleNav' => 'settings',
                    'icon' => 'fas fa-gear',
                    'routeNav' => '',
                    'activeRoute' => 'setting.overview',
                    'wireNavigate' => true,
                ),
            ],
        ),
    ];
@endphp

@foreach ($LstNavGroupAside as $itmGroupNavAside)
    <div class="{{ $itmGroupNavAside->titleGroup }}NavAside mt-6">
        <div class="titleGroupNavAside select-none max-xl:hidden -ml-4" 
            id="group-{{ $itmGroupNavAside->titleGroup }}" 
            role="heading" 
            aria-level="2" 
            aria-label="Menu Group: {{ ucfirst($itmGroupNavAside->titleGroup) }}">
            
            {{-- <div class="txTitle pl-4 text-sm text-gray-300 tracking-wide"> --}}
            <div class="txTitle pl-4 text-sm text-white tracking-wide">
                <p>{{ ucfirst($itmGroupNavAside->titleGroup) }}</p>
            </div>
        </div>
        <div class="lst{{ $itmGroupNavAside->titleGroup }}GroupNavAside mt-2 space-y-0.5">
            @foreach ($itmGroupNavAside->lstNavAside as $itmNavAside)
                <div class="itmNvAside">
                    <a href="{{ $itmNavAside->routeNav }}"
                        class="{{ implode('', explode(' ', $itmNavAside->titleNav)) }}FieldDashboard 
                            block py-2 px-4 text-white rounded-lg overflow-hidden relative transition-all group 
                            {{ Str::contains(Route::currentRouteName(), $itmNavAside->activeRoute) ? $routeActive : $routeNotActive }}"
                        role="link"
                        aria-label="Navigate to {{ ucwords($itmNavAside->titleNav) }}"
                        {{ $itmNavAside->wireNavigate ? 'wire:navigate' : '' }}>
                            
                        <div class="c{{ ucfirst(implode('', explode(' ', $itmNavAside->titleNav))) }}FieldDashboard flex items-center gap-4">
                            <div class="icn{{ ucfirst(implode('', explode(' ', $itmNavAside->titleNav))) }} size-8 flex items-center justify-center" role="img" aria-label="Icon {{ ucwords($itmNavAside->titleNav) }}">
                                <ag-icon class="text-lg text-center">
                                    <i class="{{ $itmNavAside->icon }}"></i>
                                </ag-icon>
                            </div>
                            <div class="txLblAction text-sm hidden xl:block">
                                <p>{{ ucfirst($itmNavAside->titleNav) }}</p>
                            </div>
                        </div>
                        {{-- @if (Route::is($itmNavAside->activeRoute))
                            <div class="stickActive w-1 h-3/4 rounded-full bg-[#FFD700]/60 absolute left-0 top-1/2 -translate-y-1/2 transition-all"></div>
                        @endif --}}
                        <div class="stickActive w-1.5 h-3/4 rounded-full bg-[#E6BF3C] absolute left-0 top-1/2 -translate-y-1/2 transition-all {{ Str::contains(Route::currentRouteName(), $itmNavAside->activeRoute) ? $routeStickActive : $routeStickNotActive }}"></div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    
@endforeach