@props(['icon', 'label', 'value', 'trend' => null, 'trendUp' => true, 'iconColor' => 'primary'])

<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <div class="p-2 bg-{{ $iconColor }}/10 rounded-lg text-{{ $iconColor }}">
            <span class="material-symbols-outlined">{{ $icon }}</span>
        </div>
        @if($trend)
            <span class="{{ $trendUp ? 'text-emerald-500' : 'text-red-500' }} text-xs font-bold flex items-center gap-0.5">
                <span class="material-symbols-outlined text-sm">{{ $trendUp ? 'trending_up' : 'trending_down' }}</span>
                {{ $trend }}
            </span>
        @endif
    </div>
    <p class="text-slate-500 text-sm font-medium">{{ $label }}</p>
    <h3 class="text-2xl font-bold mt-1">{{ $value }}</h3>
</div>
