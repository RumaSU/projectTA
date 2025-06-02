<div class="ctr-filterDocumentsDashboard">
    <div class="cFilterDocumentsDashboard flex items-center justify-between">
        <div class="ctr-lftFilterDocumentsDashboard" x-data="filterDocument">
            <div class="cLftFilterDocumentsDashboard flex items-start gap-2">
                @php
                    $lstFilterDocuments = [
                        (object) [
                            'title' => 'status',
                            'alpine_data' => '',
                            'filter' => [
                                (object) ['label' => 'All Status', 'value' => 'all'],
                                (object) ['label' => 'In Progress', 'value' => 'progress'],
                                (object) ['label' => 'Completed', 'value' => 'completed'],
                                (object) ['label' => 'Rejected', 'value' => 'rejected'],
                                (object) ['label' => 'Draft', 'value' => 'draft'],
                            ]
                        ],
                        (object) [
                            'title' => 'type',
                            'alpine_data' => '',
                            'filter' => [
                                (object) ['label' => 'All Type', 'value' => 'all'],
                                (object) ['label' => 'Signature', 'value' => 'signature'],
                                (object) ['label' => 'Paraf', 'value' => 'paraf'],
                                (object) ['label' => 'Uncategorized', 'value' => 'uncategorized'],
                            ]
                        ],
                        (object) [
                            'title' => 'type',
                            'alpine_data' => '',
                            'filter' => [
                                (object) ['label' => 'All Type', 'value' => 'all'],
                                (object) ['label' => 'Signature', 'value' => 'signature'],
                                (object) ['label' => 'Paraf', 'value' => 'paraf'],
                                (object) ['label' => 'Uncategorized', 'value' => 'uncategorized'],
                            ]
                        ],
                    ];
                @endphp
                
                <div class="itm-statusFilterDocumentsDashboard relative">
                    <div class="actStatusFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-40 rounded-xl border border-[#9a9a9a]" @click="$filterDocument.status.filterModal = !$filterDocument.status.filterModal">
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p x-text="$filterDocument.status.filterText">All Status</p>
                                    </div>
                                </div>
                                <div class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
                                    <div class="icn text-[#6a6a6a] text-xl">
                                        <i class="fas fa-sliders"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    
                    <div class="wrapper-modalStatusFilterDocumentsDashboard absolute top-full left-0 pt-4"
                        x-show="$filterDocument.status.filterModal"
                        style="display: none"
                        @click.away="$filterDocument.status.filterModal = false"
                        >
                        <div class="ctr-modalStatusFilterDocumentsDashboard bg-white w-52 py-2 rounded-md shadow-md shadow-black/40">
                            <div class="cModalStatusFilterDocumentsDashboard space-y-0.5">
                                <template x-for="itmStatus in listFilter.status" :key="itmStatus.label">
                                    <div class="itm-statusFilterDocuments group">
                                        <label :for="`statusFilterDocument${itmStatus.label}`" class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100">
                                            <div class="cStatusFilterDocument flex items-center justify-between">
                                                <div class="txLblStatusFilter">
                                                    <div class="txLbl text-sm">
                                                        <p x-text="itmStatus.label"></p>
                                                    </div>
                                                </div>
                                                <div class="icnStatusFilter invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                    <div class="icn text-blue-800">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="radio" name="statusFilterDocuments" :id="`statusFilterDocument${itmStatus.label}`" :value="itmStatus.value" :checked="itmStatus.checked" class="sr-only hidden" @change="changeStatusFilter">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="itm-typeFilterDocumentsDashboard relative">
                    <div class="actTypeFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-40 rounded-xl border border-[#9a9a9a]" @click="$filterDocument.type.filterModal = !$filterDocument.type.filterModal">
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p x-text="$filterDocument.type.filterText">All Type</p>
                                    </div>
                                </div>
                                <div class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
                                    <div class="icn text-[#6a6a6a] text-xl">
                                        <i class="fas fa-sliders"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="wrapper-modalTypeFilterDocumentsDashboard absolute top-full left-0 pt-4"
                        x-show="$filterDocument.type.filterModal"
                        style="display: none"
                        @click.away="$filterDocument.type.filterModal = false"
                        >
                        <div class="ctr-modalTypeFilterDocumentsDashboard bg-white w-52 py-2 rounded-md shadow-md shadow-black/40">
                            <div class="cModalTypeFilterDocumentsDashboard space-y-0.5">
                                <template x-for="itmType in listFilter.type" :key="itmType.label">
                                    <div class="itm-TypeFilterDocuments group">
                                        <label :for="`TypeFilterDocument${itmType.label}`" class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100">
                                            <div class="cTypeFilterDocument flex items-center justify-between">
                                                <div class="txLblTypeFilter">
                                                    <div class="txLbl text-sm">
                                                        <p x-text="itmType.label"></p>
                                                    </div>
                                                </div>
                                                <div class="icnTypeFilter invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                    <div class="icn text-blue-800">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="radio" name="TypeFilterDocuments" :id="`TypeFilterDocument${itmType.label}`" :value="itmType.value" :checked="itmType.checked" class="sr-only hidden" @change="changeTypeFilter">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="itm-ownerFilterDocumentsDashboard relative">
                    <div class="actOwnerFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-40 rounded-xl border border-[#9a9a9a]">
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p>Owner</p>
                                    </div>
                                </div>
                                <div class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
                                    <div class="icn text-[#6a6a6a] text-xl">
                                        <i class="fas fa-sliders"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
                
                <div class="itm-modifiedFilterDocumentsDashboard relative">
                    <div class="actModifiedFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-40 rounded-xl border border-[#9a9a9a]">
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p>Modified</p>
                                    </div>
                                </div>
                                <div class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
                                    <div class="icn text-[#6a6a6a] text-xl">
                                        <i class="fas fa-sliders"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    
                    <div class="wrapper-modalModifiedFilterDocumentsDashboard absolute top-full left-0 pt-4"
                        {{-- x-show="$filterDocument.type.filterModal"
                        style="display: none"
                        @click.away="$filterDocument.type.filterModal = false" --}}
                        >
                        <div class="ctr-modalModifiedFilterDocumentsDashboard bg-white w-52 py-2 rounded-md shadow-md shadow-black/40">
                            <div class="cModalModifiedFilterDocumentsDashboard space-y-0.5">
                                <template x-for="itmModified in listFilter.modified" :key="itmModified.label">
                                    <div class="itm-modifiedFilterDocuments group">
                                        <label :for="`ModifiedFilterDocument${itmModified.label}`" class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100">
                                            <div class="cModifiedFilterDocument flex items-center justify-between">
                                                <div class="txLblModifiedFilter">
                                                    <div class="txLbl text-sm">
                                                        <p x-text="itmModified.label"></p>
                                                    </div>
                                                </div>
                                                <div class="icnModifiedFilter invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                    <div class="icn text-blue-800">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="radio" name="ModifiedFilterDocuments" :id="`ModifiedFilterDocument${itmModified.label}`" :value="itmModified.value.label" :checked="itmModified.checked" class="sr-only hidden" @change="changeModifiedFilter">
                                    </div>
                                </template>
                                
                                <div class="itm-modifiedFilterDocuments">
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                {{-- <div class="act-checkAllFilterDocumentsDashboard flex items-center h-8">
                    <div class="act-checkAllDocumentsDashboard group">
                        <label for="checkAllFilterDashboard" class="lblActCheckAll size-8 rounded-lg bg-[#D9D9D9] group-has-[:checked]:bg-[#1565C0] block relative cursor-pointer">
                            <div class="halfCheck invisible opacity-0 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                                <div class="icon">
                                    <i class="fas fa-minus"></i>
                                </div>
                            </div>
                            <div class="allCheck invisible opacity-0 text-white group-has-[:checked]:visible group-has-[:checked]:opacity-100 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                                <div class="icon text-lg">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </label>
                        <input type="checkbox" id="checkAllFilterDashboard" class="sr-only hidden" checked>
                    </div>
                    <div class="filter-checkAllDocumentsDashboard relative">
                        <button class="btn-filterCheckAllDocumentsDashboard w-6 h-8 mt-1 relative rounded-md hover:bg-[#D9D9D9]">
                            <div class="icn text-[#3D3D3D] absolute left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2">
                                <i class="fas fa-sort-down"></i>
                            </div>
                        </button>
                        <div class="abs-filterCheckAllDocumentsDashbaord absolute">
                            <div class="cFilterCheckAllDocumentsDashbaord">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="moreAct-documentsDashboard ml-4 flex gap-2">
                    <div class="act-refreshDocumentsDashbaord">
                        <button class="bntAct-refreshDocumentsDashboard block rounded-full hover:bg-[#d9d9d9]">
                            <div class="cBtnAct size-8 flex items-center justify-center">
                                <div class="icn text-xl text-[#3D3D3D]">
                                    <i class="fas fa-rotate-right"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                </div> --}}
            </div>
        </div>
        
        
        <div class="ctr-rghtFilterDocumentsDashboard">
            <div class="cRghtFilterDocumentsDashboard flex items-center gap-4">
                <div class="numOfPaginateDocumentsDashboard text-xs inline-flex gap-1 text-[#7D7D7D]">
                    <div class="minNumActivePaginate">
                        <p>{$num1}</p>
                    </div>
                    - 
                    <div class="maxNumActivePaginate">
                        <p>{$num2}</p>
                    </div>
                    of
                    <div class="ofMaxNumPaginateDocumentsDashboard">
                        <p>${num3}</p>
                    </div>
                </div>
                <div class="act-paginateDocumentsDashboard flex gap-2">
                    <div class="act-leftPaginateDocumentsDashboard">
                        <button class="btnAct-paginateDocumentsDashboard rounded-full hover:bg-[#D9D9D9]">
                            <div class="cBtnAct-paginateDocumentsDashboard size-8  flex items-center justify-center">
                                <div class="icn text-[#3D3D3D]">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="act-rghtPaginateDocumentsDashboard">
                        <button class="btnAct-paginateDocumentsDashboard rounded-full hover:bg-[#D9D9D9]">
                            <div class="cBtnAct-paginateDocumentsDashboard size-8  flex items-center justify-center">
                                <div class="icn text-[#3D3D3D]">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@once
    @push('dashboard-body-script')
        <script data-navigate-once>
            Alpine.data('filterDocument', () => {
                const csrf_token = '{{ csrf_token() }}';
                
                listFilter = {
                    status: [
                        { label: 'All Status', value: 'all', checked: false },
                        { label: 'In Progress', value: 'progress', checked: false },
                        { label: 'Completed', value: 'completed', checked: false },
                        { label: 'Rejected', value: 'rejected', checked: false },
                        { label: 'Draft', value: 'draft', checked: false },
                    ],
                    type: [
                        { label: 'All Type', value: 'all', checked: false },
                        { label: 'Signature', value: 'signature', checked: false },
                        { label: 'Paraf', value: 'paraf', checked: false },
                        { label: 'Uncategorized', value: 'uncategorized', checked: false },
                    ],
                    modified: [
                        { label: 'All Period', 
                            value: {
                                label: 'all',
                                start_date: 'all',
                            }, 
                        checked: false },
                        { label: 'Last 30 days', 
                            value: {
                                label: 'L30D',
                                start_date: 30,
                            }, 
                        checked: false },
                        { label: 'Last 3 months', 
                            value: {
                                label: 'L3M',
                                start_date: 90,
                            }, 
                        checked: false },
                        { label: 'Last 6 months', 
                            value: {
                                label: 'L6M',
                                start_date: 180,
                            }, 
                        checked: false },
                    ]
                };
                
                return {
                    // $filterDocument: [
                    //     { filterName: 'status', filterModal: false, filterText: listStatus[0].label, filterValue: listStatus[0].value },
                    //     { filterName: 'type', filterModal: false, filterText: listType[0].label, filterValue: listType[0].value },
                    //     { filterName: 'owner', filterModal: false, filterText: '', filterValue: '' },
                    //     // { filterName: 'modified', filterModal: false, filterText: '', filterValue: '' },
                    // ],
                    
                    $filterDocument: {
                        status: { filterModal: false, filterText: listFilter.status[0].label, filterValue: listFilter.status[0].value },
                        type: { filterModal: false, filterText: listFilter.type[0].label, filterValue: listFilter.type[0].value },
                        owner: { filterModal: false, filterText: '', filterValue: '' },
                        modified: { filterModal: false, filterText: listFilter.modified[0].label, 
                            filterValue: {
                                label: listFilter.modified[0].value.label,
                                start_date: '',
                                end_date: '',
                            } 
                        },
                    },
                    
                    init() {
                        $paramQueryUrlAll = ['status', 'type'];
                        
                        $paramQueryUrlAll.forEach(($val) => {
                            if (isParamQueryExists($val)) {
                                $valParamQuery = whatParamQueryValue($val);
                                
                                if(listFilter[$val].some(x => x.value == $valParamQuery)) {
                                    $filterSelect = listFilter[$val].find(x => x.value == $valParamQuery);
                                    $filterSelect.checked = true;
                                    
                                    this.$filterDocument[$val].filterText = $filterSelect.label;
                                    this.$filterDocument[$val].filterValue = $filterSelect.value;
                                } else {
                                    this.$filterDocument[$val].filterText = listFilter[$val][0].label;
                                    this.$filterDocument[$val].filterValue = listFilter[$val][0].label;
                                    removeParamsQuery($val);
                                }
                            } else {
                                listFilter[$val][0].checked = true;
                            }
                            
                        });
                        
                        window.addEventListener('Alpine-Init-Filter-Document', ($val) => {
                            this.dispatchDataFilter();
                        });
                    },
                    
                    changeStatusFilter(event) {
                        const valInp = event.target.value;
                        console.log(valInp);
                        
                        if(listFilter.status.some(x => x.value == valInp)) {
                            findStatus = listFilter.status.find(x => x.value == valInp);
                            this.$filterDocument.status.filterText = findStatus.label;
                            this.$filterDocument.status.filterValue = findStatus.value;
                            // this.$statusFilter = findStatus.label;
                            
                            if (findStatus.value == 'all') {
                                removeParamsQuery('status');
                            } else {
                                setParamsQuery('status', findStatus.value);
                            }
                        } else {
                            this.$filterDocument.status.filterText = listFilter.status[0].label;
                            this.$filterDocument.status.filterValue = listFilter.status[0].value;
                            // this.$statusFilter = listStatus[0].label;
                            removeParamsQuery('status');
                        }
                        
                        this.dispatchDataFilter();
                        
                    },
                    
                    changeTypeFilter(event) {
                        const valInp = event.target.value;
                        console.log(valInp);
                        
                        if (listFilter.type.some(x => x.value == valInp)) {
                            findType = listFilter.type.find(x => x.value == valInp);
                            this.$filterDocument.type.filterText = findType.label;
                            this.$filterDocument.type.filterValue = findType.value;
                            // this.$typeFilter = findType.label;
                            
                            if (findType.value == 'all') {
                                removeParamsQuery('type');
                            } else {
                                setParamsQuery('type', findType.value);
                            }
                        } else {
                            this.$filterDocument.type.filterText = listFilter.type[0].label;
                            this.$filterDocument.type.filterValue = listFilter.type[0].value;
                            removeParamsQuery('type');
                        }
                        
                        this.dispatchDataFilter();
                    },
                    
                    changeModifiedFilter(event) {
                        const valInp = event.target.value;
                        if (listFilter.modified.some(x => x.value.label == valInp) && (valInp != 'all')) {
                            findModified = listFilter.modified.find(x => x.value.label == valInp);
                            
                            const now = new Date();
                            const start = new Date();
                            start.setDate(start.getDate() - findModified.value.start_date);
                            
                            $formatStart = this.formateDate(start);
                            $formatNow = this.formateDate(now);
                            
                            this.$filterDocument.modified.filterValue.start_date = $formatStart;
                            this.$filterDocument.modified.filterValue.end_date = $formatNow;
                            
                            setParamsQuery('modifiedtype', valInp);
                            setParamsQuery('start_date', $formatStart);
                            setParamsQuery('end_date', $formatNow);
                        }
                        
                        
                        
                        // const nowFormat = new Intl.DateTimeFormat('en')

                        // console.log(now.toISOString());
                        // console.log(start.setDate(start.getDate() - 30));
                        
                        // console.log(this.formateDate());
                    },
                    
                    formateDate($time = new Date()) {
                        const now = new Date($time);
                        
                        const day = String(now.getDate()).padStart(2, '0');
                        const month = String(now.getMonth() + 1).padStart(2, '0'); // Ingat: bulan dimulai dari 0
                        const year = now.getFullYear();
                        
                        const formattedDate = `${day}-${month}-${year}`;
                        return formattedDate;
                        // console.log(formattedDate); // contoh: "01-06-2025"
                    },
                    
                    dispatchDataFilter() {
                        $dispatchingData = Object.assign({}, this.$filterDocument);
                        Object.assign($dispatchingData, {_token: csrf_token});
                        console.log($dispatchingData);
                        
                        dispatchingDataTo('Document-Filter-Data', $dispatchingData);
                    },
                    
                    
                    
                };
            });
            
            
            Livewire.hook('component.init', (component, cleanup) => {
                console.log({
                    // LHookMessage: message,
                    LHookComponent: component,
                    LHookCleanup: cleanup,
                });
            })
            
        </script>
    @endpush
@endonce