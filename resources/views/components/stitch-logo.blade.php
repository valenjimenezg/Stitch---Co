<div class="inline-flex items-center gap-3 sm:gap-4 rounded-full border-2 border-primary bg-white px-5 sm:px-7 py-2 sm:py-3 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 shrink-0 {{ $attributes->get('class') ?? '' }}">
    
    <!-- Text Section -->
    <div class="flex flex-col items-start justify-center">
        <span class="text-primary font-black leading-none tracking-tight {{ $textSize ?? 'text-2xl sm:text-[32px]' }}" style="font-family: 'Nunito', 'Segoe UI', sans-serif;">
            Stitch <span class="text-primary/80">&amp;</span> Co
        </span>
        <span class="text-primary/70 font-bold tracking-[0.15em] mt-1 {{ $subTextSize ?? 'text-[10px] sm:text-[11px]' }} uppercase">
            Mercería Online
        </span>
    </div>

    <!-- Needle Section -->
    <div class="relative {{ $size ?? 'w-8 h-12 sm:w-10 sm:h-14' }} shrink-0">
        <svg viewBox="0 0 100 120" class="w-full h-full overflow-visible drop-shadow-sm">
            <!-- Thread (Yellow S-Shape) -->
            <path d="M 5,90 C -15,70 40,40 50,65 C 60,90 105,70 85,30" fill="none" stroke="#eab308" stroke-width="8" stroke-linecap="round" />
            
            <!-- Needle (Yellow Diagonal) -->
            <path d="M 20,110 L 85,15 L 90,20 L 25,115 Z" fill="#eab308" />
            
            <!-- Needle Eye -->
            <ellipse cx="85" cy="18" rx="2.5" ry="8" fill="white" transform="rotate(55 85 18)" />
            <ellipse cx="85" cy="18" rx="2.5" ry="8" fill="none" stroke="#eab308" stroke-width="2.5" transform="rotate(55 85 18)" />
        </svg>
    </div>

</div>
