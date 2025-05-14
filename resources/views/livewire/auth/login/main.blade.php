@push('additional-title')
    | Login
@endpush

<div>
    <p>Login</p>
    <a href="{{ route('auth.register') }}" wire:navigate>Go to register</a>
</div>
