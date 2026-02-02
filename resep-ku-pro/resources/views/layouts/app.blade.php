<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<style>
    /* Custom Scrollbar for Sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #f97316;
    }
</style>

<body>
    <div class="min-h-screen bg-[#f8fafc] flex overflow-hidden font-sans" x-data="{ sidebarOpen: false }">

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/20 backdrop-blur-sm md:hidden transition-opacity"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 transition-transform duration-300 md:relative md:translate-x-0 flex flex-col shadow-sm">

            <div class="p-8">
                <h1 class="text-2xl font-black italic text-[#f97316] tracking-tighter">
                    Resep<span class="text-gray-800">Ku</span>Pro
                </h1>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">
                <div class="px-6 pt-4 pb-2">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">{{ __('Operations') }}
                    </p>
                </div>

                <x-nav-link icon="layout-dashboard" label="Dashboard" :active="request()->routeIs('dashboard')" href="{{ route('dashboard') }}"
                    wire:navigate />

                <x-nav-link icon="utensils" label="Manage Ingredients" :active="request()->routeIs('ingredients.*')"
                    href="{{ route('ingredients.index') }}" wire:navigate />

                <x-nav-link icon="tag" label="Manage Categories" :active="request()->routeIs('categories.*')"
                    href="{{ route('categories.index') }}" wire:navigate />

                <x-nav-link icon="plus-circle" label="Add New Menu" :active="request()->routeIs('add-new-menu.*')" href="{{ route('add-new-menu') }}"
                    wire:navigate />

                <x-nav-link icon="file-spreadsheet" label="Costing & SOP" :active="request()->routeIs('costing.*')" href="#"
                    wire:navigate />

                @role(['owner', 'manager'])
                    <div class="px-6 pt-6 pb-2">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">{{ __('Management') }}
                        </p>
                    </div>

                    <x-nav-link icon="settings" label="Management Center" :active="request()->routeIs('management-center')"
                        href="{{ route('management-center') }}" wire:navigate />
                @endrole

                @role('owner')
                    <x-nav-link icon="history" label="Activity Logs" :active="request()->routeIs('activity-logs.*')" href="{{ route('activity.logs') }}"
                        wire:navigate />
                    <x-nav-link icon="shield-check" label="Access Control" :active="request()->routeIs('access-control')"
                        href="{{ route('access-control') }}" wire:navigate />
                @endrole
            </nav>

            <div class="p-4 border-t border-gray-50">
                <button
                    class="w-full flex items-center justify-center gap-3 p-3.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all font-black uppercase text-[10px] tracking-widest cursor-pointer group">
                    <x-lucide-log-out class="w-4 h-4 transition-transform group-hover:-translate-x-1" />
                    {{ __('Sign Out') }}
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <header
                class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-4 md:px-8 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true"
                        class="md:hidden p-2 text-gray-500 hover:bg-gray-50 rounded-lg cursor-pointer">
                        <x-lucide-menu class="w-6 h-6" />
                    </button>
                    {{-- <h2 class="hidden md:block font-bold text-gray-800">
                        {{ $title ?? 'ResepKuPro' }}
                    </h2> --}}
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#f97316]">
                            {{ auth()->user()->role ?? 'Owner' }}</p>
                        <p class="text-sm font-bold text-gray-800">{{ auth()->user()->full_name }}</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-orange-100 border border-orange-200 flex items-center justify-center text-[#f97316] font-black shadow-sm">
                        {{ substr(auth()->user()->full_name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>
