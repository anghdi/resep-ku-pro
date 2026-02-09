<div class="p-6 md:p-10 bg-[#f8f9fa] min-h-screen animate-fade-in">
    <div class="max-w-7xl mx-auto space-y-6">
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-xl shadow-lg transition-all duration-500">
                <x-lucide-check-circle class="w-5 h-5 text-green-500 mr-3" />
                <p class="text-xs font-black text-green-800 uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-xl shadow-lg">
                <x-lucide-alert-triangle class="w-5 h-5 text-red-500 mr-3" />
                <p class="text-xs font-black text-red-800 uppercase tracking-tight">{{ session('error') }}</p>
            </div>
        @endif
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <x-lucide-leaf class="w-5 h-5 text-[#d4af37]" />
                <h2 class="text-xl font-black text-gray-800 tracking-tight">Ingredients Master</h2>
            </div>

            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Ingredient Name</label>
                        <input type="text" wire:model="name" placeholder="Ex: Chicken Breast"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#d4af37] outline-none text-sm font-medium">
                        @error('name')
                            <span class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Price (Rp)</label>
                        <input type="number" wire:model="purchase_price" placeholder="0"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none text-sm font-medium">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Size</label>
                        <input type="number" step="0.01" wire:model="package_size" placeholder="1000"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none text-sm font-medium">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Unit</label>
                        <select wire:model="unit"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none text-sm font-medium cursor-pointer">
                            <option value="gr">Gram (gr)</option>
                            <option value="ml">Milliliter (ml)</option>
                            <option value="pcs">Pieces (pcs)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Yield (%)</label>
                        <input type="number" wire:model="yield"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none text-sm font-medium">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-[#1a1a1a] hover:bg-black text-white py-4 rounded-xl font-black text-xs uppercase tracking-[0.2em] transition-all cursor-pointer shadow-lg active:scale-[0.98]">
                        {{ $is_editing ? 'Update Ingredient' : '+ Save' }}
                    </button>
                    @if ($is_editing)
                        <button type="button" wire:click="resetForm"
                            class="px-8 py-4 bg-gray-100 text-gray-500 rounded-xl font-black text-xs uppercase tracking-widest cursor-pointer hover:bg-gray-200">Cancel</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="relative group">
            <x-lucide-search
                class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-[#d4af37] transition-colors" />
            <input type="text" wire:model.live="search" placeholder="Regex Search (e.g. ^Chicken|Beef$)..."
                class="w-full pl-14 pr-6 py-4 bg-white border border-gray-100 rounded-2xl shadow-sm outline-none focus:border-[#d4af37] transition-all font-medium text-sm">
        </div>

        <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[10px] font-black uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-8 py-5">Ingredient</th>
                        <th class="px-8 py-5">Purchase / Pack</th>
                        <th class="px-8 py-5">Yield</th>
                        <th class="px-8 py-5">Netto / Unit</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($ingredients as $item)
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="px-8 py-5 font-bold text-gray-800">{{ $item->name }}</td>

                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-gray-700">Rp {{ number_format($item->purchase_price) }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium">{{ (float) $item->package_size }}
                                    {{ $item->unit }}</p>
                            </td>

                            <td class="px-8 py-5 font-black text-orange-500 text-sm">{{ (float) $item->yield }}%</td>

                            <td class="px-8 py-5">
                                <p class="text-sm font-black text-green-600">
                                    Rp {{ number_format($item->net_unit_cost, 2) }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium italic">/{{ $item->unit }}</p>
                            </td>

                            <td class="px-8 py-5 text-right space-x-2">
                                <button wire:click="edit('{{ $item->id }}')"
                                    class="p-2 text-gray-300 hover:text-[#d4af37] cursor-pointer"><x-lucide-pencil
                                        class="w-4 h-4" /></button>
                                <button wire:click="delete('{{ $item->id }}')" wire:confirm="Delete?"
                                    class="p-2 text-gray-300 hover:text-red-500 cursor-pointer"><x-lucide-trash-2
                                        class="w-4 h-4" /></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-gray-400 italic font-medium">No
                                ingredients matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-6 bg-gray-50 border-t border-gray-100">
                {{ $ingredients->links() }}
            </div>
        </div>
    </div>
</div>
