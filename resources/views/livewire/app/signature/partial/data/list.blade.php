<div class="listSignatures mt-6">
    
    @if ($list_signatures)
        @foreach ($list_signatures as $signature)
            <div class="ctr-itemSignaturesSet bg-white shadow-md shadow-black/40 rounded-lg mt-2">
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
                                x-data="{ show: false, }"
                                >
                                <div 
                                    class="signatureImage rounded-lg relative size-full transition-all bg-gray-100"
                                    style="filter: blur(1px);"
                                    :style="show ? '' : `filter: blur(1px)`"
                                    :class="show ? '' : 'animate-pulse'"
                                    
                                    >
                                    <div 
                                        class="image size-full"
                                        style="background: url({{ route('drive.files.preview_signature', ['filename' => $signature->signature->file_name . '_thumbnail']) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"
                                        
                                        >
                                        <img 
                                            src="{{ route('drive.files.preview_signature', ['filename' => $signature->signature->file_name]) }}" 
                                            @load="show = true; $el.parentElement.removeAttribute('style')"
                                            alt="" 
                                            class="size-full object-cover"
                                            data-signature-image="default_signature"
                                            loading="lazy"
                                            style="visibility: hidden"
                                            :style="show ? `visibility: visible` : `visibility: hidden`    "
                                            >

                                            
                                    </div>
                                    
                                </div>
                            </div>
                            <div 
                                class="imageDefaultParaf col-span-3 size-full"
                                x-data="{ show: false, }"
                                >
                                <div 
                                    class="parafImage rounded-lg relative aspect-[1/1] transition-all bg-gray-100"
                                    style="filter: blur(1px);"
                                    :style="show ? '' : `filter: blur(1px)`"
                                    :class="show ? '' : 'animate-pulse'"
                                    
                                    >   
                                    <div class="image size-full"
                                        style="background: url({{ route('drive.files.preview_signature', ['filename' => $signature->paraf->file_name . '_thumbnail']) }}); background-repeat: no-repeat; background-position: center; background-size: cover;"

                                        >
                                        <img 
                                            src="{{ route('drive.files.preview_signature', ['filename' => $signature->paraf->file_name]) }}" 
                                            @load="show = true; $el.parentElement.removeAttribute('style')"
                                            alt="" 
                                            class="size-full object-cover"
                                            data-signature-image="default_paraf"
                                            loading="lazy"
                                            >
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    
                    
                    <div class="actionSignaturesSet mt-4 flex justify-end">
                        <div class="act-activateSignature">
                            
                        </div>
                        <div class="act-deleteSignature">
                            
                        </div>
                        
                        
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
        @endforeach
    @endif
    
</div>