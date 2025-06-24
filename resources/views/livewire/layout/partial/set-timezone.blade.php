<div x-data="setTimezone"></div>


@once
    <script data-navigate-once>
        
        window.addEventListener('alpine:init', () => {
            Alpine.data('setTimezone', () => {
                
                return {
                    init() {
                        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                        dispatchingDataTo('Set-New-Timezone', {timezone: timezone});
                        setNewCookie('timezone', timezone);
                    },
                }
            });
        })
    </script>
@endonce
