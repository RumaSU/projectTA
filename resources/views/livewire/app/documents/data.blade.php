<div>
    <div class="" x-data="dispatchFilterDocument">Div Call Dispatch Filter Document</div>
    Data document
    {{-- @dump(Cookie::get())
    @dump(session()->all()) --}}
</div>


@once
    @push('dashboard-body-script')
        <script data-navigate-once>
            Alpine.data('dispatchFilterDocument', () => {
               return {
                    init() {
                        this.$dispatch('Alpine-Init-Filter-Document', {message: 'Call event filter document'});
                    },
               } 
            });
            // Alpine-Init-Filter-Document
            // $dispatch('Alpine-Init-Filter-Document', {message: 'Call event filter document'})
            // dispatchingDataLivewireTo('Alpine-Init-Filter-Document', {message: 'Call event filter document'});
        </script>
    @endpush
@endonce