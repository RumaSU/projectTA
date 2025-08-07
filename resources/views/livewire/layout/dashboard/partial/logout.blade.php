<div class="logoutActionProfileHeader">
    <button 
        wire:click="logout" 
        wire:loading.attr='disabled'
        type="submit" 
        class="ctr-actionLogout w-full bg-transparent px-6 py-2 hover:bg-gray-200 cursor-pointer text-gray-800 group">
        <div class="cActionLogout flex items-center gap-4">
            <div class="icnLogout size-8 flex items-center justify-center" role="img" aria-label="Icon Logout">
                <div class="text-xl text-center group-hover:text-gray-950">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                </div>
            </div>
            <div class="txLblAction text-sm group-hover:text-gray-950">
                <p>Logout</p>
            </div>
        </div>
    </button>
</div>

@once
    @push('dashboard-body-script')
        <script data-navigate-once="true">
            
            
            
        </script>
    @endpush
@endonce