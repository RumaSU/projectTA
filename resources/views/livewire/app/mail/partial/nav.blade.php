@php
    $navColorMail = [
        'bg-gradient-to-tr from-[#004DA6] to-[#297DDE] text-white',
        'border border-black',
    ];
    $lstNavMailDashboard = [
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

<div class="ctr-navMailDashboard">
    <div class="cNavMailDashboard flex justify-between">
        <div class="ctr-leftNavMailDashboard">
            <div class="cLeftNavMailDashboard">
                <div class="ctr-lstMainNavMailDashboard">
                    <div class="cLstMainNavMailDashboard flex gap-2" x-data="spa_mail">
                        @foreach ($lstNavMailDashboard as $itmNavMailDashboard)
                            <div class="itmNavMailDashboard-{{ $itmNavMailDashboard->label }}">
                                <button wire:spamail='{{ $itmNavMailDashboard->page }}' wire:click='test' @click="setParamUrl(`{{ $itmNavMailDashboard->page }}`)" 
                                    
                                    {{-- wire:current='{{ $navColorMail[0] }}' --}}
                                    class="href-ItmNavInboxDashboard-{{ $itmNavMailDashboard->label }}
                                        block px-4 py-2 w-36 rounded-md 
                                        {{ $navColorMail[1] }}
                                    "
                                    :class="activePage === '{{ $itmNavMailDashboard->page }}' 
                                        ? '{{ $navColorMail[0] }}' 
                                        : '{{ $navColorMail[1] }}' "
                                    >
                                    
                                    <div class="cHrefNavMailDashboard-{{ $itmNavMailDashboard->label }}
                                        flex items-center gap-4">
                                        <div class="iconNavMail">
                                            <div class="icon">
                                                <i class="{{ $itmNavMailDashboard->icon }}"></i>
                                            </div>
                                        </div>
                                        <div class="txLabelNavMail">
                                            <div class="txLabel">
                                                <p>{{ $itmNavMailDashboard->label }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="ctr-rightMailNavDashboard">
            <div class="cRightMailNavDashboard">
                <div class="wrapper-searchMailNavDashboard">
                    <div class="searchMailNavDashboard flex py-0.5 pl-2 pr-1 focus-within:ring-1 focus-within:ring-blue-600 border border-black overflow-hidden rounded-full"
                        x-data="search_mail"
                    >
                        <div class="inpSearchMail flex items-center justify-center px-2">
                            <div class="inpF">
                                {{-- <input type="text" id="inpSearchMail" placeholder="search..." class="text-sm bg-transparent p-0 border-none ring-0 focus:border-none focus:ring-0"> --}}
                                <input 
                                    type="text" 
                                    id="inpSearchMail" 
                                    placeholder="search..." 
                                    class="text-sm bg-transparent p-0 focus:ring-0 focus:ring-transparent outline-none w-64"
                                    x-model="inpSearch"
                                    wire:click
                                    @change="handleSearchMail"
                                    >
                            </div>
                        </div>
                        <div class="wrapper-btnSearchMail">
                            <button class="btnSearchMail block p-1 rounded-full hover:bg-gray-200">
                                <div class="cButton">
                                    <div class="icnSearch flex items-center justify-center size-8">
                                        <div class="icn text-lg">
                                            <i class="fas fa-magnifying-glass"></i>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- @once
    @push('dashboard-body-script')
        <script data-navigate-once>
            
            
            // Livewire.directive('spamail', ({ el, directive, component, cleanup }) => {
            //     // Event handler saat elemen diklik
            //     let content =  directive.expression;
                
            //     let onClick = e => {
                    
            //         if (spamail(content)) {
            //             console.group('Livewire.directive - spamail');
            //             console.log('Element (el):', el);
            //             console.log('Directive:', directive);
            //             console.log('Component:', component);
            //             console.log('Cleanup function:', cleanup);
            //             console.log('Event:', e);
            //         }
                    
            //         console.group('Livewire.directive - spamail');
            //         console.log('Element (el):', el);
            //         console.log('Directive:', directive);
            //         console.log('Component:', component);
            //         console.log('Cleanup function:', cleanup);
            //         console.log('Event:', e);
            //         // console.groupEnd();
                    
            //         const url = new URL(window.location.href);
            //         url.searchParams.set('folder', folder);
            //         window.history.pushState({}, '', url);
                    
            //         // // Misal: aksi tambahan
            //         // const folder = directive.expression; // contoh isi: "inbox"
            //         // Livewire.dispatch('change-folder', { folder });
            //     };

            //     // Pasang listener dengan capture true agar bisa mencegat lebih awal
            //     el.addEventListener('click', onClick, { capture: true });

            //     // Hapus listener jika elemen dihapus dari DOM
            //     cleanup(() => {
            //         el.removeEventListener('click', onClick);
            //     });
            // });
            
            // Livewire.directive('testdirective', (el, directive, component, cleanup) => {
            //     console.log('testdirective');
            // });
            
            
            
            
            
            
            
            
            
            
            
            
            Alpine.data('spa_mail', () => {
                let newUrl = new URL(NOW_URL);
                let paramQV = new URLSearchParams(newUrl.search).get('t');
                const paramList = ['inbox', 'sent', 'draft', 'all'];
                // console.log(JSON.parse(JSON.stringify(newUrl)));
                // console.log(newUrl);
                // console.log(new URLSearchParams(newUrl.search).get('t'));
                return {
                    activePage: paramQV,
                    init() {
                        console.log('SPA Mail initialized');
                        if (!paramList.includes(this.activePage)) this.setParamUrl(paramList[0]);
                        else this.dispatchInitial(this.activePage);
                    },
                    setParamUrl($valPar) {
                        this.activePage = $valPar; // ← update active state
                        newUrl.searchParams.set('t', $valPar);
                        window.history.pushState({}, '', newUrl);
                        
                        // console.log('Dispatch after click:', { page: $valPar });
                        Livewire.dispatch('Mail-SPA-Page', [{ page: $valPar }]);
                    },
                    dispatchInitial(page) {
                        this.activePage = page;
                        // console.log('Initial dispatch:', { page: page });
                        Livewire.dispatch('Mail-SPA-Page', [{ page: page }]);
                    },
                };
            });
            
            Alpine.data('search_mail', () => {
                let paramQV = whatParamQueryValue('s');
                const csrf_token = '{{ csrf_token() }}';
                
                
                if (paramQV) {
                    console.log(paramQV);
                    $dataParam = {
                        search: paramQV,
                        _token: csrf_token,
                    }
                    Livewire.dispatch('Mail-Search-Page', [$dataParam]);
                    console.log('Dispatching data to: Mail-Search')
                }
                
                return {
                    inpSearch: paramQV,
                    handleSearchMail(event) {
                        const valInp = event.target.value;
                        setParamsQuery('s', valInp);
                        
                        $dataSearch = {
                            search: valInp,
                            _token: csrf_token,
                        }
                        Livewire.dispatch('Mail-Search-Page', [$dataSearch]);
                        console.log(event.target.value);
                    },
                };
            });
            
            function dispatchingDataTo($dispatchKey, $dispatchData) {
                if (typeof $dispatchData !== 'object') {
                    alert('Please set the dispatch data to object type');
                    return {
                        page: window.location.href,
                        status: 'failed',
                        key: $dispatchKey,
                        data: $dispatchData,
                    };
                }
                
                Livewire.dispatch($dispatchKey, [$dispatchData]);
                return {
                    page: window.location.href,
                    status: 'success',
                    key: $dispatchKey,
                    data: $dispatchData,
                };
            }
            
            function intialLivewireDispatch($keyDispatch, $valueDispatch) {
                Livewire.hook('message.processed', (message, component) => {
                    // if (paramQV && !window.__search_dispatched) {
                    //     Livewire.dispatch('Mail-Search-Page', [dataParam]);
                    //     window.__search_dispatched = true;
                    //     console.log('Dispatching data to: Mail-Search after Livewire ready');
                    // }
                });
            }
            
            Livewire.hook('component.init', (component, cleanup) => {
                console.log({
                    // LHookMessage: message,
                    LHookComponent: component,
                    LHookCleanup: cleanup,
                });
            })
            
            // function whatParamQueryValue($pQuery) {
            //     return new URLSearchParams(window.location.href).get($pQuery);
            // }
        </script>
    @endpush
@endonce --}}

@push('dashboard-body-script')
        <script data-navigate-once>
            
            
            // Livewire.directive('spamail', ({ el, directive, component, cleanup }) => {
            //     // Event handler saat elemen diklik
            //     let content =  directive.expression;
                
            //     let onClick = e => {
                    
            //         if (spamail(content)) {
            //             console.group('Livewire.directive - spamail');
            //             console.log('Element (el):', el);
            //             console.log('Directive:', directive);
            //             console.log('Component:', component);
            //             console.log('Cleanup function:', cleanup);
            //             console.log('Event:', e);
            //         }
                    
            //         console.group('Livewire.directive - spamail');
            //         console.log('Element (el):', el);
            //         console.log('Directive:', directive);
            //         console.log('Component:', component);
            //         console.log('Cleanup function:', cleanup);
            //         console.log('Event:', e);
            //         // console.groupEnd();
                    
            //         const url = new URL(window.location.href);
            //         url.searchParams.set('folder', folder);
            //         window.history.pushState({}, '', url);
                    
            //         // // Misal: aksi tambahan
            //         // const folder = directive.expression; // contoh isi: "inbox"
            //         // Livewire.dispatch('change-folder', { folder });
            //     };

            //     // Pasang listener dengan capture true agar bisa mencegat lebih awal
            //     el.addEventListener('click', onClick, { capture: true });

            //     // Hapus listener jika elemen dihapus dari DOM
            //     cleanup(() => {
            //         el.removeEventListener('click', onClick);
            //     });
            // });
            
            // Livewire.directive('testdirective', (el, directive, component, cleanup) => {
            //     console.log('testdirective');
            // });
            
            
            
            
            
            
            
            
            
            
            
            
            Alpine.data('spa_mail', () => {
                // let newUrl = new URL(NOW_URL);
                let paramQV = new URLSearchParams(window.location.href).get('t');
                const paramList = ['inbox', 'sent', 'draft', 'all'];
                // console.log(JSON.parse(JSON.stringify(newUrl)));
                // console.log(newUrl);
                // console.log(new URLSearchParams(newUrl.search).get('t'));
                return {
                    activePage: paramQV,
                    init() {
                        console.log('SPA Mail initialized');
                        if (!paramList.includes(this.activePage)) this.setParamUrl(paramList[0]);
                        else this.dispatchInitial(this.activePage);
                    },
                    setParamUrl($valPar) {
                        this.activePage = $valPar; // ← update active state
                        setParamsQuery('t', $valPar);
                        
                        // console.log('Dispatch after click:', { page: $valPar });
                        Livewire.dispatch('Mail-SPA-Page', [{ page: $valPar }]);
                    },
                    dispatchInitial(page) {
                        this.activePage = page;
                        // console.log('Initial dispatch:', { page: page });
                        Livewire.dispatch('Mail-SPA-Page', [{ page: page }]);
                    },
                };
            });
            
            Alpine.data('search_mail', () => {
                let paramQV = whatParamQueryValue('s');
                const csrf_token = '{{ csrf_token() }}';
                
                
                if (paramQV) {
                    console.log(paramQV);
                    $dataParam = {
                        search: paramQV,
                        _token: csrf_token,
                    }
                    Livewire.dispatch('Mail-Search-Page', [$dataParam]);
                    console.log('Dispatching data to: Mail-Search')
                }
                
                return {
                    inpSearch: paramQV,
                    handleSearchMail(event) {
                        const valInp = event.target.value;
                        setParamsQuery('s', valInp);
                        
                        $dataSearch = {
                            search: valInp,
                            _token: csrf_token,
                        }
                        Livewire.dispatch('Mail-Search-Page', [$dataSearch]);
                        console.log(event.target.value);
                    },
                };
            });
            
            // function dispatchingDataTo($dispatchKey, $dispatchData) {
            //     if (typeof $dispatchData !== 'object') {
            //         alert('Please set the dispatch data to object type');
            //         return {
            //             page: window.location.href,
            //             status: 'failed',
            //             key: $dispatchKey,
            //             data: $dispatchData,
            //         };
            //     }
                
            //     Livewire.dispatch($dispatchKey, [$dispatchData]);
            //     return {
            //         page: window.location.href,
            //         status: 'success',
            //         key: $dispatchKey,
            //         data: $dispatchData,
            //     };
            // }
            
            // function intialLivewireDispatch($keyDispatch, $valueDispatch) {
            //     Livewire.hook('message.processed', (message, component) => {
            //         // if (paramQV && !window.__search_dispatched) {
            //         //     Livewire.dispatch('Mail-Search-Page', [dataParam]);
            //         //     window.__search_dispatched = true;
            //         //     console.log('Dispatching data to: Mail-Search after Livewire ready');
            //         // }
            //     });
            // }
            
            // Livewire.hook('component.init', (component, cleanup) => {
            //     console.log({
            //         // LHookMessage: message,
            //         LHookComponent: component,
            //         LHookCleanup: cleanup,
            //     });
            // })
            
            // function whatParamQueryValue($pQuery) {
            //     return new URLSearchParams(window.location.href).get($pQuery);
            // }
        </script>
    @endpush