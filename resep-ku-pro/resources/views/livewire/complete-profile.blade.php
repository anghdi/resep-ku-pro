<div class="min-h-screen flex items-center justify-center bg-orange-50 p-4 md:p-6 font-sans relative overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-orange-200 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#d4af37]/20 rounded-full blur-3xl opacity-50"></div>

    <div class="max-w-4xl w-full relative z-10" x-data="{ role: '' }">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-3 mb-2">
                <x-lucide-cooking-pot class="w-10 h-10 text-[#d4af37]" />
                <h1 class="text-4xl md:text-5xl font-black text-[#d4af37] tracking-tighter italic">ResepKuPro</h1>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('One last step, Chef!') }}</h2>
            <p class="text-gray-500 font-medium mt-1">{{ __('Tell us your position to personalize your kitchen experience.') }}</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            <div
                @click="role = 'owner'; $wire.set('role', 'owner')"
                :class="role === 'owner' ? 'ring-4 ring-[#f97316] border-transparent scale-[1.02]' : 'border-gray-100 hover:border-orange-200'"
                class="bg-white p-10 rounded-[3rem] shadow-xl cursor-pointer transition-all duration-300 border-4 text-center group relative overflow-hidden"
            >
                <div x-show="role === 'owner'" class="absolute top-6 right-6">
                    <x-lucide-check-circle-2 class="w-6 h-6 text-[#f97316]" />
                </div>

                <div :class="role === 'owner' ? 'bg-[#f97316]' : 'bg-orange-50'" class="w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300 rotate-3 group-hover:rotate-6">
                    <x-lucide-crown :class="role === 'owner' ? 'text-white' : 'text-[#f97316]'" class="w-12 h-12 transition-colors duration-300" />
                </div>

                <h3 class="text-xl font-black uppercase tracking-widest text-gray-800">{{ __('Restaurant Owner') }}</h3>
                <p class="text-sm text-gray-500 mt-3 font-medium leading-relaxed">
                    {{ __('I want to manage my own brand, recipes, and supervise my kitchen team.') }}
                </p>
            </div>

            <div
                @click="role = 'staff'; $wire.set('role', 'staff')"
                :class="role === 'staff' ? 'ring-4 ring-[#f97316] border-transparent scale-[1.02]' : 'border-gray-100 hover:border-orange-200'"
                class="bg-white p-10 rounded-[3rem] shadow-xl cursor-pointer transition-all duration-300 border-4 text-center group relative overflow-hidden"
            >
                <div x-show="role === 'staff'" class="absolute top-6 right-6">
                    <x-lucide-check-circle-2 class="w-6 h-6 text-[#f97316]" />
                </div>

                <div :class="role === 'staff' ? 'bg-[#f97316]' : 'bg-orange-50'" class="w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300 -rotate-3 group-hover:-rotate-6">
                    <x-lucide-chef-hat :class="role === 'staff' ? 'text-white' : 'text-[#f97316]'" class="w-12 h-12 transition-colors duration-300" />
                </div>

                <h3 class="text-xl font-black uppercase tracking-widest text-gray-800">{{ __('Kitchen Staff') }}</h3>
                <p class="text-sm text-gray-500 mt-3 font-medium leading-relaxed">
                    {{ __('I am joining an existing team and need access to shared recipe standards.') }}
                </p>
            </div>
        </div>

        <div
            x-show="role"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="mt-10 bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl border-b-8 border-[#d4af37] max-w-2xl mx-auto"
        >
            <div x-show="role === 'owner'" class="space-y-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-lucide-store class="w-5 h-5 text-[#f97316]" />
                    <label class="text-sm font-black uppercase tracking-widest text-gray-700">{{ __('Brand Identity') }}</label>
                </div>
                <input type="text" wire:model="brand_name" class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-[#f97316] focus:bg-white outline-none transition-all font-bold text-gray-700 shadow-inner" placeholder="{{ __('e.g. HOLMES Kitchen') }}">
                <p class="text-[10px] text-gray-400 italic">{{ __('* This will be the main name for your recipe collection.') }}</p>
            </div>

            <div x-show="role === 'staff'" class="space-y-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-lucide-key-round class="w-5 h-5 text-[#f97316]" />
                    <label class="text-sm font-black uppercase tracking-widest text-gray-700">{{ __('Team Connection') }}</label>
                </div>
                <input type="text" wire:model="team_org_id" class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-[#f97316] focus:bg-white outline-none transition-all font-bold text-gray-700 shadow-inner" placeholder="{{ __('Paste Org ID here') }}">
                <p class="text-[10px] text-gray-400 italic">{{ __('* Ask your Head Chef or Manager for the unique Organization ID.') }}</p>
            </div>

            <button
                wire:click="saveProfile"
                class="w-full bg-gradient-to-r from-[#f97316] to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-orange-200 mt-8 uppercase tracking-[0.2em] text-lg transform transition-all hover:-translate-y-1 active:scale-[0.98]"
            >
                {{ __('Let\'s Start Cooking!') }}
            </button>
        </div>
    </div>
</div>
