<div class="ctr-filterMailDashboard">
    <div class="cFilterMailDashboard flex items-center justify-between">
        <div class="ctr-lftFilterMailDashboard">
            <div class="cLftFilterMailDashboard flex">
                <div class="act-checkAllFilterMailDashboard flex items-center h-8">
                    <div class="act-checkAllMailDashboard group">
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
                            {{-- <div class="group-has-[:checked]:bg-black size-4 rounded-full absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2"></div> --}}
                        </label>
                        <input type="checkbox" id="checkAllFilterDashboard" class="sr-only hidden" checked>
                    </div>
                    <div class="filter-checkAllMailDashboard relative">
                        <button class="btn-filterCheckAllMailDashboard w-6 h-8 mt-1 relative rounded-md hover:bg-[#D9D9D9]">
                            <div class="icn text-[#3D3D3D] absolute left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2">
                                <i class="fas fa-sort-down"></i>
                            </div>
                        </button>
                        <div class="abs-filterCheckAllMailDashbaord absolute">
                            <div class="cFilterCheckAllMailDashbaord">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="moreAct-mailDashboard ml-4 flex gap-2">
                    <div class="act-refreshMailDashbaord">
                        <button class="bntAct-refreshMailDashboard block rounded-full hover:bg-[#d9d9d9]">
                            <div class="cBtnAct size-8 flex items-center justify-center">
                                <div class="icn text-xl text-[#3D3D3D]">
                                    <i class="fas fa-rotate-right"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    {{-- .moreAct --}}
                </div>
            </div>
        </div>
        <div class="ctr-rghtFilterMailDashboard">
            <div class="cRghtFilterMailDashboard flex items-center gap-4">
                <div class="numOfPaginateMailDashboard text-xs inline-flex gap-1 text-[#7D7D7D]">
                    <div class="minNumActivePaginate">
                        <p>{$num1}</p>
                    </div>
                    - 
                    <div class="maxNumActivePaginate">
                        <p>{$num2}</p>
                    </div>
                    of
                    <div class="ofMaxNumPaginateMailDashboard">
                        <p>${num3}</p>
                    </div>
                </div>
                <div class="act-paginateMailDashboard flex gap-2">
                    <div class="act-leftPaginateMailDashboard">
                        <button class="btnAct-paginateMailDashboard rounded-full hover:bg-[#D9D9D9]">
                            <div class="cBtnAct-paginateMailDashboard size-8  flex items-center justify-center">
                                <div class="icn text-[#3D3D3D]">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="act-rghtPaginateMailDashboard">
                        <button class="btnAct-paginateMailDashboard rounded-full hover:bg-[#D9D9D9]">
                            <div class="cBtnAct-paginateMailDashboard size-8  flex items-center justify-center">
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