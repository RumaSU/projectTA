@extends('layout.main')
@section('titlePage')
    Sign
    @stack('additional-title')
@endsection


@section('default-layout-head-field')
    @stack('sign-head-script')
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
    
    @stack('sign-head-css')
    
    @livewireStyles
@endsection

@push('default-aditional-prop-body')
    class="bg-[#f1f1f1] h-screen flex flex-col @stack('sign-attr-class-val-body')"
    @stack('sign-attr-body')
@endpush

@section('default-layout-body-content')
    
    <header class="py-2 px-4 bg-white z-50">
        <div class="c-header flex items-center justify-between">
            <div class="mainText">
                <div class="textHeader text-xl font-semibold">
                    <h2>Digital Signature</h2>
                </div>
            </div>
            
            <div class="back-dashboard shrink-0">
                <a href="{{ route('app.dashboard.home') }}" 
                    class="block bg-blue-600 px-4 py-4 rounded-lg">
                    <div class="cBackDashboard">
                        <div class="textBack text-sm text-white">
                            <p>Back to dashboard</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </header>
    
    
    {{ $slot }}
    
    
    
    
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
    
    @stack('sign-body-script')
@endsection
