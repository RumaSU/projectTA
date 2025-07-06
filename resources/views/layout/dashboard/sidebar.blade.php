{{-- <div class="wrapper-contentSidebarApp w-80 fixed left-0 top-1/2 -translate-y-1/2"> --}}
<div class="wrapper-contentSidebarApp w-fit xl:w-80 border border-black">
    <div class="cSidebarApp">
        <div class="ctr-additionalNav">
            <div class="cAdditionalNav space-y-0.5">
                @include('layout.dashboard.sidebar.additional')
            </div>
        </div>
        <nav class="ctrNav-sidebarApp mt-4 px-4 py-[1.6rem] bg-[#1565C0] h-[34rem] rounded-r-[2rem] shadow-md shadow-black/40">
            <div class="cNav-sidebarApp">
                @include('layout.dashboard.sidebar.main')
            </div>
        </nav>
        
    </div>
</div>