@extends('layout.main')
@section('titlePage')
    Auth
    @stack('additional-title')
@endsection


@section('default-layout-head-field')
    @stack('auth-head-script')
    @stack('auth-head-css')
    
    @livewireStyles
@endsection

@section('default-layout-body-content')
    
    {{ $slot }}
    
    @livewireScripts
    @livewireScriptConfig
    @stack('auth-body-script')
@endsection