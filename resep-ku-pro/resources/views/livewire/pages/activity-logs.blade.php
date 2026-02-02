<div class="p-6 md:p-10 bg-[#f8f9fa] min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center gap-3">
            <x-lucide-clipboard-list class="w-6 h-6 text-gray-800" />
            <h1 class="text-xl font-black text-gray-800 tracking-tight">Activity Logs (CCTV)</h1>
        </div>

        <div class="relative group">
            <x-lucide-search class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-[#d4af37] transition-colors" />
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search logs with Regex (e.g. Tambah|Hapus)..."
                   class="w-full pl-14 pr-6 py-4 bg-white border border-gray-100 rounded-2xl shadow-sm outline-none focus:border-[#d4af37] transition-all font-medium text-sm">
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <x-lucide-history class="w-5 h-5 text-gray-800" />
                <h2 class="text-md font-bold text-gray-800">Team Activity History</h2>
            </div>
            <p class="text-xs text-gray-400 mb-8 font-medium">All data changes are automatically recorded for mutual transparency.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-50">
                        <tr>
                            <th class="px-4 py-4">Timestamp</th>
                            <th class="px-4 py-4">User</th>
                            <th class="px-4 py-4">Action & Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-4 py-6 text-xs text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y, h:i A') }}
                                </td>
                                <td class="px-4 py-6 text-xs font-black text-gray-800">
                                    {{ $log->user_email }}
                                </td>
                                <td class="px-4 py-6">
                                    <span class="inline-block px-3 py-1 bg-[#10b981] text-white text-[9px] font-black rounded uppercase tracking-wider mb-2 cursor-pointer hover:brightness-110 transition-all">
                                        {{ $log->action }}
                                    </span>
                                    <p class="text-xs text-gray-600 font-medium leading-relaxed">
                                        {{ $log->details }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-20 text-center text-gray-400 italic font-medium">No activity matching your regex search.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
