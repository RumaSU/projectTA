@extends('layout.main')
@section('titlePage')
    Dashboard
    @stack('additional-title')
@endsection


@section('default-layout-head-field')
    @stack('dashboard-head-script')
    <script src="{{ asset('main/js/urlUtils.js') }}"></script>
    <script src="{{ asset('main/js/cookieUtils.js') }}"></script>
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
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
    @stack('dashboard-head-css')
    
    @livewireStyles
@endsection

@push('default-aditional-prop-body')
    class="bg-[#f1f1f1] @stack('dashboard-attr-class-val-body')"
    @stack('dashboard-attr-body')
@endpush

@section('default-layout-body-content')
    @include('layout.dashboard.header')
    
    <div class="app">
        <div class="cApp flex">
            @include('layout.dashboard.sidebar')
            
            <div class="wrapper-mainApp w-full pl-4 pt-4 min-h-[calc(100vh-5.5rem)]">
                <main class="mainApp h-full bg-white pl-9 pr-3 py-4 rounded-l-[2rem]">
                    <div class="cMainApp">                        
                        @stack('dashboard-top-main-content')
                        
                        {{-- Optional content: custom via @yield --}}
                        @yield('dashboard-child-template')
                        
                        @stack('dashboard-bottom-main-content')
                        
                        {{-- Custom content --}}
                        @stack('dashboard-custom-main-content')
                    </div>
                </main>
            </div>
        </div>
    </div>
    
    @persist('custom_notification')
        @include('livewire.layout.partial.toast-notification')
    @endpersist()
    
    @if (! session()->exists('timezone') || ! Cookie::get('timezone'))
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
    @stack('dashboard-body-script')
@endsection

{{-- <div class="">sa</div> --}}