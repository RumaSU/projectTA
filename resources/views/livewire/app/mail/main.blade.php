@once
    @push('dashboard-top-main-content')
        <nav class="navMailDashboard">
            @include('livewire.app.mail.partial.nav')
        </nav>
    @endpush
@endonce

@section('dashboard-child-template')
    <div class="wrapper-mailAppDashboard mt-12 p-4 bg-gray-200">
        <div class="ctr-headerMailAppDashboard h-12 bg-white">
            <div class="cHeaderMailAppDashboard">
                {{-- @include(...) --}}
            </div>
        </div>
        <div class="ctr-mainContentMailAppDashbaord mt-8 bg-white px-8 py-4">
            @livewire('app.mail.data')
        </div>
    </div>
@endsection


@once
    @push('dashboard-body-script')
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