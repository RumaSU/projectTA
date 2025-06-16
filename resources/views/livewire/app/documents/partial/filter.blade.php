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
                            ],
                        ],
                        (object) [
                            'title' => 'type',
                            'alpine_data' => '',
                            'filter' => [
                                (object) ['label' => 'All Type', 'value' => 'all'],
                                (object) ['label' => 'Signature', 'value' => 'signature'],
                                (object) ['label' => 'Paraf', 'value' => 'paraf'],
                                (object) ['label' => 'Uncategorized', 'value' => 'uncategorized'],
                            ],
                        ],
                        (object) [
                            'title' => 'type',
                            'alpine_data' => '',
                            'filter' => [
                                (object) ['label' => 'All Type', 'value' => 'all'],
                                (object) ['label' => 'Signature', 'value' => 'signature'],
                                (object) ['label' => 'Paraf', 'value' => 'paraf'],
                                (object) ['label' => 'Uncategorized', 'value' => 'uncategorized'],
                            ],
                        ],
                    ];
                @endphp

                <div class="itm-statusFilterDocumentsDashboard relative">
                    <div class="actStatusFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-40 rounded-xl border border-[#9a9a9a]"
                            @click="$filterDocument.status.filterModal = !$filterDocument.status.filterModal">
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p x-text="$filterDocument.status.filterText">All Status</p>
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

                    <div class="wrapper-modalStatusFilterDocumentsDashboard absolute top-full left-0 pt-4"
                        x-show="$filterDocument.status.filterModal" style="display: none"
                        @click.away="$filterDocument.status.filterModal = false">
                        <div
                            class="ctr-modalStatusFilterDocumentsDashboard bg-white w-52 py-2 rounded-md shadow-md shadow-black/40">
                            <div class="cModalStatusFilterDocumentsDashboard space-y-0.5">
                                <template x-for="itmStatus in listFilter.status" :key="itmStatus . label">
                                    <div class="itm-statusFilterDocuments group">
                                        <label :for="`statusFilterDocument${itmStatus . label}`"
                                            class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100">
                                            <div class="cStatusFilterDocument flex items-center justify-between">
                                                <div class="txLblStatusFilter">
                                                    <div class="txLbl text-sm">
                                                        <p x-text="itmStatus.label"></p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="icnStatusFilter invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                    <div class="icn text-blue-800">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="radio" name="statusFilterDocuments"
                                            :id="`statusFilterDocument${itmStatus . label}`" :value="itmStatus . value"
                                            :checked="itmStatus . checked" class="sr-only hidden"
                                            @change="changeStatusFilter">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="itm-typeFilterDocumentsDashboard relative">
                    <div class="actTypeFilterDocumentsDashboard">
                        <button class="btnActFilterDocuments w-40 rounded-xl border border-[#9a9a9a]"
                            @click="$filterDocument.type.filterModal = !$filterDocument.type.filterModal">
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p x-text="$filterDocument.type.filterText">All Type</p>
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
                    <div class="wrapper-modalTypeFilterDocumentsDashboard absolute top-full left-0 pt-4"
                        x-show="$filterDocument.type.filterModal" style="display: none"
                        @click.away="$filterDocument.type.filterModal = false">
                        <div
                            class="ctr-modalTypeFilterDocumentsDashboard bg-white w-52 py-2 rounded-md shadow-md shadow-black/40">
                            <div class="cModalTypeFilterDocumentsDashboard space-y-0.5">
                                <template x-for="itmType in listFilter.type" :key="itmType . label">
                                    <div class="itm-TypeFilterDocuments group">
                                        <label :for="`TypeFilterDocument${itmType . label}`"
                                            class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100">
                                            <div class="cTypeFilterDocument flex items-center justify-between">
                                                <div class="txLblTypeFilter">
                                                    <div class="txLbl text-sm">
                                                        <p x-text="itmType.label"></p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="icnTypeFilter invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                    <div class="icn text-blue-800">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="radio" name="TypeFilterDocuments"
                                            :id="`TypeFilterDocument${itmType . label}`" :value="itmType . value"
                                            :checked="itmType . checked" class="sr-only hidden"
                                            @change="changeTypeFilter">
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
                                <div
                                    class="icnBtnAct size-8 flex items-center justify-center rounded-md hover:bg-[#D9D9D9]">
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
                        <button class="btnActFilterDocuments w-52 rounded-xl border border-[#9a9a9a]"
                            @click="$filterDocument.modified.filterModal = !$filterDocument.modified.filterModal">
                            <div class="cBtnActFilterDocuments flex items-center justify-between px-5 py-2 ">
                                <div class="txLblBtnAct">
                                    <div class="tx text-sm text-[#6a6a6a]">
                                        <p 
                                            x-text="
                                                $filterDocument.modified.filterStatus ? $filterDocument.modified.filterText : 
                                                $filterDocument.customModified.filterStatus ? $filterDocument.customModified.filterText :
                                                'All Period'
                                                "
                                            
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

                    <div class="wrapper-modalModifiedFilterDocumentsDashboard absolute top-full left-0 pt-4" {{--
                        x-show="$filterDocument.modified.filterModal" style="display: none"
                        @click.away="$filterDocument.modified.filterModal = false" --}}>
                        <div
                            class="ctr-modalModifiedFilterDocumentsDashboard bg-white w-52 py-2 rounded-md shadow-md shadow-black/40">
                            <div class="cModalModifiedFilterDocumentsDashboard space-y-0.5">
                                <template x-for="itmModified in listFilter.modified" :key="itmModified . label">
                                    <div class="itm-modifiedFilterDocuments group">
                                        <label :for="`ModifiedFilterDocument${itmModified . label}`"
                                            class="px-4 py-2 block rounded-md group-has-[:checked]:bg-blue-100 cursor-pointer hover:bg-blue-100">
                                            <div class="cModifiedFilterDocument flex items-center justify-between">
                                                <div class="txLblModifiedFilter">
                                                    <div class="txLbl text-sm">
                                                        <p x-text="itmModified.label"></p>
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
                                        <input type="radio" name="ModifiedFilterDocuments"
                                            :id="`ModifiedFilterDocument${itmModified . label}`"
                                            :value="itmModified . value . label" :checked="itmModified . checked"
                                            class="sr-only hidden" @change="changeModifiedFilter">
                                    </div>
                                </template>

                                <div class="itm-modifiedFilterDocuments group relative">

                                    <div class="act-btnCustomModifiedFilterDocuments">
                                        <button
                                            class="btnCustomModifiedFilterDocuments w-full px-4 py-2 block rounded-md cursor-pointer hover:bg-blue-100"
                                            @click="viewModalCustomModifiedFilter"
                                            {{-- @click="$filterDocument.customModified.filterModal = !$filterDocument.customModified.filterModal" --}}
                                            >
                                            <div
                                                class="cBtnCustomModifiedFilterDocuments flex items-center justify-between">
                                                <div class="txLblCustomModified">
                                                    <div class="txLbl text-sm">
                                                        <p>Custom Period</p>
                                                    </div>
                                                </div>
                                                <div class="icnCustomModified invisible opacity-0 ">
                                                    <div class="icn">
                                                        <div class="fas fa-check"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </div>

                                    <div
                                        class="wrapper-modalCustomModifiedFilterDocuments absolute top-0 left-full border border-black"
                                        {{-- x-show="$filterDocument.customModified.filterModal" style="display: none" --}}
                                        {{-- @click.away="viewModalCustomModifiedFilter" --}}
                                        {{-- @click.away="$filterDocument.customModified.filterModal = false" --}}
                                        >
                                        <div
                                            class="ctr-modalCustomModifiedFilterDocuments bg-white w-52 py-2 px-1.5 rounded-md shadow-md shadow-black/40">
                                            <div class="cModalCustomModifiedFilterDocuments">
                                                <div class="titleModalCustomModifiedFilterDocuments">
                                                    <div class="txTitle text-xs font-semibold text-[#a9a9a9]">
                                                        <p>Custom Period (DD-MM-YYYY)</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="lst-customModifiedFilterDocuments mt-1">
                                                    <div class="itm-startDateCustomModifiedFilter">
                                                        <div class="inpStartDateCustomModifiedFilter relative">
                                                            <label for="customModifiedFilterStartDate"
                                                                class="w-full px-4 py-2 block rounded-md cursor-pointer border border-black"
                                                                data-custom-input="customModifiedFilterStartDate"
                                                                {{-- @click="viewCalendarCustomModifiedFilter" --}}
                                                                >
                                                                <div
                                                                    class="cCustomModifiedFilterStartDate flex items-center justify-between">
                                                                    <div class="txLblCustomModifiedFilterStartDate">
                                                                        <div class="txLbl text-sm">
                                                                            <p x-text="$filterDocument.customModified.filterValue.start_date.value">Start date</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="icnCustomModifiedFilter">
                                                                        <div class="icn">
                                                                            <i class="fas fa-calendar-days"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <input 
                                                                type="text" 
                                                                id="customModifiedFilterStartDate" 
                                                                class=""
                                                                placeholder="Start date"
                                                                {{-- :value="$filterDocument.customModified.filterValue.start_date.value"  --}}
                                                                @change="changeCustomModifiedFilter" 
                                                                data-custom-label="start_date"
                                                                readonly
                                                                >
                                                            {{-- <input type="text" id="customModifiedFilterStartDate" class="sr-only border border-black"> --}}
                                                            {{-- <input type="date" id="customModifiedFilterStartDate"> --}}
                                                        </div>
                                                        <div class="ctr-clearCustomDateModifiedFilter mt-0.5">
                                                            <div class="cClearCustomDateModifiedFilter">
                                                                <button 
                                                                    class="btnClearCustomDateModifiedFilter ml-auto mr-0 block"
                                                                    data-clear-custom="start_date"
                                                                    >
                                                                    <div class="cBtnClearCustomDateModifiedFilter">
                                                                        <div class="txClear text-xs text-blue-600 font-semibold">
                                                                            <p>Clear</p>
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="itm-endDateCustomModifiedFilter mt-2">
                                                        <div class="inpEndDateCustomModifiedFilter">
                                                            <label for="customModifiedFilterEndDate"
                                                                {{-- id="idBtnCustomModifiedFilterEndDate" --}}
                                                                class="w-full px-4 py-2 block rounded-md cursor-pointer border border-black"
                                                                data-custom-input="customModifiedFilterEndDate" 
                                                                {{-- @click="viewCalendarCustomModifiedFilter" --}}
                                                                >
                                                                <div
                                                                    class="cCustomModifiedFilterEndDate flex items-center justify-between">
                                                                    <div class="txLblCustomModifiedFilterEndDate">
                                                                        <div class="txLbl text-sm">
                                                                            <p 
                                                                                x-text="$filterDocument.customModified.filterValue.end_date.value"
                                                                                >
                                                                                End date</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="icnCustomModifiedFilter">
                                                                        <div class="icn">
                                                                            <i class="fas fa-calendar-days"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <input 
                                                                type="text" 
                                                                id="customModifiedFilterEndDate" 
                                                                class=""
                                                                placeholder="End date" 
                                                                {{-- :value="$filterDocument.customModified.filterValue.end_date.value"  --}}
                                                                @change="changeCustomModifiedFilter" 
                                                                data-custom-label="end_date"
                                                                readonly
                                                                >
                                                            {{-- <input type="text" id="customModifiedFilterEndDate" class="sr-only"> --}}
                                                            {{-- <input type="date" id="customModifiedFilterEndDate"> --}}
                                                        </div>
                                                        <div class="ctr-clearCustomDateModifiedFilter mt-0.5">
                                                            <div class="cClearCustomDateModifiedFilter">
                                                                <button 
                                                                    class="btnClearCustomDateModifiedFilter ml-auto mr-0 block"
                                                                    data-clear-custom="end_date"
                                                                    >
                                                                    <div class="cBtnClearCustomDateModifiedFilter">
                                                                        <div class="txClear text-xs text-blue-600 font-semibold">
                                                                            <p>Clear</p>
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- <div class="item-testFlatpickrStart">
                                                        <input type="text" id="testFlatpickrStart" placeholder="Start date">
                                                    </div>
                                                    <div class="item-testFlatpickrEnd">
                                                        <input type="text" id="testFlatpickrEnd" placeholder="end date">
                                                    </div>
                                                    
                                                    <div class="item-testInput">
                                                        <input type="text" name="" id="" class="border border-black">
                                                    </div> --}}
                                                    
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

{{-- <div class="flatpickr-calendar animate open arrowBottom arrowLeft" tabindex="-1"
    style="top: 317px; left: 1160.23px; right: auto;">
    <div class="flatpickr-months"><span class="flatpickr-prev-month"><svg version="1.1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 17 17">
                <g></g>
                <path d="M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z"></path>
            </svg></span>
        <div class="flatpickr-month">
            <div class="flatpickr-current-month"><select class="flatpickr-monthDropdown-months" aria-label="Month"
                    tabindex="-1">
                    <option class="flatpickr-monthDropdown-month" value="0" tabindex="-1">January</option>
                    <option class="flatpickr-monthDropdown-month" value="1" tabindex="-1">February</option>
                    <option class="flatpickr-monthDropdown-month" value="2" tabindex="-1">March</option>
                    <option class="flatpickr-monthDropdown-month" value="3" tabindex="-1">April</option>
                    <option class="flatpickr-monthDropdown-month" value="4" tabindex="-1">May</option>
                    <option class="flatpickr-monthDropdown-month" value="5" tabindex="-1">June</option>
                </select>
                <div class="numInputWrapper"><input class="numInput cur-year" type="number" tabindex="-1"
                        aria-label="Year" max="2025"><span class="arrowUp"></span><span class="arrowDown"></span></div>
            </div>
        </div><span class="flatpickr-next-month flatpickr-disabled"><svg version="1.1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 17 17">
                <g></g>
                <path d="M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z">
                </path>
            </svg></span>
    </div>
    <div class="flatpickr-innerContainer">
        <div class="flatpickr-rContainer">
            <div class="flatpickr-weekdays">
                <div class="flatpickr-weekdaycontainer">
                    <span class="flatpickr-weekday">
                        Sun</span><span class="flatpickr-weekday">Mon</span><span
                        class="flatpickr-weekday">Tue</span><span class="flatpickr-weekday">Wed</span><span
                        class="flatpickr-weekday">Thu</span><span class="flatpickr-weekday">Fri</span><span
                        class="flatpickr-weekday">Sat
                    </span>
                </div>
            </div>
            <div class="flatpickr-days" tabindex="-1">
                <div class="dayContainer"><span class="flatpickr-day" aria-label="June 1, 2025"
                        tabindex="-1">1</span><span class="flatpickr-day" aria-label="June 2, 2025"
                        tabindex="-1">2</span><span class="flatpickr-day" aria-label="June 3, 2025"
                        tabindex="-1">3</span><span class="flatpickr-day today" aria-label="June 4, 2025"
                        aria-current="date" tabindex="-1">4</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 5, 2025">5</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 6, 2025">6</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 7, 2025">7</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 8, 2025">8</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 9, 2025">9</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 10, 2025">10</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 11, 2025">11</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 12, 2025">12</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 13, 2025">13</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 14, 2025">14</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 15, 2025">15</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 16, 2025">16</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 17, 2025">17</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 18, 2025">18</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 19, 2025">19</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 20, 2025">20</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 21, 2025">21</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 22, 2025">22</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 23, 2025">23</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 24, 2025">24</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 25, 2025">25</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 26, 2025">26</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 27, 2025">27</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 28, 2025">28</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 29, 2025">29</span><span class="flatpickr-day flatpickr-disabled"
                        aria-label="June 30, 2025">30</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 1, 2025">1</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 2, 2025">2</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 3, 2025">3</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 4, 2025">4</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 5, 2025">5</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 6, 2025">6</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 7, 2025">7</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 8, 2025">8</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 9, 2025">9</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 10, 2025">10</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 11, 2025">11</span><span class="flatpickr-day nextMonthDay flatpickr-disabled"
                        aria-label="July 12, 2025">12</span>
                </div>
            </div>
        </div>
    </div>
</div> --}}

@once
    @push('dashboard-body-script')
        {{-- <script data-navigate-once>
            $today = new Date();
            $yesterday = new Date();
            $yesterday.setDate($yesterday.getDate() - 1);
            $jq('#customModifiedFilterStartDate').flatpickr({
                dateFormat: 'd-m-Y',
                maxDate: $today,
                onReady: function (selectedDates, dateStr, instance) {
                    let selectClassInstance = instance.calendarContainer.className;
                    selectClassInstance = selectClassInstance.split(' ')[0];

                    const jqClassInstance = $jq(`.${selectClassInstance}`);
                    jqClassInstance.addClass([
                        // 'bg-blue-100'
                    ]);
                    
                    jqClassInstance.find('.flatpickr-month select').addClass('text-sm');
                    jqClassInstance.find('.flatpickr-monthDropdown-months option').addClass('text-sm');

                    console.log(instance.calendarContainer.className);
                    console.log(selectClassInstance);
                    console.log($jq(`.${selectClassInstance}`));
                }
            });

            $jq('#customModifiedFilterEndDate').flatpickr({
                dateFormat: 'd-m-Y',
                maxDate: $yesterday,
            });
        </script> --}}
        <script data-navigate-once>
            // Alpine.data('filterDocument', () => {
            //     const csrf_token = '{{ csrf_token() }}';

            //     listFilter = {
            //         status: [
            //             { label: 'All Status', value: 'all', checked: false },
            //             { label: 'In Progress', value: 'progress', checked: false },
            //             { label: 'Completed', value: 'completed', checked: false },
            //             { label: 'Rejected', value: 'rejected', checked: false },
            //             { label: 'Draft', value: 'draft', checked: false },
            //         ],
            //         type: [
            //             { label: 'All Type', value: 'all', checked: false },
            //             { label: 'Signature', value: 'signature', checked: false },
            //             { label: 'Paraf', value: 'paraf', checked: false },
            //             { label: 'Uncategorized', value: 'uncategorized', checked: false },
            //         ],
            //         modified: [
            //             { label: 'All Period', 
            //                 value: {
            //                     label: 'all',
            //                     start_date: 'all',
            //                 }, 
            //             checked: false },
            //             { label: 'Last 30 days', 
            //                 value: {
            //                     label: 'L30D',
            //                     start_date: 30,
            //                 }, 
            //             checked: false },
            //             { label: 'Last 3 months', 
            //                 value: {
            //                     label: 'L3M',
            //                     start_date: 90,
            //                 }, 
            //             checked: false },
            //             { label: 'Last 6 months', 
            //                 value: {
            //                     label: 'L6M',
            //                     start_date: 180,
            //                 }, 
            //             checked: false },
            //         ]
            //     };

            //     return {
            //         // $filterDocument: [
            //         //     { filterName: 'status', filterModal: false, filterText: listStatus[0].label, filterValue: listStatus[0].value },
            //         //     { filterName: 'type', filterModal: false, filterText: listType[0].label, filterValue: listType[0].value },
            //         //     { filterName: 'owner', filterModal: false, filterText: '', filterValue: '' },
            //         //     // { filterName: 'modified', filterModal: false, filterText: '', filterValue: '' },
            //         // ],

            //         $filterDocument: {
            //             status: { filterModal: false, filterText: listFilter.status[0].label, filterValue: listFilter.status[0].value },
            //             type: { filterModal: false, filterText: listFilter.type[0].label, filterValue: listFilter.type[0].value },
            //             owner: { filterModal: false, filterText: '', filterValue: '' },
            //             modified: { filterModal: false, filterText: listFilter.modified[0].label, 
            //                 filterValue: {
            //                     label: listFilter.modified[0].value.label,
            //                     start_date: '',
            //                     end_date: '',
            //                 } 
            //             },
            //         },

            //         init() {
            //             this.firstInitDefaultFilter();
            //             this.firstInitModifiedFilter();

            //             window.addEventListener('Alpine-Init-Filter-Document', ($val) => {
            //                 this.dispatchDataFilter();
            //             });
            //         },

            //         changeStatusFilter(event) {
            //             const valInp = event.target.value;

            //             if(listFilter.status.some(x => x.value == valInp)) {
            //                 findStatus = listFilter.status.find(x => x.value == valInp);
            //                 this.$filterDocument.status.filterText = findStatus.label;
            //                 this.$filterDocument.status.filterValue = findStatus.value;

            //                 if (findStatus.value == 'all') {
            //                     removeParamsQuery('status');
            //                 } else {
            //                     setParamsQuery('status', findStatus.value);
            //                 }
            //             } else {
            //                 this.$filterDocument.status.filterText = listFilter.status[0].label;
            //                 this.$filterDocument.status.filterValue = listFilter.status[0].value;
            //                 removeParamsQuery('status');
            //             }
            //             this.dispatchDataFilter();

            //         },

            //         changeTypeFilter(event, isDispatch = true) {
            //             const valInp = event.target.value;

            //             if (listFilter.type.some(x => x.value == valInp)) {
            //                 findType = listFilter.type.find(x => x.value == valInp);
            //                 this.$filterDocument.type.filterText = findType.label;
            //                 this.$filterDocument.type.filterValue = findType.value;

            //                 if (findType.value == 'all') {
            //                     removeParamsQuery('type');
            //                 } else {
            //                     setParamsQuery('type', findType.value);
            //                 }
            //             } else {
            //                 this.$filterDocument.type.filterText = listFilter.type[0].label;
            //                 this.$filterDocument.type.filterValue = listFilter.type[0].value;
            //                 removeParamsQuery('type');
            //             }

            //             this.dispatchDataFilter();
            //         },

            //         changeModifiedFilter(event) {
            //             const valInp = event.target.value;
            //             if (listFilter.modified.some(x => x.value.label == valInp) && (valInp != 'all')) {
            //                 findModified = listFilter.modified.find(x => x.value.label == valInp);

            //                 const now = new Date();
            //                 const start = new Date();
            //                 start.setDate(start.getDate() - findModified.value.start_date);

            //                 $formatStart = this.formateDate(start);
            //                 $formatNow = this.formateDate(now);

            //                 this.$filterDocument.modified.filterText = findModified.label;
            //                 this.$filterDocument.modified.filterValue.start_date = $formatStart;
            //                 this.$filterDocument.modified.filterValue.end_date = $formatNow;

            //                 setParamsQuery('modifiedtype', valInp);
            //                 setParamsQuery('start_date', $formatStart);
            //                 setParamsQuery('end_date', $formatNow);
            //             } else {
            //                 this.$filterDocument.modified.filterText = listFilter.modified[0].label;
            //                 this.$filterDocument.modified.filterValue.start_date = '';
            //                 this.$filterDocument.modified.filterValue.end_date = '';

            //                 removeParamsQuery('modifiedtype');
            //                 removeParamsQuery('start_date');
            //                 removeParamsQuery('end_date');
            //             }

            //             this.dispatchDataFilter();
            //         },

            //         formateDate($time = new Date()) {
            //             const now = new Date($time);

            //             const day = String(now.getDate()).padStart(2, '0');
            //             const month = String(now.getMonth() + 1).padStart(2, '0');
            //             const year = now.getFullYear();

            //             const formattedDate = `${day}-${month}-${year}`;
            //             return formattedDate;
            //         },

            //         dispatchDataFilter() {
            //             $dispatchingData = Object.assign({}, this.$filterDocument);
            //             Object.assign($dispatchingData, {_token: csrf_token});
            //             console.log($dispatchingData);

            //             dispatchingDataLivewireTo('Document-Filter-Data', $dispatchingData);
            //         },

            //         async firstInitDefaultFilter() {
            //             $paramQueryUrlAll = ['status', 'type'];

            //             $paramQueryUrlAll.forEach(($val) => {
            //                 if (isParamQueryExists($val)) {
            //                     $valParamQuery = whatParamQueryValue($val);

            //                     if(listFilter[$val].some(x => x.value == $valParamQuery)) {
            //                         $filterSelect = listFilter[$val].find(x => x.value == $valParamQuery);
            //                         $filterSelect.checked = true;

            //                         this.$filterDocument[$val].filterText = $filterSelect.label;
            //                         this.$filterDocument[$val].filterValue = $filterSelect.value;
            //                     } else {
            //                         this.$filterDocument[$val].filterText = listFilter[$val][0].label;
            //                         this.$filterDocument[$val].filterValue = listFilter[$val][0].label;
            //                         removeParamsQuery($val);
            //                     }
            //                 } else {
            //                     listFilter[$val][0].checked = true;
            //                 }

            //             });
            //         },

            //         async firstInitModifiedFilter() {
            //             $filterName = 'modifiedtype';
            //             if (isParamQueryExists($filterName)) {
            //                 $valParamQuery = whatParamQueryValue($filterName);

            //                 if (listFilter.modified.some(x => x.value.label == $valParamQuery) && $valParamQuery != 'all') {
            //                     $filterSelect = listFilter.modified.find(x => x.value.label == $valParamQuery);
            //                     $filterSelect.checked = true;

            //                     const now = new Date();
            //                     const start = new Date();
            //                     start.setDate(start.getDate() - $filterSelect.value.start_date);

            //                     $formatStart = this.formateDate(start);
            //                     $formatNow = this.formateDate(now);

            //                     this.$filterDocument.modified.filterText = $filterSelect.label;
            //                     this.$filterDocument.modified.filterValue.label = $filterSelect.value.label;
            //                     this.$filterDocument.modified.filterValue.start_date = $formatStart;
            //                     this.$filterDocument.modified.filterValue.end_date = $formatNow;

            //                     setParamsQuery('start_date', $formatStart);
            //                     setParamsQuery('end_date', $formatNow);

            //                 } else {
            //                     this.$filterDocument.modified.filterText = listFilter.modified[0].label;
            //                     this.$filterDocument.modified.filterValue.label = listFilter.modified[0].value.label;
            //                     this.$filterDocument.modified.filterValue.start_date = '';
            //                     this.$filterDocument.modified.filterValue.end_date = '';

            //                     listFilter.modified[0].checked = true;

            //                     $paramsQuery = ['modifiedtype', 'start_date', 'end_date'];
            //                     $paramsQuery.forEach(($val) => {
            //                         removeParamsQuery($val);
            //                     });
            //                 }
            //             } else {
            //                 this.$filterDocument.modified.filterText = listFilter.modified[0].label;
            //                 this.$filterDocument.modified.filterValue.label = listFilter.modified[0].value.label;
            //                 this.$filterDocument.modified.filterValue.start_date = '';
            //                 this.$filterDocument.modified.filterValue.end_date = '';

            //                 listFilter.modified[0].checked = true;
            //             }
            //         },

            //         async firstInitFilter() {
            //             $listFilterCheck = [
            //                 {param: 'status', funcFilter: 'changeStatusFilter'}
            //             ];

            //             $listFilterCheck.forEach(($valFilter) => {

            //             });
            //         }
            //     };
            // });

            Alpine.data('filterDocument', () => {
                const csrf_token = '{{ csrf_token() }}';
                
                const $today = new Date();
                const $yesterday = new Date();
                $yesterday.setDate($yesterday.getDate() - 1);
                
                listFilter = {
                    status: [
                        {label: 'All Status',value: 'all',checked: false},
                        {label: 'In Progress',value: 'progress',checked: false},
                        {label: 'Completed',value: 'completed',checked: false},
                        {label: 'Rejected',value: 'rejected',checked: false},
                        {label: 'Draft',value: 'draft',checked: false},
                    ],
                    type: [
                        {label: 'All Type',value: 'all',checked: false},
                        {label: 'Signature',value: 'signature',checked: false},
                        {label: 'Paraf',value: 'paraf',checked: false},
                        {label: 'Uncategorized',value: 'uncategorized',checked: false},
                    ],
                    modified: [
                        {
                            label: 'All Period',
                            value: {
                                label: 'all',
                                start_date: 'all',
                            },
                            checked: false
                        },
                        {
                            label: 'Last 30 days',
                            value: {
                                label: 'L30D',
                                start_date: 30,
                            },
                            checked: false
                        },
                        {
                            label: 'Last 3 months',
                            value: {
                                label: 'L3M',
                                start_date: 90,
                            },
                            checked: false
                        },
                        {
                            label: 'Last 6 months',
                            value: {
                                label: 'L6M',
                                start_date: 180,
                            },
                            checked: false
                        },
                    ],
                    customModified: [
                        {
                            label: 'Start Date',
                            elem: {
                                id: '#customModifiedFilterStartDate',
                                icon: 'fas fa-calendar-days',
                                flatpickrDay: 'hover:bg-gradient-to-r from-[#FFCA28] to-[#D4A927] hover:text-[#533F00]',
                            },
                            value: '',
                            config: {
                                dateFormat: 'd-m-Y',
                                maxDate: $yesterday,
                            }
                        },
                        {
                            label: 'End Date',
                            elem: {
                                id: '#customModifiedFilterEndDate',
                                icon: 'fas fa-calendar-days',
                                flatpickrDay: 'hover:bg-gradient-to-r from-[#FFCA28] to-[#D4A927] hover:text-[#533F00]',
                            },
                            value: '',
                            config: {
                                dateFormat: 'd-m-Y',
                                maxDate: $today,
                            }
                        },
                    ]
                };
                
                const ElementDataCustomInput = [
                    {
                        label: 'start_date',
                        dataValue: 'customModifiedFilterStartDate',
                        maxDataValue: {
                            value: $yesterday,
                            stringValue: $yesterday.toISOString().split("T")[0],
                            datepickerValue: '-1',
                        },
                    },
                    {
                        label: 'end_date',
                        dataValue: 'customModifiedFilterEndDate',
                        maxDataValue: {
                            value: $today,
                            stringValue: $today.toISOString().split('T')[0],
                            datepickerValue: '0'
                        },
                    },
                ];
                // listFilter.customModified.forEach((x) => {
                //     console.log(x);
                    
                //     $jq(x.elem.id).flatpickr({
                //         dateFormat: x.config.dateFormat,
                //         maxDate: x.config.maxDate,
                //         onReady: function (selectedDates, dateStr, instance) {
                //             let selectClassInstance = instance.calendarContainer.className;
                //             selectClassInstance = selectClassInstance.split(' ')[0];

                //             const jqClassInstance = $jq(`.${selectClassInstance}`);
                //             jqClassInstance.addClass([
                //                 // 'bg-blue-100'
                //             ]);
                            
                //             jqClassInstance.css({
                //                 position: 'absolute'
                //             });
                            
                //             jqClassInstance.find('.flatpickr-month select').addClass('text-sm');
                //             jqClassInstance.find('.flatpickr-monthDropdown-months option').addClass('text-sm');
                            
                //             jqClassInstance.find('.flatpickr-innerContainer').addClass('mt-1.5');
                //             jqClassInstance.find('.flatpickr-day').addClass(x.elem.flatpickrDay);
                //         },
                //         onChange: function(selectedDates, dateStr, instance) {
                //             let selectClassInstance = instance.calendarContainer.className;
                //             selectClassInstance = selectClassInstance.split(' ')[0];
                            
                //             const jqClassInstance = $jq(`.${selectClassInstance}`);
                //             jqClassInstance.addClass([
                //                 // 'bg-blue-100'
                //             ]);
                            
                //             jqClassInstance.css({
                //                 position: 'absolute',
                //                 right: '0'
                //             });
                            
                //             jqClassInstance.find('.flatpickr-month select').addClass('text-sm');
                //             jqClassInstance.find('.flatpickr-monthDropdown-months option').addClass('text-sm');
                            
                //             jqClassInstance.find('.flatpickr-innerContainer').addClass('mt-1.5');
                //             jqClassInstance.find('.flatpickr-day').addClass(x.elem.flatpickrDay);
                //         }
                //     });
                // });
                
                

                return {
                    
                    $filterDocument: {
                        status: {
                            filterModal: false,
                            filterText: listFilter.status[0].label,
                            filterValue: listFilter.status[0].value
                        },
                        type: {
                            filterModal: false,
                            filterText: listFilter.type[0].label,
                            filterValue: listFilter.type[0].value
                        },
                        owner: {
                            filterModal: false,
                            filterText: '',
                            filterValue: ''
                        },
                        modified: {
                            filterModal: false,
                            filterText: listFilter.modified[0].label,
                            filterValue: {
                                label: listFilter.modified[0].value.label,
                                start_date: '',
                                end_date: '',
                            },
                            filterStatus: false
                        },
                        customModified: {
                            filterModal: false,
                            filterText: 'Custom Period',
                            filterValue: {
                                start_date: {
                                    label: 'Start Date',
                                    value: ''
                                },
                                end_date: {
                                    label: 'End Date',
                                    value: ''
                                },
                            },
                            filterStatus: false,
                        }
                    },

                    init() {
                        this.firstInitFilter();
                        window.addEventListener('Alpine-Init-Filter-Document', ($val) => {
                            this.dispatchDataFilter();
                        });
                        
                        ElementDataCustomInput.forEach((x) => {
                            const jqElmnt = $jq(`#${x.dataValue}`);
                            // jqElmnt.datepicker({
                            //     changeMonth: true,
                            //     changeYear: true,
                            //     dateFormat: 'dd-mm-yy',
                            //     maxDate: x.maxDataValue.datepickerValue,
                            // });
                            const calendarInput = new Calendar(`#${x.dataValue}`, {
                                inputMode: true,
                                dateMax: x.maxDataValue.value,
                                dateToday: x.maxDataValue.value,
                                positionToInput: 'left',
                                selectedTheme: 'light',
                                selectedWeekends: [-1],
                                onChangeToInput(self) {
                                    const input = self.context.inputElement;
                                    if (!input) return;
                                    
                                    input.value = self.context.selectedDates[0] || '';
                                    
                                    input.dispatchEvent(new Event('input', { bubbles: true }));
                                    input.dispatchEvent(new Event('change', { bubbles: true }));
                                    // if (input.value) {
                                    // }
                                },
                            });
                            
                            calendarInput.init();
                        });
                    },

                    changeStatusFilter(event, isDispatch = true, isSetParam = true) {
                        const valInp = event.target?.value || event;
                        if (listFilter.status.some(x => x.value == valInp)) {
                            findStatus = listFilter.status.find(x => x.value == valInp);
                            findStatus.checked = true;
                            this.$filterDocument.status.filterText = findStatus.label;
                            this.$filterDocument.status.filterValue = findStatus.value;

                            if (findStatus.value == 'all') {
                                removeParamsQuery('status');
                            } else if (isSetParam) {
                                setParamsQuery('status', findStatus.value);
                            }
                        } else {
                            this.$filterDocument.status.filterText = listFilter.status[0].label;
                            this.$filterDocument.status.filterValue = listFilter.status[0].value;
                            removeParamsQuery('status');
                        }

                        if (isDispatch) {
                            this.dispatchDataFilter();
                        }

                    },

                    changeTypeFilter(event, isDispatch = true, isSetParam = true) {
                        const valInp = event.target?.value || event;

                        if (listFilter.type.some(x => x.value == valInp)) {
                            findType = listFilter.type.find(x => x.value == valInp);
                            findType.checked = true;

                            this.$filterDocument.type.filterText = findType.label;
                            this.$filterDocument.type.filterValue = findType.value;

                            if (findType.value == 'all') {
                                removeParamsQuery('type');
                            } else if (isSetParam) {
                                setParamsQuery('type', findType.value);
                            }
                        } else {
                            this.$filterDocument.type.filterText = listFilter.type[0].label;
                            this.$filterDocument.type.filterValue = listFilter.type[0].value;
                            removeParamsQuery('type');
                        }

                        if (isDispatch) {
                            this.dispatchDataFilter();
                        }
                    },

                    changeModifiedFilter(event, isDispatch = true, isSetParam = true) {
                        const valInp = event.target?.value || event;
                        
                        if (listFilter.modified.some(x => x.value.label == valInp) && (valInp != 'all') && (!this.$filterDocument.customModified.filterStatus)) {
                            findModified = listFilter.modified.find(x => x.value.label == valInp);
                            findModified.checked = true;

                            const now = new Date();
                            const start = new Date();
                            start.setDate(start.getDate() - findModified.value.start_date);
                            
                            $formatStart = this.formateDate(start);
                            $formatNow = this.formateDate(now);

                            this.$filterDocument.modified.filterText = findModified.label;
                            this.$filterDocument.modified.filterValue.label = findModified.value.label;
                            this.$filterDocument.modified.filterValue.start_date = $formatStart;
                            this.$filterDocument.modified.filterValue.end_date = $formatNow;
                            this.$filterDocument.modified.filterStatus = true;
                            this.$filterDocument.customModified.filterStatus = false;

                            if (isSetParam) {
                                setParamsQuery('modifiedtype', valInp);
                                setParamsQuery('start_date', $formatStart);
                                setParamsQuery('end_date', $formatNow);
                            }
                        } else {
                            listFilter.modified[0].checked = true;
                            this.$filterDocument.modified.filterText = listFilter.modified[0].label;
                            this.$filterDocument.modified.filterValue.label = listFilter.modified[0].value.label;
                            this.$filterDocument.modified.filterValue.start_date = '';
                            this.$filterDocument.modified.filterValue.end_date = '';
                            this.$filterDocument.modified.filterStatus = false;

                            removeParamsQuery('modifiedtype');
                            removeParamsQuery('start_date');
                            removeParamsQuery('end_date');
                        }

                        if (isDispatch) {
                            this.dispatchDataFilter();
                        }
                    },
                    
                    changeCustomModifiedFilter(event, isDispatch = true, isSetParam = true) {
                        // console.log();
                        // console.log($jq(`#${event.target.attributes.id.value}`).data('custom-label'));
                        // // console.log($jq(event.target).attr());
                        // console.log(event.target.value);
                        // const elemEvent = $jq(`#${event.currentTarget.attributes.id.value}`);
                        const elemEvent = $jq(`#${event.currentTarget.id}`);
                        const valInp = elemEvent.val();
                        const dataCustom = elemEvent.data('custom-label');
                        const dataCustomStatus = this.$filterDocument.customModified.filterValue.hasOwnProperty(dataCustom);
                        
                        let formatDateInp = this.formateDate(valInp);
                        
                        if (dataCustomStatus) {
                            const softCopyObjFilterDocument = this.$filterDocument.customModified;
                            
                            softCopyObjFilterDocument.filterValue[dataCustom].value = formatDateInp
                        }
                        
                        // console.log(this.checkValidDateString(valInp));
                        // console.log(event);
                        // console.log(elemEvent);
                        // console.log(valInp);
                        // console.log(dataCustom);
                        
                        // let newDateInp = new Date(valInp);
                        // let formatDateInp = this.formateDate(valInp);
                        
                        // console.log(newDateInp);
                        // console.log(formatDateInp);
                        
                        // if (this.checkValidDateString(valInp)) {
                        //     alert('Date format not valid');
                        //     return;
                        // }
                        
                        // if (dataCustom in this.$filterDocument.customModified.filterValue) {
                            
                        // }
                        
                        // if (listFilter.customModified.some())
                    },
                    
                    clearCustomModifiedFilter(event) {
                        $elemClear = $jq(`#${event.target.attributes.id.value}`);
                        $dataAttr = $elemClear.data('custom-clear');
                    },
                    
                    viewModalCustomModifiedFilter() {
                        const labelModifiedFilter = this.$filterDocument.modified.filterValue.label;
                        let statusModalModifiedFilter = this.$filterDocument.customModified.filterModal;
                        console.log(this.$filterDocument.customModified.filterModal);
                        if (this.$filterDocument.customModified.filterModal) {
                            this.$filterDocument.customModified.filterModal = false;
                        } else {
                            this.$filterDocument.customModified.filterModal = true;
                        }
                        
                        if (labelModifiedFilter != 'custom') {
                            
                        }
                    },
                    
                    // viewCalendarCustomModifiedFilter(event) {
                    //     console.log($jq('#ui-datepicker-div'));
                    //     $jq('#ui-datepicker-div').css({ backgroundColor: 'red' });
                    //     let elmEvent = event.currentTarget;
                    //     let jqElmEvent = $jq(elmEvent);
                    //     let valDataCustomInput = jqElmEvent.data('custom-input');
                        
                    //     if (ElementDataCustomInput.some(x => x.dataValue == valDataCustomInput)) {
                    //         const $jqInpEvent = $jq(`#${valDataCustomInput}`);
                            
                    //         console.log($jqInpEvent);
                    //         $jqInpEvent.focus();
                    //         // $jqInpEvent.css({ backgroundColor: 'red' });
                    //         // $jqInpEvent.showPicker();
                    //     }
                    //     // console.dir(elmEvent);
                    //     // console.log(valDataCustomInput);
                    //     // console.log(elmEvent.attributes);
                    //     // console.log(event);
                    //     // console.log(event.target.attributes);
                    //     // console.log(event.target.className);
                    // },

                    formateDate($time = new Date()) {
                        const now = new Date($time);

                        const day = String(now.getDate()).padStart(2, '0');
                        const month = String(now.getMonth() + 1).padStart(2, '0');
                        const year = now.getFullYear();

                        const formattedDate = `${day}-${month}-${year}`;
                        return formattedDate;
                    },
                    
                    formateDateDefault($time) {
                        if (!/^\d{2}-\d{2}-\d{4}$/.test(val)) return false;
                        
                        const [day, month, year] = val.split('-').map(Number);
                        const dateObj = new Date(year, month - 1, day);
                        const dateString = `${year}-${month}-${day}`;
                        
                        return dateString;
                    },
                    
                    checkValidDateString(val) {
                        if (!/^\d{2}-\d{2}-\d{4}$/.test(val)) return false;
                        
                        const [day, month, year] = val.split('-').map(Number);
                        const dateObj = new Date(year, month - 1, day);
                        
                        return (
                            dateObj.getFullYear() === year &&
                            dateObj.getMonth() === month - 1 &&
                            dateObj.getDate() === day
                        );
                    },

                    dispatchDataFilter() {
                        $dispatchingData = Object.assign({}, this.$filterDocument);
                        Object.assign($dispatchingData, {
                            _token: csrf_token
                        });
                        console.log($dispatchingData);

                        dispatchingDataLivewireTo('Document-Filter-Data', $dispatchingData);
                    },

                    async firstInitFilter() {
                        let $listFilterCheck = [{
                            filter: 'status',
                            funcFilter: 'changeStatusFilter',
                            dispatch: false,
                            param: false
                        },
                        {
                            filter: 'type',
                            funcFilter: 'changeTypeFilter',
                            dispatch: false,
                            param: false
                        },
                        {
                            filter: 'modifiedtype',
                            funcFilter: 'changeModifiedFilter',
                            dispatch: false,
                            param: true
                        },
                        ];

                        $listFilterCheck.forEach(($valFilter) => {
                            $valFilterParamQuery = whatParamQueryValue($valFilter.filter) ?? 'all';

                            this[$valFilter.funcFilter]($valFilterParamQuery, $valFilter.dispatch,
                                $valFilter.param);
                        });
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