@props(['icon', 'label', 'active' => false])

@php
// Logika Class:
// 1. State Aktif: Background Orange Solid, Teks Putih
// 2. State Normal: Teks Abu-abu
// 3. State Hover: Background Orange, Teks Putih (Agar terbaca jelas)
$classes = ($active ?? false)
            ? 'flex items-center gap-4 px-6 py-4 rounded-2xl bg-[#f97316] text-white font-bold shadow-lg shadow-orange-200 group transition-all duration-300'
            : 'flex items-center gap-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-[#f97316] hover:text-white hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <x-dynamic-component :component="'lucide-' . $icon"
            class="w-5 h-5 {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-white' }} transition-colors" />
    @endif

    <span class="tracking-wide text-sm font-bold">{{ $label }}</span>

    @if($active)
        <div class="ml-auto w-2 h-2 rounded-full bg-white/40 animate-pulse"></div>
    @endif
</a>
