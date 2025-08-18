<div class="item-audit grid grid-cols-5 p-4 even:bg-gray-200">
    
    @php
        $user = \App\Utils\ModelUtils::createInstanceModel(\App\Models\Users\User::class)
            ->find($audit->id_user);
        
        $outputUser = 'N/A';
        if ($user) {
            $outputUser = "
                &lt;{$user->email}&gt;
            ";
        }
        
        $timezone = session()->get('timezone');
        $datetime = \Carbon\Carbon::create($audit->logged_at)->timezone($timezone)->format('F j, Y');
        $time = \Carbon\Carbon::create($audit->logged_at)->timezone($timezone)->format('H:i:s');
        
        $metadata = $audit->metadata;
        $icon = \App\Enums\Audit\Documents\Event::from_value($audit->event_type)->icon();
        
        $outputMainDescription = strtolower($audit->event_type) . " " . strtolower($audit->category);
        $outputMainDescription = str_replace('_', ' ', $outputMainDescription);
    @endphp
    
    <div class="audit-event flex items-center gap-2 text-gray-800">
        <div class="icon-event flex items-center justify-center text-lg size-6">
            <div class="icon">
                <i class="{{ $icon }}"></i>
            </div>
        </div>
        <p>{{ ucfirst($audit->description) }}</p>
    </div>
    
    <div class="audit-description col-span-3 flex flex-col justify-center space-y-1">
        <div class="main-description">
            <p>Sender {!! $outputUser !!} {{ $outputMainDescription }}  </p>
        </div>
        
        @if (! empty($metadata['additional_message']))
            <div class="metadata-additional-message text-sm">
                <p>{{ $metadata['additional_message'] }}</p>
            </div>
        @endif
        
        @if (! empty($metadata['location']))
            <div class="metadata-location text-xs">
                <p>{{ $metadata['location'] }}</p>
            </div>
        @endif
        
        <div class="metadata-ip-agent text-xs flex gap-2 flex-col lg:flex-row lg:items-center">
            <p>
                <span>
                    {{ !empty($metadata['ip']) ? $metadata['ip'] . ',' : '' }}
                </span>
                <span>
                    {{ !empty($metadata['agent']) ? $metadata['agent'] : '' }}
                </span>
            </p>
        </div>
        
    </div>
    
    <div class="audit-time text-center text-sm flex flex-col justify-center">
        <div class="date-time">
            <p>{{ $datetime }}</p>
        </div>
        <div class="time text-xs">
            <p>{{ $time }}</p>
        </div>
    </div>
    
    
</div>