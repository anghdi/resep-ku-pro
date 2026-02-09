<div class="w-full max-w-6xl mx-auto space-y-8 py-6">

    <div class="flex items-center gap-4 mb-8">
        <div class="p-3 bg-white rounded-2xl shadow-sm border border-orange-100">
            <x-lucide-settings class="w-8 h-8 text-[#f97316]" />
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">{{ __('Management Center') }}</h2>
            <p class="text-sm text-gray-400 font-medium italic">Kelola ekosistem dapur profesional Anda.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm font-bold text-sm italic">
            {{ session('error') }}
        </div>
    @endif

    <section class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-orange-900/5 border border-gray-50">
        <div class="flex items-center gap-3 mb-6">
            <x-lucide-map-pin class="w-6 h-6 text-[#f97316]" />
            <h3 class="font-black text-gray-800 uppercase tracking-widest text-sm">{{ __('Manage Outlets') }}</h3>
        </div>

        <form wire:submit="{{ $is_editing ? 'updateOutlet' : 'saveOutlet' }}"
            class="flex flex-col sm:flex-row gap-3 mb-8">
            <div class="flex-1">
                <input type="text" wire:model="outlet_name"
                    placeholder="{{ $is_editing ? 'Change the outlet name...' : 'New Outlet Name...' }}"
                    class="w-full px-5 py-3.5 bg-gray-50 border {{ $is_editing ? 'border-orange-300 ring-2 ring-orange-100' : 'border-gray-100' }} rounded-xl focus:border-[#f97316] outline-none transition-all font-medium text-sm">
                @error('outlet_name')
                    <p class="text-red-500 text-[10px] font-bold mt-1.5 ml-2 italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" wire:loading.attr="disabled"
                    class="{{ $is_editing ? 'bg-[#f97316]' : 'bg-[#1a1a1a]' }} hover:opacity-90 text-white px-8 py-3.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all cursor-pointer disabled:opacity-50">
                    {{ $is_editing ? __('Update Outlet') : __('Add Outlet') }}
                </button>

                @if ($is_editing)
                    <button type="button" wire:click="cancelEdit"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-3.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all cursor-pointer">
                        {{ __('Cancel') }}
                    </button>
                @endif
            </div>
        </form>

        {{-- <button wire:click="editOutlet('{{ $outlet->id }}')"
            class="p-2 text-gray-300 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all cursor-pointer">
            <x-lucide-pencil class="w-4 h-4" />
        </button> --}}

        <div class="overflow-hidden border border-gray-50 rounded-2xl">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">
                    <tr>
                        <th class="px-8 py-4">{{ __('Outlet Name') }}</th>
                        <th class="px-8 py-4 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($outlets as $outlet)
                        <tr class="group hover:bg-orange-50 transition-colors">
                            <td class="px-6 py-5 font-bold text-gray-700">
                                {{ $outlet->name }}
                            </td>

                            <td class="px-6 py-5 text-right space-x-2">
                                <button wire:click="editOutlet('{{ $outlet->id }}')"
                                    class="p-2 text-gray-300 hover:text-orange-500 cursor-pointer transition-colors">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </button>

                                <button wire:click="deleteOutlet('{{ $outlet->id }}')"
                                    wire:confirm="Are you sure you want to delete this outlet?"
                                    class="p-2 text-gray-300 hover:text-red-500 cursor-pointer transition-colors">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    @empty <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">
                                Belum ada outlet terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section
        class="bg-[#fffbeb] rounded-[2.5rem] p-8 md:p-12 border-2 border-dashed border-[#fcd34d] shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <x-lucide-party-popper class="w-6 h-6 text-[#f97316]" />
                <h3 class="font-black text-gray-800 uppercase tracking-widest text-sm">
                    {{ __('Invite Your Staff') }}
                </h3>
            </div>

            <p class="text-xs text-gray-500 font-medium mb-8 leading-relaxed max-w-lg">
                {{ __("Share your organization's unique code so staff can join the Chef team. This code is permanent and confidential.") }}
            </p>

            <div
                class="max-w-2xl bg-white px-8 py-5 rounded-2xl border-2 border-[#fcd34d] flex items-center justify-between group transition-all hover:shadow-lg">
                <span class="font-black text-2xl md:text-3xl tracking-[0.5em] text-gray-800 select-all">
                    {{ auth()->user()->org_id }}
                </span>

                <button @click="navigator.clipboard.writeText('{{ auth()->user()->org_id }}'); alert('Code copied!')"
                    class="flex items-center gap-2 bg-[#1a1a1a] text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all cursor-pointer shadow-md active:scale-95">
                    <x-lucide-copy class="w-4 h-4" />
                    <span>{{ __('Copy Code') }}</span>
                </button>
            </div>
        </div>
    </section>

    <section class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-orange-900/5 border border-gray-50">
        <div class="flex items-center gap-3 mb-6">
            <x-lucide-users-round class="w-6 h-6 text-[#f97316]" />
            <h3 class="font-black text-gray-800 uppercase tracking-widest text-sm">{{ __('Team Members List') }}</h3>
        </div>

        <div class="overflow-hidden border border-gray-50 rounded-2xl">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">
                    <tr>
                        <th class="px-8 py-4 text-xs font-black uppercase">{{ __('Name') }}</th>
                        <th class="px-8 py-4 text-xs font-black uppercase">{{ __('Email') }}</th>
                        <th class="px-8 py-4 text-xs font-black uppercase">{{ __('Role') }}</th>
                        <th class="px-8 py-4 text-right text-xs font-black uppercase">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach ($team as $member)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-4 font-bold text-gray-800">{{ $member->full_name }}</td>
                            <td class="px-8 py-4 text-gray-500 font-medium">{{ $member->email }}</td>
                            <td class="px-8 py-4">
                                <span
                                    class="px-3 py-1 bg-orange-100 text-[#f97316] rounded-lg text-[10px] font-black uppercase tracking-widest border border-orange-200">
                                    {{ $member->getRoleNames()->first() ?? 'Staff' }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right text-gray-300 font-bold">—</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
