<div class="ctr-navDocumentDashboard h-12">
    <div class="cNavDocumentDashboard flex items-center justify-between">
        <div class="ctr-leftNavDocumentDashboard">
            <div class="cLeftNavDocumentDashboard flex gap-1">
                <div class="titleNavDocument">
                    <div class="txTitle text-2xl font-semibold">
                        <h2>Documents</h2>
                    </div>
                </div>
                {{-- <div class="countDocument size-fit px-1 py-0.5 rounded-lg bg-blue-600">
                    <div class="textCount text-sm text-white">
                        <p>{{ $totalDocument }}</p>
                    </div>
                </div> --}}
            </div>
        </div>
        
        <div class="ctr-rightDocumentNavDashboard">
            <div class="cRightDocumentNavDashboard flex items-center gap-2">
                
                {{-- <div class="wrapper-uploadDocumentNavDashboard">
                    <div class="uploadDocumentNavDashboard">
                        <button class="act-buttonUploadDocument py-1.5 px-6 overflow-hidden relative transition-all group rounded-xl bg-gray-200">
                            <div class="actionUpload flex items-center gap-2 text-gray-400">
                                <div class="actionIcon text-lg size-8 flex items-center justify-center">
                                    <i class="fas fa-upload"></i>
                                </div>
                                <div class="actionText text-sm">
                                    <p>Upload Document</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div> --}}
                
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
    
    
    @push('global-custom-content')
        
    @endpush
    
    
    @push('dashboard-body-script')
        
        <script data-navigate-once="true">
            
            Alpine.data('uploadDocuments')
            
        </script>
        
        
        
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
                        // setParamsQuery('s', valInp);
                        
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