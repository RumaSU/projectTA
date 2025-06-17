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
    <div class="cNavMailDashboard flex justify-between" 
        x-data="filterMail"
        @popstate.window='handlePopstate($event)'
        @alpineinitfiltermail.window='handleDataFirstInit($event)'
        >
        <div class="ctr-leftNavMailDashboard">
            <div class="cLeftNavMailDashboard">
                <div class="ctr-lstMainNavMailDashboard">
                    {{-- <div class="cLstMainNavMailDashboard flex gap-2" x-data="spa_mail"> --}}
                    <div class="cLstMainNavMailDashboard flex gap-2">
                        @foreach ($lstNavMailDashboard as $itmNavMailDashboard)
                            <div class="itmNavMailDashboard-{{ $itmNavMailDashboard->label }}">
                                <button 
                                    type="button"
                                    {{-- wire:spamail='{{ $itmNavMailDashboard->page }}' --}}
                                    {{-- @click="setParamUrl(`{{ $itmNavMailDashboard->page }}`)"  --}}
                                    {{-- @click="setParamUrl(`{{ $itmNavMailDashboard->page }}`);" --}}
                                    @click="handleEventChange"
                                    
                                    {{-- wire:current='{{ $navColorMail[0] }}' --}}
                                    class="href-ItmNavInboxDashboard-{{ $itmNavMailDashboard->label }}
                                        block px-4 py-2 w-36 rounded-md 
                                        {{ $navColorMail[1] }}
                                    "
                                    {{-- :class="activePage === '{{ $itmNavMailDashboard->page }}'  --}}
                                    :class="$filterMail.some(x => x.value == '{{ $itmNavMailDashboard->page }}') 
                                        ? '{{ $navColorMail[0] }}' 
                                        : '{{ $navColorMail[1] }}' "
                                    
                                    data-filter-type="type"
                                    data-filter-value="{{ $itmNavMailDashboard->page }}"
                                    value="{{ $itmNavMailDashboard->page }}"
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
                        {{-- x-data="search_mail" --}}
                    >
                        <div class="inpSearchMail flex items-center justify-center px-2">
                            <div class="inpF">
                                {{-- <input type="text" id="inpSearchMail" placeholder="search..." class="text-sm bg-transparent p-0 border-none ring-0 focus:border-none focus:ring-0"> --}}
                                <input 
                                    type="text" 
                                    id="inpSearchMail" 
                                    placeholder="search..." 
                                    class="text-sm bg-transparent p-0 focus:ring-0 focus:ring-transparent outline-none w-64"
                                    {{-- x-model="inpSearch" --}}
                                    x-model="$filterMail[1].value"
                                    {{-- @change="handleSearchMail" --}}
                                    @change="handleEventChange"
                                    data-filter-type="search"
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
@once
    @push('dashboard-body-script')
        <script data-navigate-once>
            
            Alpine.data('filterMail', () => {
                const csrf_token = '{{ csrf_token() }}';
                const page_state = window.history.state;
                const filterList = [
                    {
                        name: 'type',
                        param: 't',
                        list: ['inbox', 'sent', 'draft', 'all'],
                    },
                    {
                        name: 'search',
                        param: 's',
                    },
                    {
                        name: 'page',
                        param: 'p',
                    }
                ]
                let bulkDataParam = [];
                
                return {
                    $filterMail: [
                        {name: 'type', value: ''},
                        {name: 'search', value: ''},
                        {name: 'page', value: 0}
                    ],
                    stateNumber: 1,
                    
                    init() {
                        console.log('Filter Mail Initialized');
                        let isPushstate = false;
                        
                        // check parameter query
                        filterList.forEach((v) => {
                            let valParamQuery = whatParamQueryValue(v.param);
                            let cpFilterMail = this.$filterMail.find(x => x.name == v.name);
                            
                            if (v.name == 'type') {
                                valParamQuery = v.list.includes(valParamQuery) ? valParamQuery : filterList[0].list[0];
                            }
                            
                            cpFilterMail.value = valParamQuery;
                            if (valParamQuery) {
                                bulkDataParam.push( {key: v.param, value: valParamQuery} );
                            }
                        });
                    },
                    
                    handleDataFirstInit($event) {
                        this.handleDispatchData();
                        this.handleBulkSetParamUrl(bulkDataParam, false);
                    },
                    
                    handleEventChange(event) {
                        const eTarget = event.currentTarget;
                        const eDataset = eTarget.dataset;
                        
                        let cpFilterMail = this.$filterMail.find(x => x.name == eDataset.filterType);
                        let cpFilterList = filterList.find(x => x.name == cpFilterMail.name);
                        let isPushState = false;
                        let currentValue;
                        
                        if (cpFilterMail.name == 'type') {
                            currentValue = cpFilterMail.value;
                            if (filterList[0].list.includes(eDataset.filterValue)) {
                                cpFilterMail.value = eDataset.filterValue;
                            } else {
                                cpFilterMail.value = filterList[0].list[0];
                            }
                        }
                        
                        if (cpFilterMail.name == 'search') {
                            currentValue = cpFilterMail.value;
                            cpFilterMail.value = (cpFilterMail.value == eTarget.value) ? cpFilterMail.value : eTarget.value;
                        }
                        
                        // if (currentValue == cpFilterMail.value) {
                        //     isPushState = false;
                        // }
                        
                        this.handleDispatchData();
                        this.handleSetParamUrl(cpFilterList.param, cpFilterMail.value, isPushState);
                        console.log(window.history.state);
                    },
                    
                    handleDispatchData() {
                        let $dispatchingData = {
                            _token: csrf_token,
                            filter: this.$filterMail,
                        };
                        
                        dispatchingDataLivewireTo('Mail-Filter-Data', $dispatchingData);
                    },
                    
                    handleSetParamUrl($kPar, $vPar, $isPushtate = true) {
                        const stateObject = {
                            ...page_state,
                            custom_data: {
                                page: 'mail',
                                typeSet: 'Single',
                                filter: JSON.parse(JSON.stringify(this.$filterMail)),
                                stateNumber: this.stateNumber,
                            },
                        };
                        
                        this.stateNumber++;
                        setParamsQuery($kPar, $vPar, $isPushtate, stateObject);
                    },
                    
                    handleBulkSetParamUrl($dataBulkSet = [], $isPushstate = true) {
                        const stateObject = {
                            ...page_state,
                            custom_data: {
                                page: 'mail',
                                typeSet: 'Bulk',
                                filter: JSON.parse(JSON.stringify(this.$filterMail)),
                                stateNumber: this.stateNumber,
                            },
                        };
                        
                        this.stateNumber++;
                        
                        setBulkParamsQuery($dataBulkSet, $isPushstate, stateObject);
                    },
                    
                    handlePopstate(event) {
                        console.log('handlePopstate: ', event);
                        // const filterPopstate
                    },
                    
                    // setParamUrl($keyPar, $valPar, $isDispatch = true) {
                    //     this.activePage = $valPar; // ← update active state
                    //     setParamsQuery('t', $valPar);
                        
                    //     // console.log('Dispatch after click:', { page: $valPar });
                    //     // Livewire.dispatch('Mail-SPA-Page', [{ page: $valPar }]);
                    // },
                    
                    
                    
                }
                
                
            });
            
            
        </script>
    @endpush
@endonce