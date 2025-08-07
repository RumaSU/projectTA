@php
    $listFilterDocuments = [
        (object) [
            'title' => 'status',
            'alpine' => (object) [
                'a_data' => 'filter_statusDocument',
            ],
            'filter' => [
                (object) ['label' => 'All Status', 'value' => 'all', 'default' => true, 'checked' => true],
                (object) ['label' => 'In Progress', 'value' => 'progress', 'default' => false, 'checked' => false],
                (object) ['label' => 'Completed', 'value' => 'completed', 'default' => false, 'checked' => false],
                (object) ['label' => 'Rejected', 'value' => 'rejected', 'default' => false, 'checked' => false],
                (object) ['label' => 'Draft', 'value' => 'draft', 'default' => false, 'checked' => false],
            ],
        ],
        (object) [
            'title' => 'type',
            'alpine' => (object) [
                'a_data' => 'filter_typeDocument',
            ],
            'filter' => [
                (object) ['label' => 'All Type', 'value' => 'all', 'default' => true, 'checked' => true],
                (object) ['label' => 'Signature', 'value' => 'signature', 'default' => false, 'checked' => false],
                (object) ['label' => 'Paraf', 'value' => 'paraf', 'default' => false, 'checked' => false],
                (object) ['label' => 'Uncategorized', 'value' => 'uncategorized', 'default' => false, 'checked' => false],
            ],
        ],
    ];
@endphp

<div class="ctr-filterDocumentsDashboard">
    <div class="cFilterDocumentsDashboard flex items-center justify-between">
        <div class="ctr-lftFilterDocumentsDashboard">
            <div class="cLftFilterDocumentsDashboard flex items-start gap-2">
                
                @foreach ($listFilterDocuments as $itmKey => $itmValue)
                
                    {{-- <div class="itm-{{ $itmValue->title }}FilterDocumentsDashboard relative" x-data="{{ $itmValue->alpine->a_data }}"> --}}
                    <div class="itm-{{ $itmValue->title }}FilterDocumentsDashboard relative" x-data="filterItem('{{ $itmValue->title }}')">
                        <div class="act{{ ucfirst($itmValue->title) }}FilterDocumentsDashboard">
                            <button class="btnActFilterDocuments min-w-40 rounded-xl border border-[#9a9a9a]"
                                @click="modalStatus = ! modalStatus"
                                >
                                <div class="cBtnActFilterDocuments flex items-center justify-between gap-2 px-5 py-2 ">
                                    <div class="txLblBtnAct">
                                        <div class="tx text-sm text-[#6a6a6a]">
                                            <p x-text="label">{{ $itmValue->filter[0]->label }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
                                        <div class="icn text-[#6a6a6a] text-xl">
                                            <i class="fas fa-sliders"></i>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        
                        <div class="wrapper-modal{{ ucfirst($itmValue->title) }}FilterDocumentsDashboard absolute top-full left-0 pt-4 z-10"
                            x-show="modalStatus"
                            x-cloak
                            x-transition
                            @click.away="modalStatus = false"
                            
                            >
                            <div
                                class="ctr-modal{{ ucfirst($itmValue->title) }}FilterDocumentsDashboard bg-white w-52 min-h-32 py-2 rounded-md shadow-md shadow-black/40">
                                <div class="cModal{{ ucfirst($itmValue->title) }}FilterDocumentsDashboard space-y-0.5">
                                    
                                    @foreach ($itmValue->filter as $optionFilter)
                                    
                                        <div class="itm-{{ $itmValue->title }}FilterDocuments group">
                                            <label 
                                                for="{{ $itmValue->title }}FilterDocument_val{{ ucfirst($optionFilter->value) }}"
                                                class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100"
                                            >
                                                <div class="c{{ ucfirst($itmValue->title) }}FilterDocument flex items-center justify-between">
                                                    <div class="txLbl{{ ucfirst($itmValue->title) }}Filter">
                                                        <div class="txLbl text-sm">
                                                            <p>{{ $optionFilter->label }}</p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="icn{{ ucfirst($itmValue->title) }}Filter invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                        <div class="icn text-blue-800">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                            <input type="radio" name="{{ $itmValue->title }}FilterDocuments"
                                                id="{{ $itmValue->title }}FilterDocument_val{{ ucfirst($optionFilter->value) }}"
                                                value="{{ $optionFilter->value }}"
                                                {{ $optionFilter->default ? 'checked' : '' }}
                                                class="sr-only hidden"
                                                @change="change"
                                            >    
                                        </div>
                                        
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                {{-- Item owner --}}
                {{-- <div 
                    class="itm-OwnerFilterDocumentsDashboard relative"
                >
                    <div class="actOwnerFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-40 rounded-xl border border-[#9a9a9a]"
                            >
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p
                                         >Owner</p>
                                    </div>
                                </div>
                                <div
                                    class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
                                    <div class="icn text-[#6a6a6a] text-xl">
                                        <i class="fas fa-sliders"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    
                </div> --}}
                
                {{-- Item Modified --}}
                <div class="itm-modifiedFilterDocumentsDashboard relative" 
                    x-data="filterItemModified"
                    @customfiltervalue.window="changeCustom($event)"
                    @closefiltercustom.window="modalCalendar = false"
                    >
                    <div class="actModifiedFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-52 rounded-xl border border-[#9a9a9a]"
                            @click="modalStatus = !modalStatus"
                            >
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p 
                                            x-text="label"
                                            >
                                            All Period</p>
                                    </div>
                                </div>
                                <div
                                    class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
                                    <div class="icn text-[#6a6a6a] text-xl">
                                        <i class="fas fa-sliders"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>

                    <div class="wrapper-modalModifiedFilterDocumentsDashboard absolute top-full left-0 transition-all z-10" 
                        x-show="modalStatus"
                        x-cloak
                        x-transition
                        @click.away="modalStatus = false"
                    >
                        <div
                            class="ctr-modalModifiedFilterDocumentsDashboard bg-white w-52 py-2 rounded-md shadow-md shadow-black/70"
                        >
                            @php
                                $arrDefaultOptionFilterModified = [
                                    ['label' => 'All Period', 'value' => ['label' => 'all', 'start_date' => null], 'default' => true,],
                                    ['label' => 'Last 30 days', 'value' => ['label' => 'L30D', 'start_date' => 30], 'default' => false,],
                                    ['label' => 'Last 3 months', 'value' => ['label' => 'L3M', 'start_date' => 90], 'default' => false,],
                                    ['label' => 'Last 6 months', 'value' => ['label' => 'L6M', 'start_date' => 180], 'default' => false,],
                                ];
                                
                                $optionsFilterModified = json_decode(json_encode($arrDefaultOptionFilterModified));
                            @endphp
                            
                            
                            <div class="cModalModifiedFilterDocumentsDashboard space-y-0.5">
                                @foreach ($optionsFilterModified as $itmOption)
                                    <div class="itm-modifiedFilterDocuments group">
                                        <label 
                                            for="modifiedFilterDocuments{{ strtoupper($itmOption->value->label) }}"
                                            class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100"
                                        >
                                            <div class="cModifiedFilterDocument flex items-center justify-between">
                                                <div class="txLblModifiedFilter">
                                                    <div class="txLbl text-sm">
                                                        <p>{{ $itmOption->label }}</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="icnModifiedFilter invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                    <div class="icn text-blue-800">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="radio" 
                                            name="ModifiedFilterDocuments"
                                            id="modifiedFilterDocuments{{ strtoupper($itmOption->value->label) }}"
                                            value="{{ $itmOption->value->label }}"
                                            {{ $itmOption->default ? 'checked' : '' }}
                                            class="sr-only hidden" 
                                            @change="change">
                                    </div>
                                @endforeach
                                    
                                <div 
                                    class="itm-modifiedFilterDocuments group" 
                                >
                                    <div class="btn-actShowFilterCustomModified">
                                        <button
                                            class="px-4 py-2 block w-full rounded-md cursor-pointer hover:bg-blue-100 group-has-[:checked]:bg-blue-100"
                                            @click="modalCalendar = !modalCalendar"
                                        >
                                            <div
                                                class="cButtonActShowFilterCUstomModified flex items-center justify-between">
                                                <div class="txRangeDateItemModifiedFilter">
                                                    <div class="txRange text-sm">
                                                        <p>Custom Range</p>
                                                    </div>
                                                </div>
                                                <div class="icnItemModifiedFilter">
                                                    <div class="icn text-slate-700 group-has-[:checked]:text-blue-700">
                                                        <i class="fas fa-calendar-days"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="ctr-rangeDateSelectText">
                                        <div class="cRangeDateSelectText px-2 py-0.5 space-y-0.5">
                                            <div 
                                                class="startRangeDateSelect items-center px-4 py-0.5 bg-blue-600 rounded-md"
                                                style="display: none"
                                                :style="customDate.start_date ? 'display: flex' : 'display: none'"
                                                >
                                                <div class="icon text-white/80 shrink-0 size-6">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <div class="tx text-sm text-white/80">
                                                    <p x-text="customDate.start_date">Start date</p>
                                                </div>
                                            </div>
                                            <div 
                                                class="endRangeDateSelect items-center px-4 py-0.5 bg-yellow-600 rounded-md"
                                                style="display: none"
                                                :style="customDate.end_date ? 'display: flex' : 'display: none'"
                                                >
                                                <div class="icon text-white/80 shrink-0 size-6">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <div class="tx text-sm text-white/80">
                                                    <p x-text="customDate.end_date">End date</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="inpCheckboxStatueRangeDateCustom sr-only">
                                        <input 
                                            type="radio"
                                            name="ModifiedFilterDocuments"
                                            id="id_inpCheckboxStatueRangeDateCustom"
                                            value="custom"
                                            {{-- :checked="valueCustomFilter.start_date || valueCustomFilter.end_date ? 'checked' : ''" --}}
                                        >
                                    </div>
                                    
                                    <div class="wrapper-absolute absolute top-0 pl-2 left-full border border-black transition-all"
                                        x-show="modalCalendar"
                                        x-cloak
                                        x-transition
                                        @click.away="modalCalendar = false"
                                        >
                                        <div class="calendarField bg-white shadow-md shadow-black/40 p-2 rounded-lg">
                                            <div 
                                                id="custom-filter-pick-calendar"
                                                {{-- id="pick-calendar-by-div"  --}}
                                                data-type-as="date" 
                                                data-select-mode="multiple-ranged" 
                                                class="border border-black">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
            const hierarchyParam = ['status', 'type', 'owner', 'modified', 'start_date', 'end_date'];
            function getFilterDefaultValue(filterKey) {
                const allFilters = {
                    status: {!! json_encode($listFilterDocuments[0]->filter) !!},
                    type: {!! json_encode($listFilterDocuments[1]->filter) !!},
                    modified: {!! json_encode($optionsFilterModified) !!},
                };
                
                const values = allFilters[filterKey] || [];
                return {
                    list: values,
                    default: values.find(x => x.default)
                };
            }
            
            Alpine.store('filterDocumentStore', {
                filters: {
                    status: getFilterDefaultValue('status').default.value,
                    type: getFilterDefaultValue('type').default.value,
                    modified: {
                        label: getFilterDefaultValue('modified').default.value.label,
                        start_date: null,
                        end_date: null
                    }
                },
                
                init() {
                    this.initializeFromParams();
                    window.addEventListener('alpineinitfilterdocument', () => this.dispatchData(), { once: true });
                },
                
                set(key, value) {
                    if (key === 'modified') {
                        Object.assign(this.filters.modified, value);
                    } else {
                        this.filters[key] = value;
                    }
                },
                
                setModified(label, start_date, end_date) {
                    if (! (label || start_date || end_date) ) return;
                    
                    this.filter.modified.label = label;
                    this.filter.modified.start_date = start_date;
                    this.filter.modified.end_date = end_date;
                },
                
                initializeFromParams() {
                    const baseFilters = ['status', 'type'];
                    for (const param of baseFilters) {
                        const val = paramValue(param);
                        const { list, default: def } = getFilterDefaultValue(param);
                        
                        if (val && val !== def.value && list.some(x => x.value === val)) {
                            this.set(param, val);
                        }
                    }
                    
                    const modifiedVal = paramValue('modified');
                     if (modifiedVal === 'custom') {
                        const [start, end] = ['start_date', 'end_date']
                            .map(paramValue)
                            .filter(val => val && dayjs(val).isValid());
                        
                        if (start) {
                            let startDate = start;
                            let endDate = end || start;
                            
                            if (dayjs(startDate).isAfter(dayjs(endDate))) {
                                [startDate, endDate] = [endDate, startDate];
                            }
                            
                            const now = dayjs().format('YYYY-MM-DD');
                            endDate = dayjs(endDate).isAfter(dayjs()) ? now : endDate;
                            
                            this.set('modified', {
                                label: 'custom',
                                start_date: startDate,
                                end_date: endDate,
                            });
                        }
                    } else {
                        const { list } = getFilterDefaultValue('modified');
                        const match = list.find(x => x.value.label === modifiedVal);
                        if (match) {
                            const d = dayjs();
                            const start = d.subtract(match.value.start_date, 'day').format('YYYY-MM-DD');
                            const end = d.format('YYYY-MM-DD');
                            this.set('modified', { label: match.value.label, start_date: start, end_date: end });
                        }
                    }
                    
                    this.updateParam();
                },
                
                updateParam(isPushstate = false) {
                    const def = {
                        status: getFilterDefaultValue('status').default.value,
                        type: getFilterDefaultValue('type').default.value,
                        modified: getFilterDefaultValue('modified').default.value.label,
                    };
                    
                    const p = this.filters;
                    const params = [
                        { key: 'status', value: p.status === def.status ? null : p.status },
                        { key: 'type', value: p.type === def.type ? null : p.type },
                        { key: 'modified', value: (p.modified.label === def.modified) ? null : p.modified.label },
                        { key: 'start_date', value: (p.modified.label === def.modified) ? null : p.modified.start_date },
                        { key: 'end_date', value: (p.modified.label === def.modified) ? null : p.modified.end_date }
                    ];

                    paramSetHierarchy(['status', 'type', 'owner', 'modified', 'start_date', 'end_date'], params, isPushstate);
                },
                
                dispatchData() {
                    const data = {
                        filter_status: this.filters.status,
                        filter_type: this.filters.type,
                        filter_modified: { ...this.filters.modified },
                        _token: $jq('meta[name="csrf-token"]').attr('content'),
                    };
                    
                    dispatchingDataLivewireTo('Document-Filter-Data', data);
                }
            });
            
            Alpine.data('filterItem', (filterKey) => {
                const { list, default: def } = getFilterDefaultValue(filterKey);
                
                return {
                    selected: def.value,
                    label: def.label,
                    modalStatus: false,
                    
                    init() {
                        const storeVal = this.$store.filterDocumentStore.filters[filterKey];
                        const found = list.find(x => x.value == storeVal);
                        
                        this.selected = found.value;
                        this.label = found.label;
                        
                        const $root = $jq(this.$root);
                        $root.find(`input[type="radio"][value="${this.selected}"]`)
                            .prop('checked', true);
                    },
                    
                    change($e) {
                        const val = $jq($e.currentTarget).val();
                        const match = list.find(x => x.value === val);
                        if (!match) return this.resetToDefault(true);
                        
                        if (this.selected === match.value) return;
                        
                        this.selected = match.value;
                        this.label = match.label;
                        
                        this.$store.filterDocumentStore.set(filterKey, match.value);
                        this.$store.filterDocumentStore.updateParam();
                        this.$store.filterDocumentStore.dispatchData();
                    },
                    
                    resetToDefault(notify = false) {
                        this.selected = def.value;
                        this.label = def.label;
                        
                        this.$store.filterDocumentStore.set(filterKey, def.value);
                        this.$store.filterDocumentStore.updateParam();
                        this.$store.filterDocumentStore.dispatchData();
                        
                        if (notify) {
                            this.$dispatch('customnotify', {
                                variant: 'danger',
                                title: 'Oops!',
                                message: 'Filter value invalid. Reset to default.',
                            });
                        }
                    },
                }
            });
            
            Alpine.data('filterItemModified', () => {
                const filterKey = 'modified';
                const { list, default: def } = getFilterDefaultValue(filterKey);
                
                return {
                    selected: def.value.label,
                    label: def.label,
                    modalStatus: false,
                    modalCalendar: false,
                    
                    customDate: {start_date: null, end_date: null,},
                    calendarDate: null,
                    
                    init() {
                        
                        const storeVal = this.$store.filterDocumentStore.filters[filterKey];
                        this.selected = storeVal.label;
                        
                        if (storeVal.label == 'custom') {
                            this.label = 'Custom Range';
                            this.customDate.start_date = storeVal.start_date;
                            this.customDate.end_date = storeVal.end_date;
                        } else {
                            const found = list.find(x => x.value.label == storeVal.label);
                            this.label = found.label;
                        }
                        
                        this.initCustomFilter();
                        
                        const $root = $jq(this.$root);
                        $root.find(`input[type="radio"][value="${this.selected}"]`).prop('checked', true);
                    },
                    
                    change($e) {
                        const val = $jq($e.currentTarget).val();
                        const match = list.find(x => x.value.label === val);
                        if (!match) return this.resetToDefault(true);
                        
                        if (this.selected === match.value.label) return;
                        
                        this.selected = match.value.label;
                        this.label = match.label;
                        this.customDate.start_date = null;
                        this.customDate.end_date = null;
                        this.calendarDate.update({
                            dates: true,
                            selectedDates: [null, null],
                        });
                        this.calendarDate.selectedDates = [null, null];
                        this.modalCalendar = false;
                        
                        const d = dayjs();
                        const start = d.subtract(match.value.start_date, 'day').format('YYYY-MM-DD');
                        const end = d.format('YYYY-MM-DD');
                        
                        const valueChange = {
                            label: this.selected,
                            start_date: start,
                            end_date: end,
                        }
                        
                        this.$store.filterDocumentStore.set(filterKey, valueChange);
                        this.$store.filterDocumentStore.updateParam();
                        this.$store.filterDocumentStore.dispatchData();
                    },
                    
                    changeCustom(event) {
                        
                        console.log(event);
                        const detail = event?.detail;
                        if (! detail) return;
                        
                        const values = event.detail.value;
                        if (! values && !values.length > 1) return;
                        
                        const [start, end] = values
                            .filter(val => val && dayjs(val).isValid());
                        
                        if (start) {
                            let startDate = start;
                            let endDate = end || start;
                            
                            if (dayjs(startDate).isAfter(dayjs(endDate))) {
                                [startDate, endDate] = [endDate, startDate];
                            }
                            
                            const now = dayjs().format('YYYY-MM-DD');
                            endDate = dayjs(endDate).isAfter(dayjs()) ? now : endDate;
                            
                            const valueChange = {
                                label: 'custom',
                                start_date: startDate,
                                end_date: endDate,
                            };
                            
                            this.selected = 'custom';
                            this.label = 'Custom Range'
                            this.customDate.start_date = startDate;
                            this.customDate.end_date = endDate;
                            this.modalCalendar = false;
                            
                            const $root = $jq(this.$root);
                            $root.find(`input[type="radio"][value="${this.selected}"]`).prop('checked', true);
                            
                            this.$store.filterDocumentStore.set(filterKey, valueChange);
                            this.$store.filterDocumentStore.updateParam();
                            this.$store.filterDocumentStore.dispatchData();
                            return;
                        }
                        
                        this.resetToDefault();
                    },
                    
                    resetToDefault(notify = false) {
                        this.selected = def.value;
                        this.label = def.label;
                        this.customDate.start_date = null;
                        this.customDate.end_date = null;
                        this.calendarDate.update({
                            dates: true,
                            selectedDates: [null, null],
                        });
                        this.calendarDate.selectedDates = [null, null];
                        
                        const valueChange = {
                            label: this.selected,
                            start_date: null,
                            end_date: null,
                        }
                        
                        this.$store.filterDocumentStore.set(filterKey, valueChange);
                        this.$store.filterDocumentStore.updateParam();
                        this.$store.filterDocumentStore.dispatchData();
                        
                        if (notify) {
                            this.$dispatch('customnotify', {
                                variant: 'danger',
                                title: 'Oops!',
                                message: 'Filter value invalid. Reset to default.',
                            });
                        }
                    },
                    
                    initCustomFilter() {
                        const root = this.$root;
                        const $root = $jq(root);
                        const idCalendar = 'custom-filter-pick-calendar';
                        
                        const now = dayjs();
                        let selected = Object.values(this.customDate);
                        
                        selected = selected.filter(date => {
                            const d = dayjs(date);
                            return d.isValid() && !d.isAfter(now.toDate());
                        });
                        
                        const mode = 'multiple-ranged' // 'single', 'multiple', 'multiple-ranged'
                        const input = false;
                        const theme = 'light';
                        
                        // const calendarInput = new Calendar(`#${idCalendar}`, {
                        this.calendarDate = new Calendar(`#${idCalendar}`, {
                            dateMax: now.toDate(),
                            dateToday: now.toDate(),
                            positionToInput: ['center', 'left'],
                            selectedTheme: theme,
                            selectedDates: selected,
                            selectedWeekends: [-1],
                            selectionDatesMode: mode,
                            inputMode: input,
                            
                            onClickDate(self, event) {
                                const ctx = self.context;
                                const $vc = $jq(ctx.mainElement);
                                const $buttons = $vc.find('[data-set-range-type]');
                                
                                $buttons.removeAttr('disabled');
                                
                                const selected = ctx.selectedDates.filter(Boolean);
                                
                                // Event klik tombol
                                $buttons.off('click').on('click', function () {
                                    const $this = $jq(this);
                                    const type = $this.attr('data-set-range-type');
                                    
                                    if (! (type == 'close' || type == 'apply')) console.warn('...');
                                    if (type == 'close') {
                                        window.dispatchEvent(new Event('closefiltercustom'));
                                    };
                                    
                                    const newCustomFilter = new CustomEvent('customfiltervalue', {
                                        detail: {value: selected},
                                        bubbles: true,
                                    });
                                    
                                    window.dispatchEvent(newCustomFilter);
                                    
                                    $this.prop('disabled', true);
                                });
                                
                                
                            },
                            
                            onInit(self) {
                                const ctx = self.context;
                                const $vc = $jq(ctx.mainElement);
                                const $buttons = $vc.find('[data-set-range-type]');
                                
                                $buttons.on('click', function() {
                                    const $this = $jq(this);
                                    const type = $this.attr('data-set-range-type');
                                    if (! (type == 'close' || type == 'apply')) console.warn('...');
                                    if (type == 'close') {
                                        window.dispatchEvent(new Event('closefiltercustom'));
                                    }
                                });
                            },
                            
                            layouts: {
                                default: `
                                    <div class="vc-custom-header flex items-center pb-1 mb-1 justify-between border-b border-white">
                                        <div class="mainHeader font-semibold text-sm">
                                            <p>Select Date Range</p>
                                        </div>
                                        <div class="actionHeader group/buttonActionVC relative">
                                            <button 
                                                class="size-8 rounded-full hover:bg-black/35 hover:contrast-200"
                                                type="button"
                                                data-set-range-type="close"
                                            >
                                                <div class="mainAction">
                                                    <div class="icon text-sm">
                                                        <i class="fas fa-x"></i>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="vc-header" data-vc="header" role="toolbar" aria-label="Calendar Navigation">
                                        <#ArrowPrev [month] />
                                        <div class="vc-header__content" data-vc-header="content">
                                            <#Month /> | <#Year />
                                        </div>
                                        <#ArrowNext [month] />
                                    </div>
                                    <div class="vc-wrapper" data-vc="wrapper">
                                        <#WeekNumbers />
                                        <div class="vc-content" data-vc="content">
                                            <#Week />
                                            <#Dates />
                                            <#DateRangeTooltip />
                                        </div>
                                    </div>
                                    <#ControlTime />
                                    
                                    <div class="vc-custom-action space-y-1 mt-2">
                                        <div class="btnApply group/buttonActionVC flex-grow">
                                            <button 
                                                class="px-4 py-2 w-full rounded-lg bg-blue-600 [&:not(:disabled)]:hover:contrast-200"
                                                type="button"
                                                data-set-range-type="apply"
                                                disabled
                                            >
                                                <div class="cBtnApply">
                                                    <div class="txApply text-white">
                                                        <div class="tx text-xs">
                                                            <p>Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                    
                                `,
                            },
                            
                        });
                        
                        this.calendarDate.init();  
                    },
                }
            });
            
        </script>
        
        
        <script data-navigate-once>
            
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