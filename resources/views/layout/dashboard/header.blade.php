
<div class="cHeaderDashboard flex justify-between items-center">
    <div class="homeHeaderDashboard">
        <a href="{{ route('app.dashboard.home') }}" class="hrefHomeDashboard block" wire:navigate>
            <div class="cHrefHomeDashboard flex items-center gap-4">
                <div class="icnHomeDashboard">
                    <img src="{{ asset('main/icon/logo.png') }}" alt="" class="object-center object-cover size-14">
                </div>
                {{-- <div class="txHomeDashboard poppins-semibold text-[#FFD700]"> --}}
                <div class="txHomeDashboard -space-y-1.5">
                    <div class="mainTitleApp poppins-semibold">
                        <h1>Digital Signature</h1>
                    </div>
                    <div class="additionalTitleApp text-sm">
                        <p>Lorem Ipsum</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <nav class="navHeaderDashboard">
        <div class="cNavHeaderDashboard flex items-center gap-8">
            <div class="ctr-mainNavHeaderDashboard">
                <div class="cMainNavHeaderDashboard">
                    <div class="ctr-listMainNavHeaderDashboard relative">
                        <div class="cListMainNavHeaderDashboard flex gap-2">
                            <div class="itm-navHeaderDashboard relative" aria-label="notification">
                                <div class="actionNav">
                                    {{-- <button class="actionItmNav flex items-center justify-center size-10 rounded-lg text-[#C8A500] border border-[#8C7000] bg-[#121212] hover:bg-[#181818] hover:text-[#FFD700] hover:border-[#C8A500] shadow-sm shadow-transparent hover:shadow-[#F1C40F]"> --}}
                                    <button class="actionItmNav flex items-center justify-center size-10 rounded-lg bg-[#E4E4E4] hover:bg-[#dbdbdb] border border-[#b2b6bb]">
                                        <div class="icnAction text-xl text-[#363636]">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div 
                                x-data="{ modalStatus: false }" 
                                class="itm-navHeaderDashboard relative" 
                                aria-label="background-process"
                                >
                                <div class="actionNav">
                                    {{-- <button class="actionItmNav flex items-center justify-center size-10 rounded-lg text-[#C8A500] border border-[#8C7000] bg-[#121212] hover:bg-[#181818] hover:text-[#FFD700] hover:border-[#C8A500] shadow-sm shadow-transparent hover:shadow-[#F1C40F]"> --}}
                                    <button 
                                        @click="modalStatus = !modalStatus"
                                        class="actionItmNav flex items-center justify-center size-10 rounded-lg bg-[#E4E4E4] hover:bg-[#dbdbdb] border border-[#b2b6bb]">
                                        <div class="icnAction text-xl text-[#363636]">
                                            <i class="fas fa-circle-nodes"></i>
                                        </div>
                                    </button>
                                </div>
                                <div 
                                    x-show="modalStatus"
                                    x-cloak
                                    x-transition
                                    @click.away="modalStatus = false"
                                    class="wrapper-detailActionNav absolute right-4 top-3/4">
                                    <div class="ctr-detailActionNav mt-8 size-96 bg-gray-50 rounded-lg shadow-md shadow-black/40 overflow-hidden">
                                        <div class="cDetailActionNav">
                                            <div class="headerDetailActionNav bg-slate-200 px-6 py-2 flex items-center justify-between">
                                                <div class="textHeaderDetailAction">
                                                    <div class="textHeader text-sm font-semibold">
                                                        <p>Background Process</p>
                                                    </div>
                                                </div>
                                                <div class="act-closeDetail">
                                                    <span
                                                        @click="modalStatus = false"
                                                        role="button"
                                                        tabindex="0"
                                                        class="icon size-8 flex items-center justify-center rounded-full border border-black cursor-pointer"
                                                    >
                                                        <i class="fas fa-x"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="listBackgroundProcess mt-2 space-y-1.5">
                                                
                                                <div class="itm-backgroundProcess bg-white rounded-lg shadow-md shadow-black/20 overflow-hidden">
                                                    <div class="contentProcess flex items-center gap-1 px-4 py-2 ">
                                                        <div class="iconProcess shrink-0 border border-black size-8 flex items-center justify-center">
                                                            <i class="fas fa-bars-progress"></i>
                                                        </div>
                                                        <div class="textProcess flex-grow text-sm">
                                                            <p>Process</p>
                                                        </div>
                                                        <div class="statProcess shrink-0 text-xs">
                                                            <p>1/2</p>
                                                        </div>
                                                    </div>
                                                    <div class="progress-bar h-1.5 bg-blue-600" style="width: {{ rand(5, 90) }}%"></div>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div 
                                class="itm-navHeaderDashboard" 
                                aria-label="help"
                                >
                                <div class="actionNav">
                                    {{-- <button class="actionItmNav flex items-center justify-center size-10 rounded-lg text-[#C8A500] border border-[#8C7000] bg-[#121212] hover:bg-[#181818] hover:text-[#FFD700] hover:border-[#C8A500] shadow-sm shadow-transparent hover:shadow-[#F1C40F]"> --}}
                                    <button class="actionItmNav flex items-center justify-center size-10 rounded-lg bg-[#E4E4E4] hover:bg-[#dbdbdb] border border-[#b2b6bb]">
                                        <div class="icnAction text-xl text-[#363636]">
                                            <i class="fas fa-question"></i>
                                        </div>
                                    </button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ctr-accountNavHeaderDashboard"
                x-data="{modalStatus: false}">
                <div class="cAccountNavHeaderDashboard">
                    <div class="iconAccountNavHeader">
                        <button 
                            @click="modalStatus = !modalStatus"
                            class="actionAccountNav flex items-center justify-center size-12 rounded-full bg-[#1565C0] hover:bg-[#1879E6] border border-gray-400">
                            <div class="icnAction text-white">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                        </button>
                    </div>
                    
                    <div 
                        class="wrapper-detailActionNav absolute right-4 top-3/4"
                        x-cloak
                        x-show="modalStatus"
                        x-transition
                        @click.away="modalStatus = false"
                        >
                        <div class="ctr-detailActionNav mt-8 w-96 bg-gray-50 rounded-lg shadow-md shadow-black/40">
                            <div class="cDetailActionNav">
                                <div class="ctr-headerDetailAction py-4">
                                    <div class="cHeaderDetailAction">
                                        <div class="ctr-detailProfileUser">
                                            <div class="cDetailProfileUser">
                                                <div class="idProfileUser">
                                                    <div class="txIdProfileUser text-sm text-center poppins-light">
                                                        <p>{{ Auth::user()->username }}</p>
                                                    </div>
                                                </div>
                                                <div class="wrapper-photoProfileUser mt-2 flex items-center justify-center">
                                                    <div class="photoProfileUser bg-[#efefef] rounded-full p-2">
                                                        <div class="imgProfileUser size-28">
                                                            <img src="{{ asset('components/icon/logo/main/logo.png') }}" alt="Photo Profile" class="object-cover object-center size-full">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nmeProfileUser mt-2">
                                                    <div class="txNmeProfileUser text-lg text-center">
                                                        <p>Hi, {{ explode(' ', Auth::user()->UserPersonal->fullname)[0] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="actionHeaderDetail mt-2">
                                            <div class="manageAccount flex justify-center items-center ">
                                                <a href="#" class="hrefManageAccount block  px-10 py-2 rounded-full border border-gray-400 hover:bg-blue-50">
                                                    <div class="txHrefManageAccount text-sm text-blue-800 poppins-semibold">
                                                        <p>Manage Account</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ctr-contentDetailActionNav py-4">
                                    <div class="cContentDetailActionNav">
                                        
                                        @livewire('layout.dashboard.partial.logout')
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>


@once
    @push('dashboard-body-script')
        <script data-navigate-once="true">
            
            Alpine.data('alp_backgroundProcess', () => {
                const _token = '{{ csrf_token() }}';
                
                return {
                    
                    
                    
                }
                
            });
            
        </script>
    @endpush
@endonce
