<div class="cMainContentDocumentsAppDashbaord"

>
    
    {{-- List --}}
    <div class="ctr-listDocumentsApp">
        <div class="cListDocumentsApp space-y-1" x-data>
            @if(count($this->listDocument))
                
                @foreach($this->listDocument as $item)
                    @php
                        $styleStatus = \App\Enums\Documents\Signature\Status::from_value($item->status)->get_style();
                        $styleType = \App\Enums\Documents\Signature\Type::from_value($item->type)->get_style();
                        
                        $ownerName = \App\Utils\ModelUtils::createInstanceQuery('user_personal')->where('id_user', '=', $item->owner_id)->first()->fullname ?? 'Not found';
                        // $timestamp = \Carbon\Carbon::create($item->created_at)->format('d M, Y - H:m:s');
                        $timestamp = \Carbon\Carbon::create($item->created_at)->timezone(session()->get('timezone'))->format('d M, Y - H:m:s');
                        
                        $isNew = \Carbon\Carbon::create($item->created_at)->diffInMinutes() < 1;
                        
                        $version = \App\Utils\ModelUtils::createInstanceModel(\App\Models\Documents\DocumentVersions::class)
                            ->query()
                            ->where('id_document', '=', $item->id_document)
                            ->latest('version')
                            ->first();
                        
                        $doc_file = \App\Utils\ModelUtils::createInstanceModel(\App\Models\Documents\DocumentFile::class)
                            ->query()
                            ->where('id_document_version', '=', $version->id_document_version)
                            ->first();
                        
                        $file_doc = \App\Utils\ModelUtils::createInstanceModel(\App\Models\Files\Entity\Documents::class)
                            ->query()
                            ->where('id_file_document', '=', $doc_file->id_file_document)
                            ->first();
                        
                        $file_entity = \App\Utils\ModelUtils::createInstanceModel(\App\Models\Files\DiskEntity::class)
                            ->query()
                            ->where('id_file_disk', '=', $file_doc->id_file_disk)
                            ->first();
                        
                        $file_token = \App\Utils\ModelUtils::createInstanceModel(\App\Models\Files\DiskToken::class)
                            ->query()
                            ->where('id_file_disk_entity', '=', $file_entity->id_file_disk_entity)
                            ->first();
                        
                    @endphp
                    
                    <div class="itemDocuments bg-white px-4 py-2 rounded-lg shadow-sm shadow-black/40 relative">
                        <div class="contentItemDocuments grid grid-cols-4">
                            <div class="detailDocuments col-span-2">
                                <div class="nameDocuments">
                                    <a href="#" class="hrefDocument flex-grow">
                                        <div class="textName font-semibold hover:text-blue-600 line-clamp-2">
                                            <p>{{ $item->name }}</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="infoDocuments">
                                    <div class="ownedDocument text-sm line-clamp-1">
                                        {{-- <p class="cursor-default">Owned by <a href="#" class="cursor-pointer border-b hover:border-blue-600 hover:text-blue-600">Orang</a> </p> --}}
                                        <p class="cursor-default">Owned by 
                                            <a href="#" class="cursor-pointer border-b hover:border-blue-600 hover:text-blue-600">{{ $ownerName }}</a> 
                                        </p>
                                    </div>
                                    
                                    <div class="listInfoDocuments flex items-center flex-wrap gap-1 mt-1">
                                        <div class="infoStatusDocuments px-4 py-1.5 rounded-lg {{ $styleStatus['background'] }}">
                                            <div class="textStatus text-xs {{ $styleStatus['textColor'] }}">
                                                <p>{{ $styleStatus['text'] }}</p>
                                            </div>
                                        </div>
                                        <div class="infoTypeSignature px-4 py-1.5 rounded-lg {{ $styleType['background'] }}">
                                            <div class="textType text-xs {{ $styleType['textColor'] }}">
                                                <p>{{ $styleType['text'] }}</p>
                                            </div>
                                        </div>
                                        <div class="infoActiviyDocuments flex items-center gap-1">
                                            <div class="lastActivityDocument flex items-center gap-2 bg-gray-200 rounded-lg px-4 py-1.5 size-fit group/itemActivity cursor-defaul select-none">
                                                <div class="iconActivity text-xs">
                                                    <i class="fas fa-plus"></i>
                                                </div>
                                                <div class="textActivity text-xs">
                                                    <p>Created at</p>
                                                </div>
                                                <div class="timeActivity text-xs hidden group-hover/itemActivity:block font-semibold">
                                                    <p>{{ $timestamp }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ownerDocument flex items-center justify-center">
                                <div class="contentOwnerDocument flex items-center justify-center gap-2 w-3/4">
                                    {{-- <div class="imageOwner shrink-0">
                                        <div class="image size-12 rounded-lg overflow-hidden">
                                            <img src="" alt="" class="size-full object-cover object-center">
                                        </div>
                                    </div> --}}
                                    <div class="nameOwner flex-grow">
                                        <div class="textName text-sm line-clamp-1">
                                            <p>{{ $ownerName }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="actionDocuments flex items-center justify-center">
                                <div class="contentActionDocuments flex items-center gap-1">
                                    <button 
                                        type="button"
                                        class="item-actionDocuments flex items-center relative group" 
                                        aria-label="Action Signature"
                                        wire:click="actionSign('{{ $item->id_document }}')"
                                        wire:loading.attr='disabled'
                                        >
                                        <div role="button" tabindex="0" class="actionDocuments size-10 flex items-center justify-center rounded-lg group-hover:bg-blue-100">
                                            <div class="actionIcon text-gray-600 group-hover:text-blue-600 group-hover:contrast-200">
                                                <i class="fas fa-signature text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="actionTooltip px-2 py-0.5 rounded-md absolute bg-white bottom-[105%] left-1/2 -translate-x-1/2 hidden group-hover:block shadow-sm shadow-black/40">
                                            <div class="textTooltip  text-xs">
                                                <p>Signature</p>
                                            </div>
                                        </div>
                                    </button>
                                    {{-- <div class="item-actionDocuments flex items-center relative group" aria-label="Action Info">
                                        <div role="button" tabindex="0" class="actionDocuments size-10 flex items-center justify-center rounded-lg group-hover:bg-gray-100">
                                            <div class="actionIcon text-gray-600 group-hover:text-gray-600">
                                                <i class="fas fa-circle-info text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="actionTooltip px-2 py-0.5 rounded-md absolute bg-white bottom-[105%] left-1/2 -translate-x-1/2 hidden group-hover:block shadow-sm shadow-black/40">
                                            <div class="textTooltip  text-xs">
                                                <p>Info</p>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <a 
                                        href="{{ route('app.documents.audit', ['id' => $item->id_document]) }}"
                                        class="item-actionDocuments flex items-center relative group" 
                                        aria-label="Action Signature"
                                        target="_blank" rel="noopener noreferrer"
                                        >
                                        <div role="button" tabindex="0" class="actionDocuments size-10 flex items-center justify-center rounded-lg group-hover:bg-blue-100">
                                            <div class="actionIcon text-gray-600 group-hover:text-blue-600 group-hover:contrast-200">
                                                <i class="fas fa-gavel text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="actionTooltip px-2 py-0.5 rounded-md absolute bg-white bottom-[105%] left-1/2 -translate-x-1/2 hidden group-hover:block shadow-sm shadow-black/40">
                                            <div class="textTooltip  text-xs">
                                                <p>Audit</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a 
                                        href="{{ route('drive.files.entity_document', ['token' => $file_token->token]) }}"
                                        class="item-actionDocuments flex items-center relative group" 
                                        aria-label="Action Signature"
                                        target="_blank" rel="noopener noreferrer"
                                        >
                                        <div role="button" tabindex="0" class="actionDocuments size-10 flex items-center justify-center rounded-lg group-hover:bg-blue-100">
                                            <div class="actionIcon text-gray-600 group-hover:text-blue-600 group-hover:contrast-200">
                                                <i class="fas fa-eye text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="actionTooltip px-2 py-0.5 rounded-md absolute bg-white bottom-[105%] left-1/2 -translate-x-1/2 hidden group-hover:block shadow-sm shadow-black/40">
                                            <div class="textTooltip  text-xs">
                                                <p>View</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a 
                                        href="{{ route('drive.files.download_entity_document', ['token' => $file_token->token]) }}"
                                        class="item-actionDocuments flex items-center relative group" 
                                        aria-label="Action Signature"
                                        target="_blank" rel="noopener noreferrer"
                                        >
                                        <div role="button" tabindex="0" class="actionDocuments size-10 flex items-center justify-center rounded-lg group-hover:bg-blue-100">
                                            <div class="actionIcon text-gray-600 group-hover:text-blue-600 group-hover:contrast-200">
                                                <i class="fas fa-download text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="actionTooltip px-2 py-0.5 rounded-md absolute bg-white bottom-[105%] left-1/2 -translate-x-1/2 hidden group-hover:block shadow-sm shadow-black/40">
                                            <div class="textTooltip  text-xs">
                                                <p>Download</p>
                                            </div>
                                        </div>
                                    </a>
                                    
                                    {{-- <div class="item-actionDocuments flex items-center relative group" aria-label="Action Delete">
                                        <div role="button" tabindex="0" class="actionDocuments size-10 flex items-center justify-center rounded-lg group-hover:bg-red-100">
                                            <div class="actionIcon text-gray-600 group-hover:text-red-600 group-hover:contrast-200">
                                                <i class="fas fa-trash text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="actionTooltip px-2 py-0.5 rounded-md absolute bg-white bottom-[105%] left-1/2 -translate-x-1/2 hidden group-hover:block shadow-sm shadow-black/40">
                                            <div class="textTooltip  text-xs">
                                                <p>Delete</p>
                                            </div>
                                        </div>
                                    </div> --}}
                                    
                                </div>
                            </div>
                        </div>
                        
                        @if ($isNew)
                            <div class="statusNewDocument absolute right-2 top-2 px-2 py-1.5 rounded-lg bg-blue-600 size-fit cursor-default select-none">
                                <div class="textStatus text-white text-xs">
                                    <p>New</p>
                                </div>
                            </div>
                        @endif
                        
                    </div>
                @endforeach
            
            
            @else
                <div class="wrapper-noDocumentYet flex items-center justify-center">
                    <div class="cNoDocumentYet p-6">
                        <div class="imgNoDocument flex items-center justify-center">
                            <div class="img size-64">
                                <img src="{{ asset('components/icon/page.png') }}" class="size-full object-cover object-center" alt="No Document Yet">
                            </div>
                        </div>
                        <div class="descNoDocument">
                            <div class="txTitleNoDocument text-center">
                                <div class="txTitle text-xl font-semibold">
                                    <p>There is no document yet</p>
                                </div>
                            </div>
                            <div class="txInfoNoDocument text-center mt-4">
                                <div class="txInfo poppins-light text-gray-600">
                                    <p>Your documents will be shown here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            
        </div>
    </div>
    
</div>

@once
    @push('global-custom-content')
        @script
            <script data-navigate-once="true">
                console.log('ini tesssssssssssssssssssssssssssssssssssssssssssssss');
                Alpine.data('contextMenu', () => ({
                    x: 0,
                    y: 0,
                    visible: false,
                    hovering: false,
                    
                    showMenu(event) {
                        this.x = event.clientX;
                        this.y = event.clientY;
                        this.visible = true;
                    },
                    
                    hideMenu() {
                        // Tunggu sejenak jika mouse sedang berada di atas menu
                        setTimeout(() => {
                            if (!this.hovering) {
                                this.visible = false;
                            }
                        }, 100);
                    }
                }));
                
            </script>
        @endscript
        
        
        @script
            <script data-navigate-once="true">
                console.log('ini tesssssssssssssssssssssssssssssssssssssssssssssss2222222222');
            </script>
        @endscript
        
    @endpush
@endonce