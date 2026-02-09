<div class="p-6 md:p-10 bg-[#f8f9fa] min-h-screen">
    <div class="max-w-7xl mx-auto space-y-10">

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

        <div class="bg-white rounded-[1.5rem] overflow-hidden shadow-sm border-t-4 border-[#d4af37]">
            <div class="p-8">
                <h2 class="text-md font-black text-gray-800 mb-8 flex items-center gap-2 uppercase tracking-tight">
                    <span class="text-[#d4af37]">|</span> 1. Create New Recipe
                </h2>

                <form wire:submit="saveRecipe" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Menu
                                    Name</label>
                                <input type="text" wire:model="menu_name"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-[#d4af37] text-sm font-medium">
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Category</label>
                                <select wire:model="category"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none cursor-pointer text-sm font-medium">
                                    <option value="">Main Course, Drink, etc...</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Outlet /
                                    Station</label>
                                <select wire:model="outlet"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none cursor-pointer text-sm font-medium">
                                    <option value="">-- Select Outlet --</option>
                                    @foreach ($outlets as $ot)
                                        <option value="{{ $ot->name }}">{{ $ot->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Food
                                    Photo</label>
                                <input type="file" wire:model="food_photo" class="text-xs">
                                <div
                                    class="mt-4 w-full h-32 border border-dashed border-gray-200 rounded-2xl flex items-center justify-center text-[10px] text-gray-300 font-bold uppercase overflow-hidden">
                                    @if ($food_photo)
                                        <img src="{{ $food_photo->temporaryUrl() }}" class="h-full w-full object-cover">
                                    @else
                                        Photo Preview
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-[#3b82f6] hover:bg-blue-600 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest cursor-pointer shadow-lg active:scale-95 transition-all">
                        <x-lucide-save class="w-4 h-4 inline mr-2" /> Save New Recipe
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-[1.5rem] overflow-hidden shadow-sm border-t-4 border-[#d4af37]">
            <div class="p-8">
                <h2 class="text-md font-black text-gray-800 mb-8 flex items-center gap-2 uppercase tracking-tight">
                    <span class="text-[#d4af37]">|</span> 2. Manage Recipe Details
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-10">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Filter by
                            Outlet:</label>
                        <select wire:model.live="filter_outlet"
                            class="w-full px-4 py-3 border border-[#10b981] rounded-xl outline-none text-[#10b981] font-bold text-sm cursor-pointer">
                            <option value="">All Outlets</option>
                            @foreach ($outlets as $ot)
                                <option value="{{ $ot->name }}">{{ $ot->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Select Recipe to
                            Edit:</label>
                        <select wire:model.live="selected_recipe_id"
                            class="w-full px-4 py-3 border border-[#d4af37] rounded-xl outline-none text-[#d4af37] font-bold text-sm cursor-pointer">
                            <option value="">-- Choose Recipe --</option>
                            @foreach ($recipes as $rec)
                                <option value="{{ $rec->id }}">{{ $rec->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if ($selected_recipe_id)
                    <div class="space-y-10 animate-fade-in">
                        <div class="p-6 border border-orange-100 rounded-2xl bg-orange-50/10">
                            <h3 class="text-xs font-black uppercase text-gray-800 mb-6 flex items-center gap-2">
                                <x-lucide-pencil class="w-4 h-4 text-[#d4af37]" /> Edit Menu Info & Photo
                            </h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <input type="text" wire:model="edit_name"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm font-bold">
                                    <select wire:model="edit_category"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <select wire:model="edit_outlet"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm">
                                        @foreach ($outlets as $ot)
                                            <option value="{{ $ot->name }}">{{ $ot->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="h-32 border border-dashed rounded-xl overflow-hidden bg-white">
                                    @if ($edit_image_url)
                                        <img src="{{ asset('storage/' . $edit_image_url) }}"
                                            class="w-full h-full object-cover">
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-8">
                                <button wire:click="updateInfo"
                                    class="bg-[#a855f7] hover:bg-purple-700 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-2">
                                    <x-lucide-refresh-cw class="w-3 h-3" /> Update Info
                                </button>

                                <button wire:click="duplicateRecipe"
                                    wire:confirm="Are you sure you want to duplicate this recipe and all its ingredients?"
                                    class="bg-[#22c55e] hover:bg-green-600 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-2">
                                    <x-lucide-copy class="w-3 h-3" /> Duplicate
                                </button>

                                <button wire:click="deleteRecipe"
                                    wire:confirm="Are you sure you want to permanently delete this recipe? This action cannot be undone."
                                    class="bg-[#ef4444] hover:bg-red-600 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest cursor-pointer transition-all active:scale-95 flex items-center justify-center gap-2">
                                    <x-lucide-trash-2 class="w-3 h-3" /> Delete
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black uppercase text-gray-800 flex items-center gap-2">🍓 Add
                                Ingredient to Recipe</h3>
                            <div class="flex gap-2">
                                <select wire:model="selected_ingredient"
                                    class="flex-1 px-4 py-3 border border-[#3b82f6] rounded-xl text-xs font-bold outline-none cursor-pointer">
                                    <option value="">-- Select Ingredient --</option>
                                    @foreach ($all_ingredients as $ing)
                                        <option value="{{ $ing->id }}">{{ $ing->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" wire:model="amount_needed" placeholder="Qty"
                                    class="w-32 px-4 py-3 border border-gray-200 rounded-xl text-xs font-bold outline-none">
                                <button wire:click="addIngredient"
                                    class="bg-[#d4af37] text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase cursor-pointer">ADD</button>
                            </div>
                            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-gray-800 text-white uppercase font-black tracking-widest">
                                        <tr>
                                            <th class="px-6 py-4">Ingredient</th>
                                            <th class="px-6 py-4">Qty</th>
                                            <th class="px-6 py-4">Cost Est.</th>
                                            <th class="px-6 py-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="font-bold text-gray-600 divide-y divide-gray-50">
                                        @foreach ($recipe_ingredients as $item)
                                            <tr>
                                                <td class="px-6 py-4">{{ $item->ingredient->name }}</td>
                                                <td class="px-6 py-4">{{ $item->amount_needed }}</td>
                                                <td class="px-6 py-4 text-green-500">Rp...</td>
                                                <td class="px-6 py-4 text-red-400">✕</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($selected_recipe_id)
                    <div class="mt-10 space-y-8 animate-fade-in">

                        <div class="p-1 border-2 border-red-500 rounded-[2rem] bg-white shadow-sm overflow-hidden">
                            <div class="p-8">
                                <h3
                                    class="text-[10px] font-black uppercase text-gray-400 mb-6 flex items-center gap-2 tracking-[0.2em]">
                                    <x-lucide-file-text class="w-4 h-4" /> Cooking Method (SOP)
                                </h3>

                                <textarea wire:model="edit_method" rows="6"
                                    class="w-full p-8 bg-gray-50 border-2 {{ $errors->has('edit_method') ? 'border-red-200' : 'border-gray-50' }} rounded-[1.5rem] text-sm font-medium text-gray-600 outline-none focus:border-[#f97316] focus:bg-white transition-all resize-none shadow-inner"
                                    placeholder="Step 1: Preparation... Step 2: Cooking process..."></textarea>

                                @error('edit_method')
                                    <span
                                        class="text-red-500 text-[10px] font-bold italic mt-2 block ml-2">{{ $message }}</span>
                                @enderror

                                <button wire:click="saveSOP" wire:loading.attr="disabled"
                                    class="w-full mt-6 bg-[#1a1a1a] hover:bg-black text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.3em] transition-all cursor-pointer shadow-xl active:scale-[0.98] flex items-center justify-center gap-3 group">

                                    <div wire:loading.remove wire:target="saveSOP" class="flex items-center gap-2">
                                        <x-lucide-check-circle
                                            class="w-4 h-4 text-green-400 group-hover:scale-125 transition-transform" />
                                        <span>Save SOP Changes</span>
                                    </div>

                                    <div wire:loading wire:target="saveSOP" class="flex items-center gap-3">
                                        <svg class="animate-spin h-4 w-4 text-orange-500" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" fill="none"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span>Recording SOP...</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
