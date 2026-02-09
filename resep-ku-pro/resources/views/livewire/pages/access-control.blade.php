<div class="p-6 md:p-10 bg-[#f8f9fa] min-h-screen font-sans">
    <div class="max-w-7xl mx-auto space-y-8">

        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tighter flex items-center gap-3">
                    <x-lucide-shield-check class="w-8 h-8 text-[#f97316]" /> Access Control
                </h1>
                <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Manage Staff Permissions</p>
            </div>
            <div class="relative w-full md:w-80">
                <input type="text" wire:model.live="search" placeholder="Search staff email..."
                    class="w-full pl-12 pr-6 py-3 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold outline-none focus:border-orange-400 focus:bg-white transition-all">
                <x-lucide-search class="absolute left-4 top-3.5 w-5 h-5 text-gray-300" />
            </div>
        </div>

        <div class="space-y-6">
            @foreach ($staffList as $staff)
                <div
                    class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 p-8 hover:shadow-md transition-all group relative">

                    <button wire:click="resetPermissions({{ $staff->id }})"
                        wire:confirm="Are you sure you want to revoke all permissions for this user?"
                        class="absolute top-8 right-8 p-3 bg-gray-50 hover:bg-red-50 text-gray-300 hover:text-red-500 rounded-xl transition-all cursor-pointer">
                        <x-lucide-rotate-ccw class="w-5 h-5" />
                    </button>

                    <div class="flex flex-col lg:flex-row gap-10">
                        <div class="flex items-center gap-5 min-w-[250px]">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl flex items-center justify-center font-black text-[#f97316] text-xl shadow-inner uppercase">
                                {{ substr($staff->email, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800 break-all">{{ $staff->email }}</p>
                                <span
                                    class="px-3 py-1 bg-gray-100 rounded-full text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1 inline-block">{{ $staff->role }}</span>
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            @foreach ($modules as $modId => $modName)
                                <div class="bg-gray-50/50 p-5 rounded-[1.5rem] border border-gray-100/30 space-y-4">
                                    <label
                                        class="text-[10px] font-black uppercase text-gray-400 tracking-[0.15em] block border-b border-orange-100/50 pb-2">
                                        {{ $modName }}
                                    </label>

                                    <div class="space-y-3">
                                    @php
                                        // 1. SESUAIKAN KEY DENGAN NAMA KOLOM DB (read, create, update, delete)
                                        $permissionMap = [
                                            'read' => 'View',
                                            'create' => 'Add',
                                            'update' => 'Edit',
                                            'delete' => 'Remove',
                                        ];
                                    @endphp

                                    @foreach ($permissionMap as $key => $label)
                                        @php
                                            // Menghasilkan can_read, can_create, can_update, can_delete
                                            $dbField = 'can_' . $key;
                                            $hasPerm = $staff->permissions->where('module', $modId)->first();

                                            // Pastikan casting ke boolean agar checked-nya akurat
                                            $isChecked = $hasPerm ? (bool) $hasPerm->$dbField : false;
                                        @endphp

                                        <label
                                            wire:key="perm-{{ $staff->id }}-{{ $modId }}-{{ $key }}"
                                            class="flex items-center gap-3 cursor-pointer group/perm">
                                            <input type="checkbox" {{-- 2. TAMBAHKAN TANDA PETIK DI ID STAFF (UUID FIX) --}}
                                                wire:click="togglePermission('{{ $staff->id }}', '{{ $modId }}', '{{ $dbField }}')"
                                                {{ $isChecked ? 'checked' : '' }}
                                                class="w-5 h-5 rounded-lg border-2 border-gray-200 text-[#f97316] focus:ring-orange-500 cursor-pointer transition-all">

                                            <span
                                                class="text-[11px] font-extrabold {{ $isChecked ? 'text-gray-900' : 'text-gray-400' }} {{ $key == 'delete' && $isChecked ? 'text-red-600' : '' }} uppercase transition-colors">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                        </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach
</div>

<div class="mt-8">
    {{ $staffList->links() }}
</div>

</div>
</div>
