@push('additional-title')
    | Register
@endpush

@section('auth-child-template')
    <header class="ctr-headerLogin">
        <div class="cHeaderLogin">
            <div class="mainTxHeaderLogin">
                <div class="txHeader">
                    <strong>Create an Account</strong>
                </div>
            </div>
            <div class="additionalTxHeaderLogin">
                <div class="tx">
                    <p>asdasdasd</p>
                </div>
            </div>
        </div>
    </header>
    
    <div class="ctr-mainFormLogin">
        <div class="cMainFormLogin">
            <form>
                
                @yield('child-main-form-login-template')
                
                {{-- {{ $slot }} --}}
                
            </form>
        </div>
    </div>
    
    
    <div>
        <p>Login</p>
        <a href="{{ route('auth.register') }}" wire:navigate>Go to register</a>
    </div>
@endsection

<div></div>