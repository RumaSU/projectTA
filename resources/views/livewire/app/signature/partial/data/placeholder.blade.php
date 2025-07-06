<div class="placeholder mt-6">
    {{-- Main --}}
    <div class="bg-white shadow-md shadow-black/40 rounded-lg mt-2 p-4">
        <div class="[&>.grid]:grid-cols-8 [&>.grid]:gap-2 space-y-1 animate-pulse">
            <div class="grid">
                <div class="col-span-5 w-2/5 py-2.5 bg-gray-300 rounded-lg"></div>
                <div class="col-span-3 w-1/3 py-2.5 bg-gray-300 rounded-lg"></div>
            </div>
            <div class="grid">
                <div class="col-span-5 py-4 bg-gray-300 rounded-lg"></div>
                <div class="col-span-3 py-4 bg-gray-300 rounded-lg"></div>
            </div>
            <div class="grid">
                <div class="col-span-5 h-44 bg-gray-300 rounded-lg"></div>
                <div class="col-span-3 h-44 bg-gray-300 rounded-lg"></div>
            </div>
        </div>
        
        <div class="flex gap-2 justify-end animate-pulse mt-2">
            <div class="w-1/4 py-4 bg-gray-300 rounded-lg"></div>
        </div>
        
    </div>
    
    {{-- List --}}
    <div class="mt-4">
        @for ($i = 0; $i < 5; $i++)
            <div class="bg-white shadow-md shadow-black/40 rounded-lg mt-2 p-4">
                <div class="[&>.grid]:grid-cols-8 [&>.grid]:gap-2 space-y-1 animate-pulse">
                    <div class="grid">
                        <div class="col-span-5 w-2/5 py-2.5 bg-gray-300 rounded-lg"></div>
                        <div class="col-span-3 w-1/3 py-2.5 bg-gray-300 rounded-lg"></div>
                    </div>
                    <div class="grid">
                        <div class="col-span-5 h-44 bg-gray-300 rounded-lg"></div>
                        <div class="col-span-3 h-44 bg-gray-300 rounded-lg"></div>
                    </div>
                </div>
                
                <div class="flex gap-2 justify-end animate-pulse mt-2">
                    <div class="w-1/4 py-4 bg-gray-300 rounded-lg"></div>
                    <div class="w-1/4 py-4 bg-gray-300 rounded-lg"></div>
                </div>
                
            </div>
        @endfor
    </div>
    
</div>
