<div>
    
</div>

@section('dashboard-child-template')
    @if ($message)
        <div class="p-4 bg-green-100 text-green-800 rounded">
            Pesan masuk: {{ $message }}
        </div>
    @else
        <div class="text-gray-500">Belum ada pesan</div>
    @endif
    
    <div class=""
        x-data="{ text: 'test' }"
        @echo:Testing,TestingEventPusher.window="console.log('test')"
    >
        
    </div>
@endsection

@push('dashboard-body-script')
    <script>
        window.addEventListener('alpine:init', () => {
            const echoTest =  Echo.channel('Testing');
            console.log(echoTest);
            console.log(window.Echo);
            window.Echo.channel('Testing').listen('TestingEventPusher', (data) => {
                // alert(data);
                console.log('KONTOL');
                console.log(data);
                console.log(window.Echo); 
            });
        })
        
    </script>
@endpush