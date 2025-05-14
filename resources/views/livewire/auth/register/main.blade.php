@push('additional-title')
    | Register
@endpush

<div>
    <p>
        Register 
    </p>
    <a href="{{ route('auth.login') }}" wire:navigate>Go to login</a>
</div>
