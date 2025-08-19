@push('dashboard-custom-main-content')
    
    <header class="ctr-headerMainContent">
        <div class="cHeaderMainContent space-y-0.5">
            <div class="welcome-user">
                <div class="txWelcomeUser text-xl poppins-semibold">
                    <h2>Welcome {{ auth()->user()->userPersonal->fullname }}</h2>
                </div>
            </div>
            <div class="descWelcome-user">
                <div class="txDescWelcome text-sm">
                    <p>Hi {{ auth()->user()->userPersonal->fullname }} Discover your progress and important updates in your dashboard.</p>
                </div>
            </div>
        </div>
    </header>
    
    <section class="section-card-user-info mt-8 pb-4 border-b-2 border-slate-200">
        @livewire('app.home.component.cards')
    </section>
    
    
    {{-- <section class="sec-getStartedApp mt-12">
        <header class="ctr-headerGetStartedApp">
            <div class="cHeaderGetStartedApp">
                <div class="titleHeaderApp ">
                    <div class="txHeader text-xl font-semibold text-gray-800">
                        <h3>Get Started with Your Digital Signature</h3>
                    </div>
                </div>
                <div class="descHeaderApp">
                    <div class="txDesc text-sm text-gray-600">
                        <p>Start securing your documents in just a few easy steps. Follow the quick actions below to upload, sign, and manage your digital files efficiently.</p>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="ctr-lstCardGetStartedApp mt-4">
            <div class="cLstCardGetStartedApp grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $lstCardGetStartedApp = [
                        (object) [
                            'title' => 'Upload Document',
                            'icon' => (object) [
                                'i' => 'fas fa-upload',
                                'color' => 'text-slate-500',
                                'colorHover' => 'text-[#1D4ED8]',
                                'size' => 'text-4xl'
                            ],
                            'description' => 'Select and upload your PDF document to begin the signing process securely.'
                        ],
                        (object) [
                            'title' => 'Sign Document',
                            'icon' => (object) [
                                'i' => 'fas fa-feather-pointed',
                                'color' => 'text-slate-500',
                                'colorHover' => 'text-[#1D4ED8]',
                                'size' => 'text-4xl'
                            ],
                            'description' => 'Apply your digital signature instantly with full legal validity and tamper-proof protection.'
                        ],
                        (object) [
                            'title' => 'Verify & Archive',
                            'icon' => (object) [
                                'i' => 'fas fa-file-circle-check',
                                'color' => 'text-slate-500',
                                'colorHover' => 'text-[#1D4ED8]',
                                'size' => 'text-4xl'
                            ],
                            'description' => 'Validate the signature status or move your document to archive for future reference.'
                        ],
                    ];
                @endphp
                
                @for ($iCSA = 0; $iCSA < count($lstCardGetStartedApp); $iCSA++)
                    <div class="itm-cardGetStartedApp{{ implode('', explode(' ', $lstCardGetStartedApp[$iCSA]->title)) }}
                        bg-slate-100 p-4 rounded-xl shadow hover:shadow-md transition group">
                        
                        <div class="cCardGetStartedApp{{ implode('', explode(' ', $lstCardGetStartedApp[$iCSA]->title)) }}">
                            <div class="headerCCardGetStarted flex justify-between">
                                <div class="lftHeaderCCard flex items-center gap-2">
                                    <div class="numberStartedCard size-10 flex items-center justify-center rounded-full border border-black">
                                        <div class="txNumber text-2xl">
                                            <p>{{ $iCSA+1 }}</p>
                                        </div>
                                    </div>
                                    <div class="titleHeaderCCard">
                                        <div class="txTitle text-xl text-[#0F172A]">
                                            <strong class="font-semibold">{{ $lstCardGetStartedApp[$iCSA]->title }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="rghtHeaderCCard">
                                    <div class="iconStartedCard">
                                        <div class="icon {{ $lstCardGetStartedApp[$iCSA]->icon->size. ' ' .$lstCardGetStartedApp[$iCSA]->icon->color}} group-hover:{{ $lstCardGetStartedApp[$iCSA]->icon->colorHover }} transition">
                                            <i class="{{ $lstCardGetStartedApp[$iCSA]->icon->i }}"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mainCCardGetStarted mt-12">
                                <div class="descCard">
                                    <div class="txDesc text-sm text-[#64748B]">
                                        <p>{{ $lstCardGetStartedApp[$iCSA]->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                @endfor
                
            </div>
        </div>
    </section> --}}
    
    <section class="sec-recentDocument mt-12">
        <div class="ctr-mainRecentDocument">
            <div class="cMainRecentDocument">
                <header class="headerMainRecentDocument">
                    <div class="titleHeaderMainRecentDocument">
                        <div class="txTitle text-lg font-semibold">
                            <h3>Recently Document</h3>
                        </div>
                    </div>
                </header>
                
                @php
                    $haveDoc = rand(0,1);
                    $totalDoc = rand(0, 25);
                @endphp
                
                <div class="ctr-dMainRecentDocument bg-[#F1F1F1] p-2 mt-2 rounded-2xl">
                    <div class="cDMainRecentDocument">
                        
                        @livewire('app.documents.data')
                        
                    </div>
                </div>
                
                
            </div>
        </div>
    </section>
    
    {{-- <section class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Get Started with Your Digital Signature</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            Ikuti langkah cepat untuk mulai menggunakan layanan tanda tangan digital kami secara aman dan efisien.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl text-blue-600 dark:text-blue-400 mb-2">📤</div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-1">Upload Dokumen</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Unggah file PDF yang ingin ditandatangani dengan cepat dan aman.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl text-yellow-500 dark:text-yellow-400 mb-2">✍️</div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-1">Tanda Tangan Digital</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Tambahkan tanda tangan digital secara langsung di dalam dokumen.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl text-green-500 dark:text-green-400 mb-2">📑</div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-1">Verifikasi & Arsip</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Cek keabsahan dokumen atau arsipkan untuk akses di kemudian hari.
                </p>
            </div>
        </div>
    </section> --}}

    
    
@endpush

@once
    @push('global-custom-content')
        @livewire('app.sign.tool.configure-type', [null, true])
    @endpush
@endonce

<div></div>