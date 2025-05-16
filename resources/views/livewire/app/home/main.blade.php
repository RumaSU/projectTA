@push('dashboard-custom-main-content')
    
    <header class="ctr-headerMainContent">
        <div class="cHeaderMainContent space-y-0.5">
            <div class="welcome-user">
                <div class="txWelcomeUser text-xl poppins-semibold">
                    <h2>Welcome @{{ $user->name }}</h2>
                </div>
            </div>
            <div class="descWelcome-user">
                <div class="txDescWelcome text-sm">
                    <p>Hi @{{ $user->name }} Discover your progress and important updates in your dashboard.</p>
                </div>
            </div>
        </div>
    </header>
    
    @php
        $randStatus = rand(0, 1);
        $descCertStatus = [
            'Your certificate is currently active and ready for use.',
            'Your certificate will expire in <b>'. rand(1, 30) .' days</b>. Please renew soon.',
            'Please generate a valid certificate to enable digital signing of documents.'
        ];
        $listInfoAction = [
            (object) [
                'title' => 'Digital Signature Quota',
                'icon' => 'fas fa-signature',
                'quota' => rand(0, 10),
                'quotaSize' => 'text-3xl',
                'description' => 'Each time you sign a document or request a signature, one quota will be used.',
                'optDesc' => (object) [
                    'status' => false,
                    'text' => '',
                ],
                'action' => (object) [
                    'ref' => '#',
                    'bgColor' => 'bg-[#FFCA28]',
                    'txColor' => 'text-[#533F00]',
                    'padding' => 'px-4 py-1',
                    'icon' => 'fas fa-signature',
                    'iconSize' => 'text-base',
                    'label' => 'Sign Document',
                ],
                'bgColor' => 'bg-gradient-to-tr from-[#004DA6] to-[#1E76DA]',
            ],
            (object) [
                'title' => 'Documents Quota',
                'icon' => 'fas fa-book-open',
                'quota' => rand(0, 10),
                'quotaSize' => 'text-3xl',
                'description' => 'This quota limits the total number of documents you can store and manage.',
                'optDesc' => (object) [
                    'status' => false,
                    'text' => '',
                ],
                'action' => (object) [
                    'ref' => '#',
                    'bgColor' => 'bg-[#FFCA28]',
                    'txColor' => 'text-[#533F00]',
                    'padding' => 'px-4 py-1',
                    'icon' => 'fas fa-book-open',
                    'iconSize' => 'text-base',
                    'label' => 'Upload Document',
                ],
                'bgColor' => 'bg-gradient-to-tr from-[#004DA6] to-[#1E76DA]',
            ],
            (object) [
                'title' => 'Certificate Status',
                'icon' => 'fas fa-certificate',
                'quota' => $randStatus ? 'Active' : 'Not Active',
                'quotaSize' => 'text-xl',
                'description' => $randStatus ? $descCertStatus[rand(0,1)] : end($descCertStatus),
                'optDesc' => (object) [
                    'status' => true,
                    'text' => 'Check yout certificate',
                ],
                'action' => (object) [
                    'ref' => $randStatus ? '#check' : '#activate',
                    'bgColor' => 'bg-transparent',
                    'txColor' => 'text-white',
                    'padding' => '',
                    'icon' => 'fas fa-arrow-up-right-from-square',
                    'iconSize' => 'text-2xl',
                    'label' => '',
                ],
                'bgColor' => 'bg-gradient-to-tr from-[#'. ($randStatus ? '004DA6' : '676767') . '] to-[#'. ($randStatus ? '1E76DA' : '272727') .']',
            ],
        ];
    @endphp
    
    
    <section class="sec-userMainInfo mt-8 grid gap-6 2xl:grid-cols-4 sm:grid-cols-3 grid-cols-1 pb-4 border-b-2 border-slate-200">
        @foreach ($listInfoAction as $itemInfoAction)
            <div class="itm-cardInfoAction{{ implode('', explode(' ', $itemInfoAction->title)) }}
                    px-4 py-3 {{ $itemInfoAction->bgColor }} text-white rounded-xl shadow-md shadow-black/40
                ">
                
                <div class="cCardInfoAction">
                    <div class="topMainCardInfo flex justify-between h-[5.5rem]">
                        <div class="leftCardInfo">
                            <div class="titleCardInfo">
                                <div class="txTitle text-xl">
                                    <p>{{ $itemInfoAction->title }}</p>
                                </div>
                            </div>
                            <div class="quotaStatusCardInfo mt-4">
                                <div class="txQuota {{ $itemInfoAction->quotaSize }}">
                                    <p>{{ $itemInfoAction->quota }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="rightCardInfo">
                            <div class="iconCardInfo flex items-center justify-center size-16 p-2 rounded-full border border-white">
                                <div class="icon text-4xl">
                                    <i class="{{ $itemInfoAction->icon }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="descCardInfo mt-2 h-12">
                        <div class="txDescCardInfo text-xs tracking-tighter">
                            <p>{!! $itemInfoAction->description !!}</p>
                            @if ($itemInfoAction->optDesc->status && $randStatus)
                                <p class="mt-1">{{ $itemInfoAction->optDesc->text }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="actionCardInfo">
                        <a href="{{ $itemInfoAction->action->ref }}" class="hrefActionCardInfo{{ implode('', explode(' ', $itemInfoAction->title)) }}
                                block w-fit rounded-lg {{ $itemInfoAction->action->padding . ' ' . $itemInfoAction->action->bgColor . ' ' . $itemInfoAction->action->txColor }}
                            ">
                            <div class="cHrefActionCardInfo flex items-center gap-3">
                                <div class="icnHrefActionCardInfo">
                                    <div class="icon {{ $itemInfoAction->action->iconSize }}">
                                        <i class="{{ $itemInfoAction->action->icon }}"></i>
                                    </div>
                                </div>
                                @if ($itemInfoAction->action->label)
                                    <div class="lblHrefActionCardInfo">
                                        <div class="txLblHref text-sm">
                                            <p>{{ $itemInfoAction->action->label }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    
                </div>
            </div>
        @endforeach
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
                        
                        @if ($haveDoc)
                            <div class="lblDMainRecentDocument bg-white px-4 py-2 rounded-full">
                                <div class="lstLabelRecentDocument grid grid-cols-4 gap-2 text-gray-700 text-lg">
                                    <div class="itmLabelRecentDocument col-span-2">
                                        <div class="txLabel">
                                            <p>Document</p>
                                        </div>
                                    </div>
                                    <div class="itmLabelRecentDocument">
                                        <div class="txLabel">
                                            <p>Status</p>
                                        </div>
                                    </div>
                                    <div class="itmLabelRecentDocument">
                                        <div class="txLabel">
                                            <p>Last accessed</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="lstDMainRecentDocument mt-4">
                                @for ($i = 0; $i < ($totalDoc > 5 ? 5 : $totalDoc); $i++)
                                    <div class="itm-dMainRecentDocument bg-white px-4 py-2 my-1 rounded-xl relative group/parentItem">
                                        <div class="cItmDMainRecenetDocument grid grid-cols-4 gap-2 text-gray-700">
                                            <div class="itmDocName col-span-2">
                                                <div class="txDocName">
                                                    <p>@{{ $document->name }}</p>
                                                </div>
                                            </div>
                                            <div class="itmStatusDoc">
                                                <div class="txStatusDoc">
                                                    <p>@{{ $document->status }}</p>
                                                </div>
                                            </div>
                                            <div class="itmLastAccessDoc">
                                                <div class="txAccessDoc">
                                                    <p>@{{ $document->accessDate }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wrapperActionDMainRecentDocument bg-white pl-4 absolute right-2 top-1/2 -translate-y-1/2 hidden group-hover/parentItem:block">
                                            <div class="lstActionDMainRecentDocument flex items-center gap-2">
                                                <div class="itm-actionDMainRecentDocument" aria-label="view document">
                                                    <button class="buttonAction size-8 flex items-center justify-center rounded-lg group/actionItem hover:bg-slate-200">
                                                        <div class="iconView">
                                                            <div class="icon text-lg text-gray-600 group-hover/actionItem:text-gray-900">
                                                                <i class="fas fa-eye"></i>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="itm-actionDMainRecentDocument" aria-label="info document">
                                                    <button class="buttonAction size-8 flex items-center justify-center rounded-lg group/actionItem hover:bg-slate-200">
                                                        <div class="iconInfo flex items-center justify-center rounded-full size-7 border border-black">
                                                            <div class="icon  text-gray-600 group-hover/actionItem:text-gray-900">
                                                                <i class="fas fa-info"></i>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                
                                @if ($totalDoc > 5)
                                    <a href="" class="viewMoreDocument mt-2 p-2 flex items-center justify-center bg-white hover:bg-slate-200 rounded-xl">
                                        <div class="cViewMoreDocument flex items-center gap-4">
                                            <div class="iconViewMore flex items-center justify-center size-8 rounded-full border border-black">
                                                <div class="icon text-xl">
                                                    <i class="fas fa-ellipsis"></i>
                                                </div>
                                            </div>
                                            <div class="txViewMore">
                                                <p>View More</p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            
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

<div></div>