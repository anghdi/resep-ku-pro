<div class="p-6 md:p-10 bg-[#f8f9fa] min-h-screen">
    <div class="max-w-4xl mx-auto space-y-6">

        @if (session()->has('success'))
            <div class="bg-[#10b981] text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg animate-fade-in flex items-center gap-3">
                <x-lucide-check-circle class="w-5 h-5" /> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <h2 class="text-xl font-black text-gray-800 mb-8 flex items-center gap-3">
                <x-lucide-tag class="w-6 h-6 text-[#d4af37]" /> Manage Categories
            </h2>

            <form wire:submit="save" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Category Name</label>
                    <input type="text" wire:model="name" placeholder="Ex: Main Course"
                           class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:border-[#d4af37] text-sm font-medium">
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="bg-[#1a1a1a] hover:bg-black text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all cursor-pointer shadow-lg active:scale-95">
                        {{ $is_editing ? 'Update' : 'Save' }}
                    </button>
                    @if($is_editing)
                        <button type="button" wire:click="resetForm" class="bg-gray-100 text-gray-500 px-6 py-4 rounded-2xl font-black text-xs uppercase cursor-pointer hover:bg-gray-200">Cancel</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="space-y-4">
            <div class="relative">
                <x-lucide-search class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search category (Regex)..."
                       class="w-full pl-14 pr-6 py-4 bg-white border border-gray-100 rounded-2xl shadow-sm outline-none focus:border-[#d4af37] text-sm font-medium">
            </div>

            <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-gray-100">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-8 py-5">Category Name</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($categories as $cat)
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="px-8 py-5 font-bold text-gray-700">{{ $cat->name }}</td>
                                <td class="px-8 py-5 text-right space-x-2">
                                    <button wire:click="edit('{{ $cat->id }}')" class="p-2 text-gray-300 hover:text-[#d4af37] cursor-pointer"><x-lucide-pencil class="w-4 h-4" /></button>
                                    <button wire:click="delete('{{ $cat->id }}')" wire:confirm="Delete this category?" class="p-2 text-gray-300 hover:text-red-500 cursor-pointer"><x-lucide-trash-2 class="w-4 h-4" /></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
