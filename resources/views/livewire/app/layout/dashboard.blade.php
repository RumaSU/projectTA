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
    class="bg-[#efefef]"
@endpush

@section('default-layout-body-content')
    @include('layout.dashboard.header')
    
    <div class="app">
        <div class="cApp flex gap-2">
            @include('layout.dashboard.sidebar')
            
            <main class="mainApp w-full h-full ml-80 px-4 py-2">
                <div class="cMainApp min-h-[86vh] bg-white px-4 py-2 rounded-l-xl">
                    @if (!Str::contains(request()->route()->getName(), ['documents']))
                        {{ $slot }}
                    @endif
                    
                    @yield('dashboard-additional-content')
                </div>
            </main>
        </div>
    </div>
    
    @livewireScripts
    @livewireScriptConfig
    @stack('dashboard-body-script')
@endsection