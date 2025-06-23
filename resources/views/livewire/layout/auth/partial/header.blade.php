{{-- @php
    $statusHeader = Str::contains(request()->route()->getName(), 'login');
    
    $txMainHeader = $statusHeader == 'login' ? 'Welcome back' : 'Create an account';
    $txAdditionalHeader = $statusHeader == 'login' ? '' : '...';
    
    dump(
        request()->route()->getName(),
    );
@endphp --}}


<div class="cHeaderDetailApp">
    <div class="mainTxHeaderDA">
        <div class="txHeader text-2xl font-semibold text-center ">
            <p>@stack('text-main-header-detail-app', $text_main_header)</p>
        </div>
    </div>
    
    @if ($text_additional_header)
        <div class="additionalTxHeaderDA mt-2">
            <div class="tx text-sm text-center">
                <p>@stack('text-additional-header-detail-app', $text_additional_header)</p>
            </div>
        </div>
    @endif
</div>