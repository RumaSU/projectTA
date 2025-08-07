<div x-data="setTimezone"></div>


@once
    <script data-navigate-once>
        
        window.addEventListener('alpine:init', () => {
            Alpine.data('setTimezone', () => {
                
                
                return {
                    init() {
                        
                        
                        
                        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                        dispatchingDataLivewireTo('Set-New-Timezone', {timezone: timezone});
                        // setNewCookie('timezone_browser', timezone);
                    },
                }
            });
        })
    </script>
@endonce
