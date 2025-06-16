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
        function dispatchingDataTo($dispatchKey, $dispatchData) {
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
    class="bg-[#f1f1f1] @stack('auth-attr-class-val-body')"
    @stack('auth-attr-body')
@endpush

@section('default-layout-body-content')
    
    <div class="app">
        <div class="cApp ">
            
            <div class="headerApp px-4 py-4">
                <div class="txHeader text-xl">
                    <p class="font-semibold">Digital Signature</p>
                </div>
            </div>
            
            <div class="wrapper-mainApp border border-black flex justify-center">
                <main class="mainApp border border-black">
                    <div class="cmainApp">
                        @stack('auth-top-main-content')
                        
                        @yield('auth-child-template')
                        
                        @stack('auth-bottom-main-content')
                        
                        @stack('auth-custom-main-content')
                    </div>
                </main>
                <footer></footer>
            </div>
            
            
        </div>
    </div>
    
    @stack('additional-auth-body-content')
    
    @livewireScripts
    @livewireScriptConfig
    <script data-navigate-once>
        document.addEventListener('livewire:navigated', (e) => {
            
            console.log(e.target.location);
            
        });
    </script>
    @stack('auth-body-script')
@endsection