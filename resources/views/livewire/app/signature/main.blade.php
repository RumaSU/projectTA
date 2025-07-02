@push('default-layout-head-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@once
    @push('dashboard-head-script')
        <script src="{{ asset("vendor/signaturePad/dist/signature_pad.umd.min.js") }}"></script>
    @endpush
    
    @push('dashboard-head-css')
        <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Allura&family=Dancing+Script:wght@400..700&family=Great+Vibes&family=Homemade+Apple&family=Mr+Dafoe&family=Pacifico&family=Satisfy&family=Signika:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('main/css/font/pacifico.css') }}">
        <link rel="stylesheet" href="{{ asset('main/css/font/dancing-script.css') }}">
    @endpush
@endonce

@once
    @push('dashboard-top-main-content')
        <nav class="navMailDashboard">
            @include('livewire.app.signature.partial.header')
        </nav>
    @endpush
@endonce

@section('dashboard-child-template')
    <div class="wrapper-mailAppDashboard mt-6 p-4 bg-gray-200 rounded-xl">
        <div class="ctr-headerMailAppDashboard px-6 py-4 bg-white rounded-3xl">
            <div class="cHeaderMailAppDashboard">
                @include('livewire.app.signature.partial.nav')
            </div>
        </div>
        <div class="ctr-mainContentMailAppDashbaord mt-8">
            {{-- @livewire('app.documents.data') --}}
        </div>
    </div>
@endsection


@once
    @push('dashboard-body-script')
        <script data-navigate-once>
            console.log('signature');
            
        </script>
        {{-- <script data-navigate-once>
            window.addEventListener('mail_spa_nav', event => {
                let dataPage = [event.detail];
                console.log('Response from nav:', dataPage);
                Livewire.dispatch('MailPageSpa', dataPage);
                // Livewire.dispatch('testDispatchFromInbox', dataPage);
            });
            Livewire.hook('component.init', ({ component }) => {
                console.log("Livewire component initialized", component);
            });
            Livewire.hook('message.received', (message, component) => {
                console.log('Message received:', message, 'by', component);
            });
        </script> --}}
    @endpush
@endonce

<div></div>