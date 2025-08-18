<div class="item-info flex items-center gap-2 {{ empty($add_border) ? '' : 'border-b border-gray-400' }}">
    <div class="label-item w-40 text-right font-medium">
        <p>{{ $label }}</p>
    </div>
    <div class="value-item inline-flex items-center gap-2">
        <p>{!! $value !!}</p>
    </div>
</div>