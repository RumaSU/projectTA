<div class="content-section-card-user flex flex-wrap gap-2">
    
    @php
        $main_cards = [
            [
                'icon' => 'fas fa-signature',
                'title' => 'Documents Signed',
                'description' => 'Total documents you have signed.',
                'additional' => $total_signed,
                'action' => [
                    'icon' => 'fas fa-signature',
                    'link' => route('app.documents.main'),
                    'text' => 'Sign Document'
                ]
            ],
            [
                'icon' => 'fas fa-book-open',
                'title' => 'My Documents',
                'description' => 'Documents you own.',
                'additional' => $total_document,
                'action' => [
                    'icon' => 'fas fa-book-open',
                    'link' => route('app.uploads.document'),
                    'text' => 'Upload Document'
                ]
            ],
        ];
        
        $main_cards = json_decode(json_encode($main_cards));
    @endphp
    
    @foreach ($main_cards as $card)
        <div 
            class="item-card-user-info px-4 py-3 bg-gradient-to-tr from-[#004DA6] to-[#1E76DA] text-white rounded-xl shadow-md shadow-black/40 w-full sm:w-80" 
            data-card-info="{{ $card->title }}">
            <div class="content-item-card-user-info">
                
                <div class="main-card-info flex justify-between">
                    <div class="left-info">
                        <div class="title-card text-xl">
                            <div class="text">
                                <p>{{ $card->title }}</p>
                            </div>
                        </div>
                        
                        <div class="additional-info h-16 mt-1">
                            <div class="text text-3xl">
                                <p>{{ $card->additional }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="right-info">
                        <div class="icon-info flex items-center justify-center size-16 p-2 rounded-full border border-white">
                            <div class="icon text-4xl">
                                <i class="{{ $card->icon }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="description-card-info my-1 h-12">
                    <div class="text-descrption text-sm">
                        <p>{{ $card->description }}</p>
                    </div>
                </div>
                
                <div class="action-card-info mt-2">
                    <a 
                        class="bg-[#FFCA28] text-[#533F00] block px-4 py-1 rounded-lg size-fit"
                        href="{{ $card->action->link }}" 
                        wire:navigate>
                        <div class="content-action-info flex items-center gap-2">
                            <div class="icon-action">
                                <div class="icon">
                                    <i class="{{ $card->action->icon }}"></i>
                                </div>
                            </div>
                            <div class="text-action">
                                <div class="text-action text-sm">
                                    <p>{{ $card->action->text }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                
            </div>
        </div>
    @endforeach
    
    @php
        $background = $certificate_status
            ? 'from-[#004DA6] to-[#1E76DA]'
            : 'from-[#676767] to-[#272727]';
        $additional_info = $certificate_status
            ? 'Active'
            : 'Not Active';
        $description = $certificate_status
            ? 'Your certificate is active and ready for signing documents.'
            : 'Generate a valid certificate to enable digital signing of documents.';
    @endphp
    
    <div 
        class="item-card-user-info px-4 py-3 bg-gradient-to-tr from-[#004DA6] to-[#1E76DA] text-white rounded-xl shadow-md shadow-black/40 w-full sm:w-80" 
        data-card-info="Certificate">
        <div class="content-item-card-user-info">
            
            <div class="main-card-info flex justify-between">
                <div class="left-info">
                    <div class="title-card text-xl">
                        <div class="text">
                            <p>Certificate Status</p>
                        </div>
                    </div>
                    
                    <div class="additional-info h-16 mt-1">
                        <div class="text text-xl">
                            <p>{{ $additional_info }}</p>
                        </div>
                    </div>
                </div>
                <div class="right-info">
                    <div class="icon-info flex items-center justify-center size-16 p-2 rounded-full border border-white">
                        <div class="icon text-4xl">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="description-card-info my-1 h-12">
                <div class="text-descrption text-sm">
                    <p>{{ $description }}</p>
                </div>
            </div>
            
            <div class="action-card-info mt-2">
                <a 
                    class="bg-transparent text-white block size-fit"
                    href="#" 
                    wire:navigate>
                    <div class="content-action-info flex items-center gap-2">
                        <div class="icon-action">
                            <div class="icon text-2xl">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
        </div>
    </div>
    
    
</div>
