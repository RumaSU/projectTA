




@section('dashboard-child-template')
    
    {{-- header upload --}}
    <div class="ctr">
        <div class="c">
            <div class="textHeader text-lg font-semibold">
                <div class="p">Add file</div>
            </div>
            <div class="descriptionHeader text-sm">
                <p>Upload file to get started</p>
            </div>
        </div>
    </div>
    
    {{-- placholder upload --}}
    <div 
        wire:ignore
        class="wrapper mt-8 flex items-center justify-center" 
        x-data="placeholderUploadFile">
        <div 
            @drop.prevent="dragover = false; dropfile($event)"
            @dragover.prevent="dragover = true"
            @dragleave.prevent="dragover = false"
            class="ctr w-3/4 h-72 border-2 border-dashed border-gray-600 bg-gray-100 rounded-xl flex items-center justify-center"
            :style="dragover ? `background-color: #d1d5db` : `` "
            >
            <div class="c ">
                <div class="action">
                    <label 
                        role="button"
                        tabindex="0"
                        for="id_uploadFileDocume" 
                        class="px-12 py-2 bg-indigo-600 rounded-xl size-fit block shadow shadow-black/40 hover:bg-indigo-800"
                        :class="dragover ? `brightness-75` : ``"
                        >
                        <div class="c flex items-center justify-center gap-2 text-white">
                            <div class="icon size-8 flex items-center justify-center">
                                <i class="fas fa-upload"></i>
                            </div>
                            <div class="text text-sm">
                                <p>Select file</p>
                            </div>
                        </div>
                    </label>
                    <input 
                        type="file" 
                        name="" 
                        id="id_uploadFileDocume" 
                        tabindex="-1" 
                        @change="selectfile($event)"
                        class="sr-only text-xs placeholder:text-xs"
                        >
                </div>
                
                <div class="descriptionAction mt-2 flex items-center justify-center">
                    <div class="text text-sm text-gray-600"
                        :class="dragover ? `brightness-75` : ``">
                        <p>or drop file here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Container List Upload File --}}
    <div 
        class="ctr-uploadFile mt-12"
        x-data="containerListUploadFile"
        wire:ignore
        @newfileadded.window="listenNewFile"
        {{-- x-data="list_uploadFile" --}}
        >
        <div class="cUploadFile">
            <div class="headerUploadFiles flex items-center gap-2 justify-between">
                <div class="titleHeader shrink-0">
                    <div class="textHeader text-lg font-semibold">
                        <p>Status Upload File</p>
                    </div>
                </div>
                {{-- <div class="counterUpload flex items-center flex-wrap gap-2" x-ref="container_list_counter">
                    <div class="totalFile bg-gray-200 flex items-center gap-2.5 px-2 py-1 rounded">
                        <div class="dot size-2 rounded-full bg-gray-800"></div>
                        <div class="textCounter text-sm text-gray-800">
                            <p><span>{{ rand(1, 15) }}</span> file</p>
                        </div>
                    </div>
                    <div class="cancelFile bg-red-100 flex items-center gap-2.5 px-2 py-1 rounded">
                        <div class="dot size-2 rounded-full bg-red-800"></div>
                        <div class="textCounter text-sm text-red-800">
                            <p><span>{{ rand(1, 15) }}</span> canceled</p>
                        </div>
                    </div>
                    <div class="totalFile bg-green-100 flex items-center gap-2.5 px-2 py-1 rounded">
                        <div class="dot size-2 rounded-full bg-green-800"></div>
                        <div class="textCounter text-sm text-green-800">
                            <p><span>{{ rand(1, 15) }}</span> completed</p>
                        </div>
                    </div>
                    <div class="totalFile bg-indigo-100 flex items-center gap-2.5 px-2 py-1 rounded">
                        <div class="dot size-2 rounded-full bg-indigo-800"></div>
                        <div class="textCounter text-sm text-indigo-800">
                            <p><span>{{ rand(1, 15) }}</span> process</p>
                        </div>
                    </div>
                </div> --}}
            </div>
            
            <div class="ctr-listUploadFile mt-4">
                <div class="cListUploadFile space-y-2"
                    x-ref="container_list_upload"
                    >
                    
                    {{-- <div 
                        x-data
                        wire:key=""
                        class="item-uploadFile bg-slate-100 rounded-xl overflow-hidden shadow-sm shadow-black/40" 
                        >
                        
                        <div class="detailUpload flex items-center gap-1 px-2 py-1">
                            <div class="iconUpload shrink-0 size-10 flex items-center justify-center">
                                <div class="icon text-lg">
                                    <i class="fas fa-upload"></i>
                                </div>
                            </div>
                            <div class="contentUploadFile flex-grow flex items-center justify-between gap-4">
                                <div class="infoUploadFile">
                                    <div class="nameUploadFile text-sm font-semibold line-clamp-1">
                                        <p x-ref="name_file">Nama file</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="sizeUploadFile text-xs text-gray-600">
                                            <p>200 / 500 KB</p>
                                        </div>
                                        <div class="percentageUploadFile px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                            <div class="textPercentage text-xs ">
                                                <p>100%</p>
                                            </div>
                                        </div>
                                        <div class="chunkUploadFile px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                            <div class="textChunk text-xs ">
                                                <p>0 / 10</p>
                                            </div>
                                        </div>
                                        <div class="statusUploadFile flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-100">
                                            <div class="dot size-2 rounded-full bg-indigo-800"></div>
                                            <div class="textStatus text-xs text-indigo-800">
                                                <p>Status</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="actionUploadFile shrink-0">
                                    <button 
                                        type="button" 
                                        data-action-uplaod="upload"
                                        class="actionUploadFile block bg-indigo-200 text-indigo-800 px-2 py-1 rounded-lg hover:bg-indigo-300" 
                                        @click="console.log('test')"
                                        >
                                        <div class="cButton flex items-center gap-2">
                                            <div class="iconAction size-6 text-sm flex items-center justify-center">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <div class="textAction text-sm">
                                                <p>Upload</p>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="progressUpload mt-1 h-1.5 w-[{{ rand(10, 80) }}%] bg-indigo-800 transition-all"></div>
                    </div> --}}
                    
                    
                </div>
            </div>
            
        </div>
    </div>
    
    {{-- 
        yellow -> chunking / pending    #854d0e
        indigo -> progress              #3730a3
        red -> cancel                   #991b1b
        green -> completed              #166534
    --}}
    
    @livewire('app.upload.partials.document-function')
    
    @php
        $acceptTypeFile = json_encode(['application/pdf']);

    @endphp
    
@endsection


@once
    @push('dashboard-body-script')
        <script data-navigate-once="true">
            Alpine.data('placeholderUploadFile', () => {
                
                const acceptTypeFile = JSON.parse('{!! $acceptTypeFile !!}');
                
                return {
                    dragover: false,
                    
                    dropfile($event) {
                        if (! $event.dataTransfer.items) return
                        const dataTransfer = $event.dataTransfer;   
                        const listFiles = dataTransfer.files;
                        
                        if (! listFiles && listFiles.length) return;
                        
                        for (const item of listFiles) {
                            if (! item instanceof File) return;
                            // if (!acceptTypeFile.includes(item.type)) {
                            //     this.dispatchNotify(
                            //         'danger',
                            //         'Unsupported File Type',
                            //         `The file "${item.name}" (${item.type}) is not allowed. Only PDF files are supported.`
                            //     );
                            //     continue;
                            // }
                            
                            this.dispatchNewFile(item);
                        }
                    },
                    
                    selectfile($event) {
                        const input = $event.target;
                        const listFiles = input.files;
                        
                        if (! listFiles && listFiles.length) return;
                        
                        for (const item of listFiles) {
                            
                            if (! item instanceof File) continue;
                            // if (!acceptTypeFile.includes(item.type)) {
                            //     this.dispatchNotify(
                            //         'danger',
                            //         'Unsupported File Type',
                            //         `The file "${item.name}" (${item.type}) is not allowed. Only PDF files are supported.`
                            //     );
                            //     continue;
                            // }
                            
                            this.dispatchNewFile(item);
                        }
                    },
                    
                    dispatchNewFile(file) {
                        
                        const tokenUpload = generateClientToken(24);
                        const tokenResumable = `resumable_${generateClientToken(24)}`;
                        const data = {
                            tokenUpload: tokenUpload,
                            tokenResumable: tokenResumable,
                            newFile: file,
                        };
                        
                        const newToken = {
                            tokenUpload: tokenUpload,
                            tokenResumable: tokenResumable,
                        }
                        
                        Livewire.dispatch('create_token_upload_file', {data: newToken})
                        this.$dispatch('newfileadded', data);
                    },
                    
                    
                    dispatchNotify($variant = 'info', $title, $message) {
                        this.$dispatch('customnotify', { 
                            variant: $variant, 
                            title: $title,  
                            message: $message, 
                        });
                    }
                    
                    
                } 
            });
        </script>
        
        <script data-navigate-once="true">
            
            Alpine.data('containerListUploadFile', () => {
                
                const acceptTypeFile = JSON.parse('{!! $acceptTypeFile !!}');
                
                return {
                    
                    
                    
                    listenNewFile($event) {
                        const detail = $event.detail;
                        if (! detail) return;
                        if (! ('tokenUpload' in detail && 'tokenResumable' in detail && 'newFile' in detail)) return;
                        if (! detail.file instanceof File) return;
                        // if (! acceptTypeFile.includes(detail.newFile.type)) {
                        //     this.dispatchNotify(
                        //         'danger',
                        //         'Unsupported File Type',
                        //         `The file "${detail.newFile.name}" (${detail.newFile.type}) is not allowed. Only PDF files are supported.`
                        //     );
                        //     return;
                        // }
                        
                        const $token = "{{ csrf_token() }}";
                        const $route = "{{ route('app.uploads.chunk', ['type' => 'documents']) }}";
                        const $routeTest = "{{ route('app.uploads.chunk.test', ['type' => 'documents']) }}";
                        console.log(detail);
                        window[detail.tokenResumable] = new Resumable({
                            headers: {
                                'X-CSRF-TOKEN': $token
                            },
                            target: $route,
                            query:{
                                _token: $token,
                                token_resumable: detail.tokenResumable,
                                token_upload: detail.tokenUpload,
                                originalFilename: detail.newFile.name,
                            },
                            testChunks: true,
                            testTarget: $routeTest,
                            chunkSize: 1 * 1024 * 1024,
                            simultaneousUploads: 4,
                            // throttleProgressCallbacks: 1,
                            maxChunkRetries: 3,
                            chunkRetryInterval: 2000,
                            autoStart: false,
                        });
                        
                        window[detail.tokenResumable].addFile(detail.newFile);
                        
                        this.addNewItemDOMUpload(detail);
                        
                    },
                    
                    addNewItemDOMUpload(detail) {
                        const size = formatBytes( detail.newFile.size ).split(' ');
                        const template = document.createElement('div');
                        template.setAttribute('x-data', `uploadFile('${detail.tokenResumable}', '${detail.tokenUpload}')`);
                        template.setAttribute('wire:key', detail.tokenResumable);
                        template.setAttribute('class', 'item-uploadFile bg-slate-100 rounded-xl overflow-hidden shadow-sm shadow-black/40');
                        template.innerHTML = `
                            <div class="detailUpload flex items-center gap-1 px-2 py-1">
                                <div class="iconUpload shrink-0 size-10 flex items-center justify-center">
                                    <div class="icon text-lg">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                </div>
                                <div class="contentUploadFile flex-grow flex items-center justify-between gap-4">
                                    <div class="infoUploadFile">
                                        <div class="nameUploadFile text-sm font-semibold line-clamp-1">
                                            <p>${detail.newFile.name}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="sizeUploadFile text-xs text-gray-600">
                                                <p x-text="size">0 / 0B</p>
                                            </div>
                                            <div class="percentageUploadFile px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                                <div class="textPercentage text-xs ">
                                                    <p x-text="percentage">0%</p>
                                                </div>
                                            </div>
                                            <div class="chunkUploadFile px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                                <div class="textChunk text-xs ">
                                                    <p x-text="chunk">0 / 0</p>
                                                </div>
                                            </div>
                                            <div x-ref="progress_status" class="statusUploadFile flex items-center gap-1 px-2 py-0.5 rounded-full" style="background-color: #fef9c3;">
                                                <div class="dot size-2 rounded-full" style="background-color: #854d0e"></div>
                                                <div class="textStatus text-xs" style="color: #854d0e">
                                                    <p x-text="status">Pending</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="actionUploadFile shrink-0">
                                        <button 
                                            type="button" 
                                            data-action-upload="upload"
                                            class="actionUploadFile block px-2 py-1 rounded-lg enabled:hover:bg-indigo-300"
                                            style="background-color: #fef08a; color: #854d0e;"
                                            @click="action"
                                            x-ref="button_action"
                                            disabled
                                            >
                                            <div class="cButton flex items-center gap-2">
                                                <div class="iconAction size-6 text-sm flex items-center justify-center text-sm">
                                                    <svg class="svg-inline--fa aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play" role="img" xmlns="http://www.w3.org/2000/svg" 
                                                        viewBox="0 0 384 512" data-fa-i2svg="">
                                                        <path 
                                                            fill="currentColor" 
                                                            :d=action_icon_d 
                                                            >
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="textAction text-sm">
                                                    <p x-text="action_text">Pending</p>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div x-ref="progress_upload" class="progressUpload mt-1 h-1.5 transition-all" style="width: 0%; background-color: #3730a3;"></div>
                        `;
                        
                        this.$refs.container_list_upload.appendChild(template);
                        
                        Alpine.initTree(template);
                    },
                    
                    
                    dispatchNotify($variant = 'info', $title, $message) {
                        this.$dispatch('customnotify', { 
                            variant: $variant, 
                            title: $title,  
                            message: $message, 
                        });
                    },
                    
                }
            });
            
            
        </script>
        
        <script data-navigate-once="true">
            
            Alpine.data('uploadFile', ($resumeId, $token_upload) => {
                
                const actionAccept = ['upload', 'pause'];
                
                const pathPlay = `M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80L0 432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z`;
                const pathPause = `M48 64C21.5 64 0 85.5 0 112L0 400c0 26.5 21.5 48 48 48l32 0c26.5 0 48-21.5 48-48l0-288c0-26.5-21.5-48-48-48L48 64zm192 0c-26.5 0-48 21.5-48 48l0 288c0 26.5 21.5 48 48 48l32 0c26.5 0 48-21.5 48-48l0-288c0-26.5-21.5-48-48-48l-32 0z`;
                
                return {
                    percentage: '0%',
                    size: 0,
                    chunk: 0,
                    totalSize: 0,
                    totalChunk: 0,
                    
                    status: 'Pending',
                    action_text: 'Pending',
                    action_icon_d: pathPlay,
                    r: null,
                    isFirst: true,

                    
                    init() {
                        const self = this;
                        const newFileName = randomString(32);
                        self.r = window[$resumeId];
                        
                        // self.$refs.button_action.querySelector('.iconAction').innerHTML = svgPlay;
                        
                        self.r.on('fileAdded', function(file) {
                            self.totalSize = self.r.getSize();
                            self.totalChunk = file.chunks.length;
                            
                            self.size = `0 / ${formatBytes( self.totalSize )}`;
                            self.chunk = `0 / ${self.totalChunk}`;
                            
                            self.$refs.button_action.removeAttribute('disabled');
                            self.$refs.button_action.style.backgroundColor = "#c7d2fe";
                            self.$refs.button_action.style.color = "#3730a3";
                            self.action_text = "Upload"
                            
                            file.fileName = `${newFileName}`;
                            file.uniqueIdentifier = `${$resumeId}`;
                        });
                        
                        self.r.on('chunkingStart', (file) => {
                            self.status = 'Pending';
                            self.$refs.progress_upload.style.backgroundColor = `#854d0e`;
                            self.$refs.progress_upload.style.width = `0%`;
                            
                            self.$refs.progress_status.style.backgroundColor = `#fef9c3`;
                            self.$refs.progress_status.querySelector('.dot').style.backgroundColor = `#854d0e`;
                            self.$refs.progress_status.querySelector('.textStatus').style.color = `#854d0e`;
                            
                            self.$refs.button_action.style.backgroundColor = "#fef08a";
                            self.$refs.button_action.style.color = "#854d0e";
                        });
                        self.r.on('chunkingProgress', (file, ratio) => {
                            const progressUp = Math.floor(ratio * 100);
                            const chunkLength = file.chunks.length;
                            const totalBytes = file.size;
                            const preparedBytes = totalBytes * ratio;
                            
                            self.chunk = `0 / ${chunkLength}`;
                            self.size = `${formatBytes(preparedBytes)}`;
                            self.totalChunk = chunkLength;
                            self.percentage = `${progressUp}%`;
                            
                            self.$refs.progress_upload.style.width = `${progressUp}%`;
                            self.$refs.progress_upload.style.backgroundColor = `#854d0e`;
                        });
                        self.r.on('chunkingComplete', (file) => {
                            const chunkLength = file.chunks.length;
                            
                            self.$refs.progress_upload.style.width = `0%`;
                            self.$refs.progress_upload.style.backgroundColor = `#3730a3`;
                            
                            self.$refs.progress_status.style.backgroundColor = `#e0e7ff`;
                            self.$refs.progress_status.querySelector('.dot').style.backgroundColor = `#3730a3`;
                            self.$refs.progress_status.querySelector('.textStatus').style.color = `#3730a3`;
                            
                            self.totalChunk = chunkLength;
                            self.chunk = `0 / ${self.totalChunk}`;
                            self.percentage = `0%`;
                            self.status = 'Ready';
                        });
                        
                        self.r.on('fileProgress', (file, message) => {
                            const progressUp = Math.floor(file.progress() * 100);
                            const fileChunks = file.chunks.filter(chunk => chunk.status() === 'success');
                            
                            self.$refs.progress_upload.style.width = `${progressUp}%`;
                            self.size = `${formatBytes( self.totalSize * file.progress() )} / ${formatBytes(self.totalSize)}`;
                            self.chunk = `${fileChunks.length} / ${self.totalChunk}`;
                            self.status = 'Progress...';
                            self.percentage = `${progressUp}%`;
                        });
                        
                        self.r.on('fileSuccess', (file, message) => {
                            console.log('Success upload file');
                            self.status = 'Complete';
                        });
                        
                        self.r.on('fileRetry', (file) => {
                            console.log('file retry: ',file);
                        });
                        
                        self.r.on('fileError', (file, message) => {
                            console.warn('Error upload file');
                        });
                        
                        self.r.on('error', (message, file) => {
                            console.log(message);
                            console.log(file);
                        });
                        
                    },
                    
                    action($event) {
                        const file = this.r.files[0];
                        const r = this.r;
                        if (file.isUploading()) {
                            console.log('is uploading: ', file.isUploading());
                            r.pause();
                            console.log(file.progress());
                            
                        } else {
                            
                            r.upload();
                            
                        }
                        
                        
                        // const target = $event.currentTarget;
                        // if (! 'actionUpload' in target.dataset) return;
                        // if (! actionAccept.includes(target.dataset.actionUpload) ) return;
                        // const action = target.dataset.actionUpload;
                        
                        // if (action == 'upload') return this.upload();
                        
                        
                    },
                    
                    upload() {
                        console.log('action tot: ', this.r);
                        console.log('action tot: ', this.r.files[0].isUploading());
                        console.log('action tot: ', this.r.files[0].isPaused());
                        this.r.upload();
                        this.dispatchNotify('info', 'Upload Starting', 'uploading.....');
                        
                    },
                    
                    dispatchNotify($variant = 'info', $title, $message) {
                        this.$dispatch('customnotify', { 
                            variant: $variant, 
                            title: $title,  
                            message: $message, 
                        });
                    },
                    
                }
                
            });
            
        </script>
        
        <script data-navigate-once="true">
            
            // window.Echo.private('process_docs.{{ session()->getId() }}' )
            //     .listen('ProcessNewDocument', ($data) => {
            //         console.log('process new documents...')
            //     });
            
        </script>
        
        
    @endpush
@endonce







<div></div>
