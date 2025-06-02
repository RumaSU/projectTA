<div class="ctr-navDocumentDashboard">
    <div class="cNavDocumentDashboard flex items-center justify-between">
        <div class="ctr-leftNavDocumentDashboard">
            <div class="cLeftNavDocumentDashboard">
                <div class="titleNavDocument">
                    <div class="txTitle text-2xl font-semibold">
                        <h2>Documents</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ctr-rightDocumentNavDashboard">
            <div class="cRightDocumentNavDashboard">
                <div class="wrapper-searchDocumentNavDashboard">
                    <div class="searchDocumentNavDashboard flex py-0.5 pl-2 pr-1 focus-within:ring-1 focus-within:ring-blue-600 border border-black overflow-hidden rounded-full"
                        x-data="search_document"
                    >
                        <div class="inpSearchDocument flex items-center justify-center px-2">
                            <div class="inpF">
                                {{-- <input type="text" id="inpSearchDocument" placeholder="search..." class="text-sm bg-transparent p-0 border-none ring-0 focus:border-none focus:ring-0"> --}}
                                <input 
                                    type="text" 
                                    id="inpSearchDocument" 
                                    placeholder="search..." 
                                    class="text-sm bg-transparent p-0 focus:ring-0 focus:ring-transparent outline-none w-64"
                                    x-model="inpSearch"
                                    wire:click
                                    @change="handleSearchDocument"
                                    >
                            </div>
                        </div>
                        <div class="wrapper-btnSearchDocument">
                            <button class="btnSearchDocument block p-1 rounded-full hover:bg-gray-200">
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
            
            Alpine.data('search_document', () => {
                let paramQV = whatParamQueryValue('s');
                const csrf_token = '{{ csrf_token() }}';
                
                
                if (paramQV) {
                    console.log(paramQV);
                    $dataParam = {
                        search: paramQV,
                        _token: csrf_token,
                    }
                    Livewire.dispatch('Document-Search-Page', [$dataParam]);
                    console.log('Dispatching data to: Mail-Search')
                }
                
                return {
                    inpSearch: paramQV,
                    handleSearchDocument(event) {
                        const valInp = event.target.value;
                        setParamsQuery('s', valInp);
                        
                        $dataSearch = {
                            search: valInp,
                            _token: csrf_token,
                        }
                        Livewire.dispatch('Document-Search-Page', [$dataSearch]);
                        console.log(event.target.value);
                    },
                };
            });
        </script>
    @endpush
@endonce