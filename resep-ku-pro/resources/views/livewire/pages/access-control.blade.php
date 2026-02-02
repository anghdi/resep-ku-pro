<div class="p-6 md:p-10 bg-[#f8f9fa] min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">

        @if (session()->has('success'))
            <div class="bg-[#10b981] text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg flex items-center justify-between animate-bounce">
                <div class="flex items-center gap-3">
                    <x-lucide-check-circle class="w-5 h-5" />
                    {{ session('success') }}
                </div>
                <button @click="open = false" class="cursor-pointer opacity-50 hover:opacity-100">×</button>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1 class="text-xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <x-lucide-shield-check class="w-6 h-6 text-[#d4af37]" /> Access Control
            </h1>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Regex Search staff email..."
                   class="w-full md:w-80 pl-6 pr-6 py-3 bg-white border border-gray-100 rounded-2xl shadow-sm outline-none focus:border-[#d4af37] text-sm font-medium">
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($staffList as $user)
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-100 transition-all hover:shadow-md">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                        <div class="flex items-center gap-4 min-w-[200px]">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center font-black text-gray-400">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-sm">{{ $user->name }}</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex-1 overflow-x-auto pb-2">
                            <div class="flex gap-3 min-w-max">
                                @foreach($modules as $module)
                                    @php $p = $allPermissions->where('user_id', $user->id)->where('module', $module)->first(); @endphp
                                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100 min-w-[150px]">
                                        <p class="text-[9px] font-black uppercase text-gray-400 mb-3 tracking-widest">{{ $module }}</p>
                                        <div class="flex gap-1.5">
                                            @foreach(['can_create' => 'C', 'can_read' => 'R', 'can_update' => 'U', 'can_delete' => 'D'] as $f => $l)
                                                <button wire:click="togglePermission('{{ $user->id }}', '{{ $module }}', '{{ $f }}')"
                                                        class="w-8 h-8 rounded-lg font-black text-[10px] cursor-pointer border transition-all
                                                        {{ optional($p)->$f ? 'bg-[#10b981] border-[#10b981] text-white' : 'bg-white border-gray-200 text-gray-300 hover:text-gray-500' }}">
                                                    {{ $l }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button wire:click="resetPermissions('{{ $user->id }}')"
                                wire:confirm="Are you sure you want to revoke all access for this user?"
                                class="p-3 text-gray-300 hover:text-red-500 transition-colors cursor-pointer" title="Reset All Permissions">
                            <x-lucide-rotate-ccw class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2rem] p-20 text-center border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 italic">No staff members found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $staffList->links() }}</div>
    </div>
</div>
