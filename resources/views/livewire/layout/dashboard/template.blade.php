@extends('layout.main')
@section('titlePage')
    Dashboard
    @stack('additional-title')
@endsection


@section('default-layout-head-field')
    @stack('dashboard-head-script')
    <script src="{{ asset('main/js/urlUtils.js') }}"></script>
    <script src="{{ asset('main/js/cookieUtils.js') }}"></script>
    <script src="{{ asset('main/js/dateUtils.js') }}"></script>
    <script src="{{ asset('main/js/helper.js') }}"></script>
    <script>
        function dispatchingDataLivewireTo($dispatchKey, $dispatchData) {
            if (typeof $dispatchData !== 'object') {
                alert('Please set the dispatch data to object type');
                return {
                    page: window.location.href,
                    status: 'failed',
                    key: $dispatchKey,
                    data: $dispatchData,
                };
            }
            
            Livewire.dispatch($dispatchKey, [$dispatchData]);
            return {
                page: window.location.href,
                status: 'success',
                key: $dispatchKey,
                data: $dispatchData,
            };
        }
    </script>
    
    @vite('resources/js/events/processDocuments.js')
    
    @stack('dashboard-head-css')
    
    @livewireStyles
@endsection

@push('default-aditional-prop-body')
    class="bg-[#f1f1f1] @stack('dashboard-attr-class-val-body')"
    @stack('dashboard-attr-body')
@endpush

@section('default-layout-body-content')
    
    {{-- <header class="bg-[#121212] py-2 px-4 z-[1000] sticky top-0"> --}}
    {{-- <header class="bg-[#002366] py-2 px-4 z-[1000] sticky top-0"> --}}
    <header class="sticky top-0 z-10 bg-[#E4E4E4] p-2 px-40" wire:ignore.self>
        @include('layout.dashboard.header')
    </header>
    
    
    <div class="app  ">
        <div class="cApp flex ">
            {{-- <aside class="ctr-sidebarApp bg-[#181818] w-96 h-screen fixed left-0 top-0"> --}}
            {{-- <aside class="ctr-sidebarApp bg-[#003399] w-96 h-screen fixed left-0 top-0"> --}}
            <aside class="ctr-sidebarApp shrink-0 p-0 sticky top-[15%] self-start hidden md:block xl:w-96 border border-black">
                @include('layout.dashboard.sidebar')
                {{-- <div class="wrapper sticky top-1/2 ">
                </div> --}}
            </aside>
            
            <div class="wrapper-mainApp w-full pt-4 h-full  min-h-[calc(100vh-5rem)] lg:pl-4">
                <main class="mainApp min-h-[calc(100vh-7rem)] bg-white px-2 lg:pl-9 lg:pr-3 py-4 lg:rounded-l-[2rem] ">
                    <div class="cMainApp  h-full">                        
                        @stack('dashboard-top-main-content')
                        
                        @yield('dashboard-child-template')
                        
                        @stack('dashboard-bottom-main-content')
                        
                        @stack('dashboard-custom-main-content')
                    </div>
                </main>
            </div>
            
        </div>
    </div>
    
    @stack('global-custom-content')
    
    @persist('custom_notification')
        @include('livewire.layout.partial.toast-notification')
    @endpersist()
    
    {{-- @if (! session()->exists('timezone') || ! Cookie::get('timezone')) --}}
    @if (! session()->exists('timezone'))
        @livewire('layout.partial.set-timezone')
    @endif
    
    @livewireScripts
    @livewireScriptConfig
    <script data-navigate-once>
        // function callWhatWireLocation() {
        //     document.addEventListener('livewire:initialized', (e) => {
        //         console.log(e.target.location);
        //     });
        // }
        document.addEventListener('livewire:navigated', (e) => {
            
            // console.log(e.target.location);
            console.log(window.history)
            const CalendarClass = window?.VanillaCalendarPro?.Calendar;
            if (CalendarClass?.memoizedElements?.clear) {
                CalendarClass.memoizedElements.clear(); // aman, cepat
                console.log('[Calendar] Memoized elements cleared after Livewire navigated');
            }
        });
        
    </script>
    
    @vite('resources/js/events/documents/main.js')
    
    <script data-navigate-once="true" type="module">
        window.Echo.private(`process_docs.${window.Laravel.sessionId}`)
            .listen('ProcessNewDocument', (e) => {
                console.log('private process new documents...');
            })
            .listen('.ProcessNewDocument', (e) => {
                console.log('private alias process new documents...');
            });
        
            
        // window.Echo.private(`now-status_upload.${window.Laravel.sessionId}`)
        //     .listen('Documents.Now.StatusUpload', ($data) => {
        //         console.log("now process status upload");
        //         console.log("data: ", $data);
        //     })
        //     .listen('Documents.Now.StatusUpload', ($data) => {
        //         console.log("now namespace process status upload");
        //         console.log("data: ", $data);
        //     })
        //     .listen('.Now_ProcessStatusUpload', ($data) => {
        //         console.log("now alias process status upload");
        //         console.log("data: ", $data);
        //     });
            
            
        // window.Echo.channel(`process_docs.${window.Laravel.sessionId}`)
        //     .listen("ProcessNewDocument", () => {
        //         console.log('channel process new documents...');
        //     });
    </script>
    
    @stack('dashboard-body-script')
@endsection
