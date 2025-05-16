@php
    $navColorInbox = [
        'bg-gradient-to-tr from-[#004DA6] to-[#297DDE] text-white',
        'border border-black',
    ];
    $lstNavInboxDashboard = [
        (object) [
            'icon' => 'fas fa-inbox',
            'label' => 'Inbox',
            'page' => 'inbox',
        ],
        (object) [
            'icon' => 'fas fa-file-export',
            'label' => 'Sent',
            'page' => 'sent',
        ],
        (object) [
            'icon' => 'fas fa-file-pen',
            'label' => 'Draft',
            'page' => 'draft',
        ],
        (object) [
            'icon' => 'fas fa-envelopes-bulk',
            'label' => 'All',
            'page' => 'all',
        ],
    ];
@endphp

<div class="lstNavInboxDashboard flex gap-2" x-data="spa_mail">
    @foreach ($lstNavInboxDashboard as $itmNavInboxDashboard)
        <div class="itmNavInboxDashboard-{{ $itmNavInboxDashboard->label }}">
            <button @click="setParamUrl(`{{ $itmNavInboxDashboard->page }}`)" 
                
                {{-- wire:current='{{ $navColorInbox[0] }}' --}}
                class="href-ItmNavInboxDashboard-{{ $itmNavInboxDashboard->label }}
                    block px-4 py-2 w-36 rounded-md 
                    {{ $navColorInbox[1] }}
                "
                :class="activePage === '{{ $itmNavInboxDashboard->page }}' 
                    ? '{{ $navColorInbox[0] }}' 
                    : '{{ $navColorInbox[1] }}'"
                >
                
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
            </button>
        </div>
    @endforeach
</div>


@once
    @push('dashboard-body-script')
        <script data-navigate-once>
            Alpine.data('spa_mail', () => {
                let newUrl = new URL(NOW_URL);
                let paramQV = new URLSearchParams(newUrl.search).get('t');
                const paramList = ['inbox', 'sent', 'draft', 'all'];
                console.log(JSON.parse(JSON.stringify(newUrl)));
                console.log(newUrl);
                console.log(new URLSearchParams(newUrl.search).get('t'));
                return {
                    activePage: paramQV,
                    init() {
                        console.log('SPA Mail initialized');
                        if (!paramList.includes(this.activePage)) this.setParamUrl(paramList[0]);
                    },
                    setParamUrl($valPar) {this.activePage = $valPar; // ← update active state
                        newUrl.searchParams.set('t', $valPar);
                        window.history.pushState({}, '', newUrl);

                        console.log({ page: $valPar });
                        Livewire.dispatch('spa_nav', {page: $valPar});
                        Livewire.hook('message.received', (message, component) => {
                            console.log('Message received:', message, 'by', component);
                        });
                    },
                };
            });
            // Livewire.hook('component.init', ({ component, cleanup }) => {
            //     console.log(component);
            //     console.log(cleanup);
            // });
            Livewire.hook('component.init', ({ component }) => {
                console.log("Livewire component initialized", component);
            });
            Livewire.hook('message.received', (message, component) => {
                console.log('Message received:', message, 'by', component);
            });
        </script>
    @endpush
@endonce