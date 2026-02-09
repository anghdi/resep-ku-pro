<div class="p-6 md:p-10 space-y-8 bg-[#f8f9fa] min-h-screen">
    <div
        class="flex flex-col lg:flex-row justify-between gap-6 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex items-center gap-6 flex-1">
            <div class="w-full max-w-xs">
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Select Outlet</label>
                <select wire:model.live="selectedOutlet"
                    class="w-full p-3 bg-gray-50 border-none rounded-xl font-bold text-sm outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Choose Outlet</option>
                    @foreach ($outlets as $outlet)
                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full max-w-xs">
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block">Select Menu</label>
                <select wire:model.live="selectedRecipeId"
                    class="w-full p-3 bg-gray-50 border-none rounded-xl font-bold text-sm outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Choose Menu</option>
                    @foreach ($recipes as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="px-6 py-3 bg-[#1e293b] text-white rounded-xl font-bold text-xs flex items-center gap-2"><x-lucide-file-text
                    class="w-4 h-4" /> Report PDF</button>
            <button
                class="px-6 py-3 bg-[#22c55e] text-white rounded-xl font-bold text-xs flex items-center gap-2"><x-lucide-file-spreadsheet
                    class="w-4 h-4" /> Excel</button>
        </div>
    </div>

    @if ($recipe)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 flex flex-col items-center text-center">
                <img src="{{ Storage::url($recipe->image_url) }}"
                    class="w-32 h-32 rounded-3xl object-cover mb-4 shadow-md"
                    onerror="this.src='https://placehold.co/200?text=No+Photo'">
                <h3 class="font-black text-gray-800 uppercase tracking-tighter">{{ $recipe->name }}</h3>
                <span
                    class="px-3 py-1 bg-orange-50 text-[9px] font-black text-orange-500 rounded-full mt-2 uppercase tracking-widest">{{ $recipe->category }}</span>
            </div>

            <div
                class="bg-[#f97316] p-8 rounded-[2rem] flex flex-col justify-center items-center text-white shadow-lg shadow-orange-100">
                <p class="text-[9px] font-black uppercase tracking-widest opacity-80">Food Cost (+5% Fluk & +10% Tax)
                </p>
                <h2 class="text-2xl font-black mt-2">Rp {{ number_format($finalCost, 0, ',', '.') }}</h2>
            </div>

            <div
                class="bg-[#3b82f6] p-8 rounded-[2rem] flex flex-col justify-center items-center text-white shadow-lg shadow-blue-100">
                <p class="text-[9px] font-black uppercase tracking-widest opacity-80">Suggested Price (30%)</p>
                <h2 class="text-2xl font-black mt-2">Rp {{ number_format($suggestedPrice, 0, ',', '.') }}</h2>
            </div>

            <div
                class="bg-[#22c55e] p-8 rounded-[2rem] flex flex-col justify-center items-center text-white shadow-lg shadow-green-100">
                <p class="text-[9px] font-black uppercase tracking-widest opacity-80">Selling Price</p>
                <h2 class="text-2xl font-black mt-2">Rp {{ number_format($recipe->selling_price, 0, ',', '.') }}</h2>
            </div>

            <div
                class="p-8 rounded-[2rem] flex flex-col justify-center items-center text-white shadow-lg
            {{ $foodCostPercent < 30 ? 'bg-green-500' : ($foodCostPercent <= 40 ? 'bg-orange-400' : 'bg-red-500') }}">
                <p class="text-[9px] font-black uppercase tracking-widest opacity-80">Food Cost %</p>
                <h2 class="text-3xl font-black mt-2">{{ number_format($foodCostPercent, 1) }}%</h2>
            </div>
        </div>

        @if (auth()->user()->hasAccess('menus', 'edit'))
            <div class="bg-white p-8 rounded-[2rem] border border-gray-100 flex items-center gap-6">
                <div class="flex-1">
                    <h4 class="text-xs font-black text-gray-800 uppercase flex items-center gap-2"><x-lucide-coins
                            class="w-4 h-4 text-orange-500" /> Update Selling Price</h4>
                    <div class="flex items-center gap-4 mt-4">
                        <input type="number" wire:model="newSellingPrice"
                            class="bg-gray-50 border-none rounded-xl p-3 font-bold w-48 focus:ring-2 focus:ring-orange-500">
                        <button wire:click="updateSellingPrice"
                            class="bg-[#22c55e] text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:shadow-lg transition-all">Update</button>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center gap-2">
                <x-lucide-scroll-text class="w-5 h-5 text-orange-500" />
                <h4 class="text-xs font-black text-gray-800 uppercase tracking-widest">Ingredients List</h4>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Ingredient</th>
                        <th class="px-8 py-4">Quantity</th>
                        <th class="px-8 py-4">Price/U</th>
                        <th class="px-8 py-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold text-gray-600 divide-y divide-gray-50">
                    @foreach ($recipe->ingredients as $item)
                        @php $pricePerU = $item->ingredient->purchase_price / $item->ingredient->package_size; @endphp
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="px-8 py-4 text-gray-800">{{ $item->ingredient->name }}</td>
                            <td class="px-8 py-4">{{ $item->amount_needed }} {{ $item->ingredient->unit }}</td>
                            <td class="px-8 py-4">{{ number_format($pricePerU, 0, ',', '.') }}</td>
                            <td class="px-8 py-4 text-right">Rp
                                {{ number_format($pricePerU * $item->amount_needed, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50/80 font-black text-gray-800">
                    <tr>
                        <td colspan="3" class="px-8 py-4">TOTAL COST</td>
                        <td class="px-8 py-4 text-right text-orange-600">Rp {{ number_format($rawCost, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
    <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden mt-8">
        <div class="p-6 border-b border-gray-50 flex items-center gap-2">
            <x-lucide-chef-hat class="w-5 h-5 text-orange-500" />
            <h4 class="text-xs font-black text-gray-800 uppercase tracking-widest">Preparation Method (SOP)</h4>
        </div>

        <div class="p-8">
            @if ($recipe && $recipe->method)
                <div class="prose prose-sm max-w-none text-gray-600 font-bold leading-relaxed whitespace-pre-line">
                    {{ $recipe->method }}
                </div>
            @else
                <div class="flex items-center gap-3 p-6 bg-orange-50/50 border border-orange-100 rounded-2xl">
                    <x-lucide-info class="w-5 h-5 text-orange-400" />
                    <p class="text-sm italic text-orange-400">SOP has not been filled.</p>
                </div>
            @endif
        </div>
    </div>
</div>
