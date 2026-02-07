<div class="min-h-screen flex items-center justify-center bg-orange-50 p-4 font-sans relative overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-orange-200 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#d4af37]/20 rounded-full blur-3xl opacity-50"></div>

    <div class="max-w-6xl w-full relative z-10" x-data="{
        role: @entangle('role'),
        generateOrgId() {
            if (this.role === 'owner') {
                const prefix = $wire.brand_name ? $wire.brand_name.substring(0, 10).toUpperCase() : 'RES';
                const random = Math.floor(1000 + Math.random() * 9000);
                $wire.set('team_org_id', prefix + '-' + random);
            }
        }
    }">

        <div class="grid md:grid-cols-3 gap-6">
            <div @click="role = 'owner'; generateOrgId()"
                :class="role === 'owner' ? 'ring-4 ring-[#f97316] scale-[1.02]' : 'border-gray-100'"
                class="bg-white p-8 rounded-[2.5rem] shadow-xl cursor-pointer transition-all border-4 text-center group">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 flex items-center justify-center mx-auto mb-4">
                    <x-lucide-crown class="w-8 h-8 text-[#f97316]" />
                </div>
                <h3 class="font-black uppercase tracking-widest">{{ __('Owner') }}</h3>
            </div>

            <div @click="role = 'manager'"
                :class="role === 'manager' ? 'ring-4 ring-[#f97316] scale-[1.02]' : 'border-gray-100'"
                class="bg-white p-8 rounded-[2.5rem] shadow-xl cursor-pointer transition-all border-4 text-center group">
                <div :class="role === 'manager' ? 'bg-[#f97316]' : 'bg-orange-50'"
                    class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <x-lucide-clipboard-check :class="role === 'manager' ? 'text-white' : 'text-[#f97316]'" class="w-8 h-8" />
                </div>
                <h3 class="font-black uppercase tracking-widest">{{ __('Manager') }}</h3>
            </div>

            <div @click="role = 'staff'"
                :class="role === 'staff' ? 'ring-4 ring-[#f97316] scale-[1.02]' : 'border-gray-100'"
                class="bg-white p-8 rounded-[2.5rem] shadow-xl cursor-pointer transition-all border-4 text-center group">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 flex items-center justify-center mx-auto mb-4">
                    <x-lucide-chef-hat class="w-8 h-8 text-[#f97316]" />
                </div>
                <h3 class="font-black uppercase tracking-widest">{{ __('Staff') }}</h3>
            </div>
        </div>

        <div x-show="role" x-transition class="mt-8 bg-white p-8 rounded-[3rem] shadow-2xl max-w-xl mx-auto space-y-6">

            @error('role')
                <span class="text-red-500 text-[10px] font-black uppercase italic">{{ $message }}</span>
            @enderror

            <div>
                <label class="text-xs font-black uppercase tracking-widest text-gray-400">Full Name</label>
                <input type="text" wire:model="full_name"
                    class="w-full px-5 py-3 bg-gray-50 border-2 rounded-xl focus:border-[#f97316] outline-none font-bold @error('full_name') border-red-500 @enderror">
                @error('full_name')
                    <span class="text-red-500 text-[10px] font-bold italic mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div x-show="role === 'owner'" class="space-y-4">
                <div>
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400">Brand Name</label>
                    <input type="text" wire:model.live="brand_name" @input.debounce.500ms="generateOrgId()"
                        class="w-full px-5 py-3 bg-gray-50 border-2 rounded-xl focus:border-[#f97316] outline-none font-bold @error('brand_name') border-red-500 @enderror">
                    @error('brand_name')
                        <span class="text-red-500 text-[10px] font-bold italic mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400">Generated Org ID</label>
                    <input type="text" wire:model="team_org_id" readonly
                        class="w-full px-5 py-3 bg-gray-100 border-2 rounded-xl font-mono font-bold text-[#f97316]">
                </div>
            </div>

            <div x-show="role === 'manager' || role === 'staff'" class="space-y-4">
                <label class="text-xs font-black uppercase tracking-widest text-gray-400">Paste Team Org ID</label>
                <input type="text" wire:model="team_org_id"
                    class="w-full px-5 py-3 bg-gray-50 border-2 rounded-xl focus:border-[#f97316] outline-none font-bold @error('team_org_id') border-red-500 @enderror"
                    placeholder="ID from your Owner">
                @error('team_org_id')
                    <span class="text-red-500 text-[10px] font-bold italic mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button wire:click="saveProfile"
                class="w-full bg-[#f97316] text-white font-black py-4 rounded-xl shadow-lg hover:bg-orange-600 transition-all">
                {{ __('START COOKING!') }}
            </button>
        </div>
    </div>
</div>
