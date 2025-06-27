@extends('layout.main')
@section('titlePage')
    Auth
    @stack('additional-title')
@endsection


@section('default-layout-head-field')
    @stack('auth-head-script')
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
    @stack('auth-head-css')
    
    @livewireStyles
@endsection

@push('default-aditional-prop-body')
    {{-- @stack('auth-attr-class-val-body')" --}}
    @stack('auth-attr-body')
@endpush

@section('default-layout-body-content')
    
    <div class="app">
        <div class="cApp ">
            
            <div class="headerApp px-6 py-8">
                <div class="txHeader text-xl">
                    <p class="font-semibold">Digital Signature</p>
                </div>
            </div>
            
            <div class="wrapper-detailApp mt-16">
                <div class="detailApp w-96 mx-auto">
                    
                    <header class="ctr-headerDetailApp">
                        
                        @livewire('layout.auth.partial.header')
                        
                    </header>
                    
                    <main class="mainApp mt-14">
                        <div class="cMainApp">
                            @stack('auth-top-main-content')
                            
                            @yield('auth-child-template')
                            
                            {{ $slot }}
                            
                            @stack('auth-bottom-main-content')
                            
                            @stack('auth-custom-main-content')
                        </div>
                    </main>
                    
                    
                    <footer>
                        
                    </footer>
                </div>
            </div>
            
            
            
        </div>
    </div>
    
    @persist('custom_notification')
        @include('livewire.layout.partial.toast-notification')
    @endpersist()
    
    @if (! session()->exists('timezone'))
        @livewire('layout.partial.set-timezone')
    @endif
    
    
    @stack('additional-auth-body-content')
    
    @livewireScripts
    @livewireScriptConfig
    <script data-navigate-once>
        document.addEventListener('livewire:navigated', (e) => {
            
            // console.log(e.target.location);
            console.log(' ');
            // console.log(window);
            console.log(window.history);
            // console.log(window.VanillaCalendarPro);
            // console.log(window.VanillaCalendarPro.Calendar);
            // console.log(window.VanillaCalendarPro.Calendar.memoizedElements);
            // console.log(Calendar);
            console.log(' ');
            
            const CalendarClass = window?.VanillaCalendarPro?.Calendar;
            if (CalendarClass?.memoizedElements?.clear) {
                CalendarClass.memoizedElements.clear(); // aman, cepat
                console.log('[Calendar] Memoized elements cleared after Livewire navigated');
            }
            
        });
    </script>
    
    {{-- @livewire('layout.partial.refresh-c-s-r-f') --}}
    
    @stack('auth-body-script')
@endsection