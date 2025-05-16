@extends('livewire.layout.dashboard.template')

@once
    @push('dashboard-top-main-content')
        <nav class="navInboxDashboard">
            @include('livewire.layout.dashboard.inbox.nav')
        </nav>
    @endpush
@endonce

@section('dashboard-child-template')
    <div class="wrapper-inboxAppDashboard">
        {{ $slot }}
    </div>
    <div class="test flex gap-2">
        @for ($i = 0; $i < 5; $i++)
            {{-- <button class="block border border-black px-4 py-2" x-on:click="alert('test-{{ $i }}')"> --}}
            <button class="block border border-black px-4 py-2" x-init @click="alert('test-{{ $i }}')">
                <div class="tx">
                    <p>Param-{{ $i }}</p>
                </div>
            </button>
        @endfor
    </div>
@endsection