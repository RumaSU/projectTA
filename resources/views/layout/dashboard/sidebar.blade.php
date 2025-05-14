{{-- <aside class="ctr-sidebarApp bg-[#181818] w-96 h-screen fixed left-0 top-0"> --}}
{{-- <aside class="ctr-sidebarApp bg-[#003399] w-96 h-screen fixed left-0 top-0"> --}}
<aside class="ctr-sidebarApp w-[27rem] p-0 relative">
    <div class="wrapper-contentSidebarApp w-80 fixed left-0 top-1/2 -translate-y-1/2">
        <div class="cSidebarApp">
            <div class="ctr-additionalNav">
                <div class="cAdditionalNav space-y-0.5">
                    @include('layout.dashboard.sidebar.additional')
                </div>
            </div>
            <nav class="ctrNav-sidebarApp mt-4 px-4 py-12 bg-[#1565C0] h-[38rem] rounded-r-[2rem] shadow-md shadow-black/40">
                <div class="cNav-sidebarApp">
                    @include('layout.dashboard.sidebar.main')
                </div>
            </nav>
            
        </div>
    </div>
</aside>