@extends('layout.main')
@section('titlePage')
    Dashboard
    @stack('additional-title')
@endsection


@section('default-layout-head-field')
    @stack('dashboard-head-script')
    @stack('dashboard-head-css')
    
    @livewireStyles
@endsection

@push('default-aditional-prop-body')
    class="bg-[#f1f1f1]"
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
    
    @livewireScripts
    @livewireScriptConfig
    @stack('dashboard-body-script')
@endsection