<div 
    x-data="notificationElemnt"
    @customnotify.window="addNotification($event)"
>
    <div 
        x-on:mouseenter="$dispatch('pause-auto-dismiss')" 
        x-on:mouseleave="$dispatch('resume-auto-dismiss')" 
        class="{{ Str::contains(request()->route()->getName(), 'app') ? 'mt-20' : '' }} group pointer-events-none z-40 border border-black fixed inset-x-8 top-0 flex max-w-full flex-col gap-2 bg-transparent px-6 py-6 md:left-[unset] md:right-0 md:max-w-md"
    
        >
        <template x-for="(notification, index) in notifications" x-bind:key="notification.id">
            
            <div>
                <div 
                    class="pointer-events-auto relative rounded-md border"
                    :class="notification.notificationStyle.styleColor.parentElement"
                    role="alert" 
                    x-data="{ isVisible: false, timeout: null }" 
                    x-cloak 
                    x-show="isVisible" 
                    x-on:pause-auto-dismiss.window="clearTimeout(timeout)" 
                    x-on:resume-auto-dismiss.window=" timeout = setTimeout(() => {(isVisible = false), removeNotification(notification.id) }, notification.notifDuration)" 
                    x-init="$nextTick(() => { isVisible = true }), (timeout = setTimeout(() => { isVisible = false, removeNotification(notification.id)}, notification.notifDuration))" 
                    x-transition:enter="transition duration-300 ease-out" 
                    x-transition:enter-end="translate-y-0" 
                    x-transition:enter-start="translate-y-8" 
                    x-transition:leave="transition duration-300 ease-in" 
                    x-transition:leave-end="-translate-x-24 opacity-0 md:translate-x-24" 
                    x-transition:leave-start="translate-x-0 opacity-100"
                >
                    <div 
                        class="flex w-full items-center gap-2.5 rounded-radius p-4 transition-all duration-300"
                        :class="notification.notificationStyle.styleColor.contentElement"
                    >
                    
                    {{-- @dump(Str::contains(request()->route()->getName(), 'dashboard'), request()->route()->getName())
                    val: {{ !Str::contains(request()->route()->getName(), 'dashboard') }} 
                    {{ !Str::contains(request()->route()->getName(), 'dashboard') ? 'contains' : 'not contains' }} --}}
                        <!-- Icon -->
                        <div 
                            class="rounded-full p-0.5 text-info"
                            :class="notification.notificationStyle.styleColor.iconElement" 
                            aria-hidden="true"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true">
                                <path 
                                    fill-rule="evenodd" 
                                    clip-rule="evenodd" 
                                    :d="notification.notificationStyle.dPathIcon"
                                />
                            </svg>
                        </div>

                        <!-- Title & Message --> 
                        <div class="flex flex-col gap-2">
                            <h3 
                                x-cloak 
                                x-show="notification.title" 
                                class="text-sm font-semibold"
                                :class="notification.notificationStyle.styleColor.titleMessage.title" 
                                x-text="notification.title">
                            </h3>
                            <p x-cloak x-show="notification.message" class="text-pretty text-sm" x-text="notification.message"></p>
                        </div>

                        <!--Dismiss Button -->
                        <button type="button" class="ml-auto" aria-label="dismiss notification" x-on:click="(isVisible = false), removeNotification(notification.id)">
                            <svg xmlns="http://www.w3.org/2000/svg viewBox="0 0 24 24 stroke="currentColor" fill="none" stroke-width="2" class="size-5 shrink-0" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </template>
    </div>
</div>

@once
    <script data-navigate-once>
        window.addEventListener('alpine:init', () => {
            Alpine.data('notificationElemnt', () => {
                // info, success, warning, danger
                const variantsNotifElement = [
                    { 
                        variant: 'info',
                        dPathIcon: 'M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z',
                        styleColor: {
                            'parentElement': 'border-sky-500 bg-white text-neutral-600',
                            'contentElement': 'bg-sky-500/10',
                            'iconElement': 'text-sky-500',
                            'titleMessage': {
                                'title': 'text-sky-500',
                                'message': ''
                            },
                        },
                    },
                    { 
                        variant: 'success',
                        dPathIcon: 'M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z',
                        styleColor: {
                            'parentElement': 'border-green-500 bg-white text-neutral-600',
                            'contentElement': 'bg-green-500/10',
                            'iconElement': 'text-green-500',
                            'titleMessage': {
                                'title': 'text-green-500',
                                'message': ''
                            },
                        },
                    },
                    { 
                        variant: 'warning',
                        dPathIcon: 'M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z',
                        styleColor: {
                            'parentElement': 'border-amber-500 bg-white text-neutral-600',
                            'contentElement': 'bg-amber-500/10',
                            'iconElement': 'text-amber-500',
                            'titleMessage': {
                                'title': 'text-amber-500',
                                'message': ''
                            },
                        },
                    },
                    { 
                        variant: 'danger',
                        dPathIcon: 'M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z',
                        styleColor: {
                            'parentElement': 'border border-red-500 bg-white text-neutral-600',
                            'contentElement': 'bg-red-500/10',
                            'iconElement': 'text-red-500',
                            'titleMessage': {
                                'title': 'text-red-500',
                                'message': ''
                            },
                        },
                    },
                ];
                
                return {
                    notifications: [],
                    // displayDuration: 5000,
                    defaultDuration: 5000,
                    notificationMax: 20,
                    
                    // addNotification({ variant = 'info', sender = null, title = null, message = null}, duration = this.defaultDuration) {
                    addNotification($val) {
                        let valueEventParam = JSON.parse(JSON.stringify($val.detail));
                        if ( Array.isArray(valueEventParam) ) {
                            valueEventParam = valueEventParam[0];
                        }
                        console.log('original value: ', JSON.parse(JSON.stringify($val)));
                        console.log('detail value: ', JSON.parse(JSON.stringify(valueEventParam)));
                        
                        console.log('Add notification ' + valueEventParam.variant);
                        
                        const id = Date.now();
                        const notificationStyle = variantsNotifElement.find(x => x.variant == valueEventParam.variant);
                        const notificationDuration = valueEventParam.duration || this.defaultDuration;
                        
                        if (!notificationStyle) {
                            this.$dispatch('customnotify', { variant: 'danger', title: 'Oops!',  message: 'Something went wrong. Variant ' + valueEventParam.variant + ' is not found' });
                            return
                        }
                        const notification = { 
                            id: id, 
                            variant: valueEventParam.variant, 
                            sender: valueEventParam.sender, 
                            title: valueEventParam.title, 
                            message: valueEventParam.message, 
                            notificationStyle: notificationStyle,
                            notifDuration: notificationDuration,
                        };
                        
                        console.log(notification);
                        
                        // Keep only the most recent 20 notifications
                        if (this.notifications.length >= this.notificationMax) {
                            this.notifications.splice(0, this.notifications.length - (this.notificationMax - 1));
                        }
                        
                        // Add the new notification to the notifications stack
                        this.notifications.push(notification);
                    },
                    removeNotification(id) {
                        setTimeout(() => {
                            this.notifications = this.notifications.filter(
                                (notification) => notification.id !== id,
                            )
                        }, 400);
                    },
                }
                
            }); 
        });
    </script>
    
@endonce