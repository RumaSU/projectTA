<div class="mt-4 space-y-2">
    @for ($i = 0; $i < 5; $i++)
        <div class="bg-white px-4 py-2 rounded-lg shadow-sm shadow-black/40 relative grid grid-cols-3">
            
            <div class="space-y-1">
                <div class="w-1/2 h-6 bg-gray-300 rounded-lg animate-pulse"></div>
                <div class="w-1/4 h-6 bg-gray-300 rounded-lg animate-pulse"></div>
                <div class="flex items-center gap-2">
                    <div class="w-16 h-6 bg-gray-300 rounded-md animate-pulse"></div>
                    <div class="w-16 h-6 bg-gray-300 rounded-md animate-pulse"></div>
                    <div class="w-16 h-6 bg-gray-300 rounded-md animate-pulse"></div>
                </div>
            </div>
            
            <div class="flex items-center justify-center">
                <div class="w-1/4 h-6 bg-gray-300 rounded-lg animate-pulse"></div>
            </div>
            
            <div class="flex items-center justify-center gap-2">
                <div class="w-16 h-6 bg-gray-300 rounded-md animate-pulse"></div>
                <div class="w-16 h-6 bg-gray-300 rounded-md animate-pulse"></div>
                <div class="w-16 h-6 bg-gray-300 rounded-md animate-pulse"></div>
            </div>
            
        </div>
    @endfor
</div>