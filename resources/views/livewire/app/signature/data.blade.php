<div class="cDataSignaturesAppDashboard"
    >
    
    @if (! empty($default_signature))
    
    
    
        {{-- <div 
            class="act-showSignaturesDetail group {{ $show_detail ? 'block' : 'hidden' }}"
            >
            <label class="cursor-pointer size-fit block" wire:click='changeShowDetail'>
                
                <div class="cLabelActShowSignaturesDetail flex items-center gap-4">
                    
                    <div class="switchIconCheck border-2 rounded-full w-16 h-6 border-slate-600 bg-transparent transition-all group-has-[:checked]:border-blue-600 group-has-[:checked]:bg-blue-100">
                        <div class="p-1 relative size-full">
                            <div class="switchCheck absolute left-[4%] top-1/2 -translate-y-1/2 w-full transition-all duration-500 ease-out group-has-[:checked]:left-[66%] group-has-[:checked]:ease-in group-has-[:checked]:duration-200">
                                <div class="ballSwitch size-[30%] bg-slate-600 rounded-full aspect-[1/1] transition-all group-has-[:checked]:bg-blue-600 group-has-[:checked]:contrast-200"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="textLabelAct select-none">
                        <div class="tx text-sm">
                            <p>Show signatures detail</p>
                        </div>
                    </div>
                    
                </div>
                
                <input 
                    type="checkbox" 
                    class="sr-only"
                    {{ $show_detail ? 'checked' : '' }}
                    @change="$dispatch('statusdetailshow', {status: $el.checked})"
                    >
                
            </label>
        </div> --}}
        
        <div class="ctr-defaultSignaturesSet bg-white shadow-md shadow-black/40 rounded-lg mt-2"
            {{-- wire:key='item-{{ $default_signature->id }}' --}}
        >
            <div class="cDefaultSignaturesSet p-4">
                
                <div class="wrapper-signaturesImages [&>.grid]:grid-cols-8 [&>.grid]:gap-2 transition-all">
                    
                    {{-- Titles Signature --}}
                    <div class="titleListSignatures grid">
                        <div class="titleSignature col-span-5">
                            <div class="textTitle text-[0.925rem] leading-[1.175rem]">
                                <div class="tx font-medium">
                                    <p>Signature</p>
                                </div>
                            </div>
                        </div>
                        <div class="titleParaf col-span-3">
                            <div class="textTitle text-[0.925rem] leading-[1.175rem]">
                                <div class="tx font-medium">
                                    <p>Paraf</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Details Signature --}}
                    <div 
                        x-data="{ shown: {{ $show_detail }} }"
                        class="detailListSignatures grid"
                        style="visibility: {{ $show_detail ? 'visible' : 'hidden' }}; height: auto"
                        :style="shown ? `visibility: visible; height: auto;` : `visibility: hidden; height: 0px;` "
                        @statusdetailshow.window="shown = $event.detail.status"
                        >
                        <div class="detailDefaultSignature col-span-5">
                            <div class="detailDefault">
                                <div class="textDetailName text-[0.75rem] leading-[1.15rem]">
                                    <p>Signed by {{ auth()->user()->userPersonal->fullname }}</p>
                                </div>
                                <div class="textDetailTime text-slate-400 text-[0.675rem] leading-[1rem]">
                                    <p>{{ \Carbon\Carbon::now() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="detailDefaultParaf col-span-3">
                            <div class="detailDefault">
                                <div class="textDetailName text-[0.75rem] leading-[1.15rem]">
                                    <p>Signed by {{ auth()->user()->userPersonal->fullname }}</p>
                                </div>
                                <div class="textDetailTime text-slate-400 text-[0.675rem] leading-[1rem]">
                                    <p>{{ \Carbon\Carbon::now() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Images Signature --}}
                    <div class="imageListSignatures mt-2 grid">
                        <div class="imageDefaultSignature col-span-5 size-full" 
                            x-data="{ show: false, }"
                            >
                            <div 
                                class="signatureImage rounded-lg relative size-full transition-all bg-gray-100 overflow-hidden"
                                style="filter: blur(1px);"
                                :style="show ? '' : `filter: blur(1px)`"
                                :class="show ? '' : 'animate-pulse'"
                                
                                >
                                <div 
                                    class="image size-full"
                                    style="background: url({{ route('drive.files.entity_signature', [ 'token' => $default_signature['signature']['token_thumbnail'] ] ) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"
                                    wire:ignore.self
                                    >
                                    <img 
                                        src="{{ route('drive.files.entity_signature', [ 'token' => $default_signature['signature']['token_original'] ] ) }}" 
                                        @load="show = true; $el.parentElement.removeAttribute('style')"
                                        alt="" 
                                        class="size-full object-cover"
                                        data-signature-image="default_signature"
                                        loading="lazy"
                                        {{-- style="visibility: hidden"
                                        :style="show ? `visibility: visible` : `visibility: hidden`    " --}}
                                        >
                                    <div class="loading size-full bg-gray-200/40 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 invisible backdrop-blur-sm"
                                        wire:loading.class.remove='invisible'
                                    ></div>
                                </div>
                                
                            </div>
                        </div>
                        <div 
                            class="imageDefaultParaf col-span-3 size-full"
                            x-data="{ show: false, }"
                            >
                            <div 
                                class="parafImage rounded-lg relative aspect-[1/1] transition-all bg-gray-100 overflow-hidden"
                                style="filter: blur(1px);"
                                :style="show ? '' : `filter: blur(1px)`"
                                :class="show ? '' : 'animate-pulse'"
                                
                                >   
                                <div class="image size-full"
                                    style="background: url({{ route('drive.files.entity_signature', [ 'token' => $default_signature['paraf']['token_thumbnail'] ] ) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"
                                    wire:ignore.self
                                    >
                                    <img 
                                        src="{{ route('drive.files.entity_signature', [ 'token' => $default_signature['paraf']['token_original'] ] ) }}" 
                                        @load="show = true; $el.parentElement.removeAttribute('style')"
                                        alt="" 
                                        class="size-full object-cover"
                                        data-signature-image="default_paraf"
                                        loading="lazy"
                                        >
                                    <div class="loading size-full bg-gray-200/40 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 invisible backdrop-blur-sm"
                                        wire:loading.class.remove='invisible'
                                    ></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="statusActiveDefault mt-4 flex justify-end">
                    <div class="activeDefault size-fit px-8 py-2 bg-gradient-to-r from-[#297DDE] to-[#004DA6] rounded-md">
                        <div class="textStatus text-white">
                            <div class="tx text-xs">
                                <p>Default</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    @endif
    
    @if(! empty($list_signatures))
        <div class="listSignatures mt-6">
            @foreach ($list_signatures as $id => $value)
                
                <div
                    class="ctr-itemSignaturesSet bg-white shadow-md shadow-black/40 rounded-lg mt-2"
                    x-data="actionSignatures('{{ $id }}')"
                    wire:key='item-{{ $id }}'
                    wire:ignore
                    >
                    
                    <div class="cItemSignaturesSet p-4">
                        
                        <div class="wrapper-signaturesImages [&>.grid]:grid-cols-8 [&>.grid]:gap-2">
                            
                            {{-- Titles Signature --}}
                            <div class="titleListSignatures grid">
                                <div class="titleSignature col-span-5">
                                    <div class="textTitle text-[0.925rem] leading-[1.175rem]">
                                        <div class="tx font-medium">
                                            <p>Signature</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="titleParaf col-span-3">
                                    <div class="textTitle text-[0.925rem] leading-[1.175rem]">
                                        <div class="tx font-medium">
                                            <p>Paraf</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Images Signature --}}
                            <div class="imageListSignatures mt-2 grid">
                                <div class="imageDefaultSignature col-span-5 size-full" 
                                    x-data="{ show: $persist(false).as('item-{{ $id }}') }"
                                    {{-- x-data="{ show: false, imgSrc: '{{ route('drive.files.preview_signature', ['filename' => $signature->signature->file_name]) }}', thumbnailSrc: '{{ route('drive.files.preview_signature', ['filename' => $signature->signature->file_name . '_thumbnail']) }}'}" --}}
                                    >
                                    <div 
                                        class="signatureImage rounded-lg relative aspect-[16/9] size-full transition-all bg-gray-100 overflow-hidden"
                                        style="filter: blur(1px);"
                                        :style="show ? '' : `filter: blur(1px)`"
                                        :class="show ? '' : 'animate-pulse'"
                                        
                                        >
                                        <div 
                                            class="image size-full"
                                            style="background: url({{ route('drive.files.entity_signature', [ 'token' => $value['signature']['token_thumbnail'] ]) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"
                                            {{-- :style="show ? `` : `background: url('${thumbnailSrc}'); background-repeat: no-repeat; background-position: center; background-size: cover;`" --}}
                                            >
                                            <img 
                                                src="{{ route('drive.files.entity_signature', [ 'token' => $value['signature']['token_original'] ]) }}" 
                                                {{-- :src="imgSrc" --}}
                                                @load="show = true; $el.parentElement.removeAttribute('style')"
                                                {{-- src="{{ $signature->baseImage->signature }}"  --}}
                                                alt="" 
                                                class="size-full object-cover"
                                                data-signature-image="list_signature"
                                                loading="lazy"
                                                style="visibility: hidden"
                                                :style="show ? `visibility: visible` : `visibility: hidden`    "
                                                >
                                            
                                            <div class="loading size-full bg-gray-200/40 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 invisible backdrop-blur-sm"
                                                wire:loading.class.remove='invisible'
                                            ></div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div 
                                    class="imageDefaultParaf col-span-3 size-full"
                                    x-data="{ show: false }"
                                    {{-- x-data="{ show: false, imgSrc: '{{ route('drive.files.preview_signature', ['filename' => $signature->paraf->file_name]) }}', thumbnailSrc: '{{ route('drive.files.preview_signature', ['filename' => $signature->paraf->file_name . '_thumbnail']) }}'}" --}}
                                    >
                                    <div 
                                        class="parafImage rounded-lg relative aspect-[1/1] transition-all bg-gray-100 overflow-hidden"
                                        style="filter: blur(1px);"
                                        :style="show ? '' : `filter: blur(1px)`"
                                        :class="show ? '' : 'animate-pulse'"
                                        
                                        >   
                                        <div class="image size-full"
                                            style="background: url({{ route('drive.files.entity_signature', [ 'token' => $value['paraf']['token_thumbnail'] ]) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"
                                            {{-- :style="show ? `` : `background: url('${thumbnailSrc}'); background-repeat: no-repeat; background-position: center; background-size: cover;`" --}}
                                            >
                                            <img 
                                                src="{{ route('drive.files.entity_signature', [ 'token' => $value['paraf']['token_original'] ]) }}"
                                                {{-- :src="imgSrc" --}}
                                                @load="show = true; $el.parentElement.removeAttribute('style')"
                                                alt="" 
                                                class="size-full object-cover"
                                                data-signature-image="list_paraf"
                                                loading="lazy"
                                                {{-- wire:loading.class='blur-sm' --}}
                                                
                                                >
                                                
                                            <div class="loading size-full bg-gray-200/40 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 invisible backdrop-blur-sm"
                                                wire:loading.class.remove='invisible'
                                            ></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        
                        <div class="actionSignaturesSet mt-4 flex items-center justify-end gap-1">
                            <div class="act-activateSignature group">
                                <button 
                                    type="button"
                                    class="hover:bg-gray-200 rounded-lg h-8 px-2 flex items-center justify-center w-36"
                                    @click="setDefaultSignature"
                                    wire:loading.attr='disabled'
                                    wire:loading.class='bg-gray-200'
                                    >
                                    <div 
                                        class="textButton text-blue-600"
                                        wire:loading.class='hidden'
                                        >
                                        <div class="tx text-[0.825rem] leading[1rem]">
                                            <p>Set as default</p>
                                        </div>
                                    </div>
                                    
                                    <div 
                                        class="loading iconButton size-6 items-center justify-center hidden"
                                        wire:loading.class='flex'
                                        wire:loading.class.remove='hidden'
                                        >
                                        <div class="icon animate-spin">
                                            <i class="fas fa-circle-notch"></i>
                                        </div>
                                    </div>
                                    
                                </button>
                            </div>
                            <div class="act-deleteSignature group">
                                <button
                                    type="button"
                                    class="block hover:bg-gray-200 rounded-lg h-8"
                                    @click="deleteSignature"
                                    wire:loading.attr='disabled'
                                    >
                                    
                                    {{-- <div class="textButton">
                                        <div class="tx">
                                            <p>Delete</p>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="iconButton text-slate-400 size-full aspect-[2/1] flex items-center justify-center group-hover:text-red-600">
                                        <div class="icon">
                                            <i class="fas fa-trash-can"></i>
                                        </div>
                                    </div>
                                    
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            @endforeach
            
        </div>
    @endif
    
</div>

@once
    @push('global-custom-content')
        
        @script
            <script data-navigate-once>
                
                
                Alpine.data('actionSignatures', ($id_signature) => {
                    const $token = '{{ csrf_token() }}';
                    const modalDelete = `
                        <div class="wrapper-deleteSignature fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/20 size-full z-20 flex items-center justify-center">
                            <div class="ctr-DeleteSignature transition-all animate-fade-in w-96 px-4 py-6 bg-white rounded-lg shadow-sm shadow-black/40">
                                <div class="cDeleteSignature">
                                    <div class="titleDelete">
                                        <div class="textTitle text-center text-xl font-semibold">
                                            Delete Signature
                                        </div>
                                    </div>
                                    
                                    <div class="iconDanger flex items-center justify-center mt-4 h-20">
                                        <div class="icon text-6xl text-red-600">
                                            <i class="fas fa-circle-exclamation"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="descriptionDelete mt-12">
                                        <div class="descText text-center text-sm text-gray-700">
                                            <p>
                                                Are you sure you want to delete this signature? <br>
                                                This action <span class="font-semibold text-red-600">cannot be undone</span>.
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="actDeleteSignature flex items-center justify-around mt-12">
                                        <div class="itm-cancelDelete">
                                            <button
                                                class="btn-cancel block w-24 py-1.5 rounded-lg bg-gray-100"
                                            >
                                                <div class="textAction">
                                                    <div class="tx text-sm">
                                                        <p>Cancel</p>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                        <div class="itm-acceptDelete">
                                            <button
                                                class="btn-accept block w-24 py-1.5 rounded-lg bg-red-100"
                                            >
                                                <div class="textAction">
                                                    <div class="tx text-sm text-red-600">
                                                        <p>Accept</p>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    return {
                        init() {
                            
                        },
                        
                        setDefaultSignature() {
                            
                            const $saveData = {
                                _token: $token,
                                id: $id_signature,
                            }
                            
                            this.$wire.updateDefaultSignatures($saveData);
                            
                            console.log('test set default');
                        },
                        
                        deleteSignature() {
                            const $saveData = {
                                _token: $token,
                                id: $id_signature,
                            }
                            // Hapus semua modal popup dengan class yang sama (jika ada)
                            document.querySelectorAll('.wrapper-deleteSignature').forEach(el => el.remove());
                            
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = modalDelete;
                            const modalElement = tempDiv.firstElementChild;
                            
                            document.body.appendChild(modalElement);
                            
                            const self = this;
                            modalElement.querySelector('.btn-cancel').addEventListener('click', () => {
                                modalElement.remove();
                            });
                            
                            modalElement.querySelector('.btn-accept').addEventListener('click', () => {
                                self.$wire.deleteSignatures($saveData);
                                modalElement.remove();
                            });
                            
                            
                            // this.$wire.deleteSignatures($saveData);
                        },
                        
                    };
                    
                });
                
            </script>
        @endscript
        
    @endpush
@endonce
