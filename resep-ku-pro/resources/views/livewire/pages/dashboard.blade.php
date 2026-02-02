<div class="min-h-full w-full bg-[#f8fafc] flex items-center justify-center p-4 md:p-12 relative overflow-hidden">

    {{-- <div class="absolute top-0 left-0 w-full h-full opacity-40 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-orange-200 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[30%] h-[30%] bg-yellow-100 blur-[100px] rounded-full"></div>
    </div> --}}

    <div class="max-w-4xl w-full bg-white rounded-[3rem] p-8 md:p-16 shadow-2xl shadow-orange-900/5 text-center relative z-10 border-b-[12px] border-[#d4af37]">

        <h2 class="text-3xl md:text-5xl font-black text-gray-800 mb-8 tracking-tight leading-tight">
            Welcome,<br>
            <span class="text-[#f97316]">Chef {{ explode(' ', auth()->user()->full_name)[0] }}</span>
        </h2>

        <div class="text-gray-600 leading-relaxed font-medium text-sm md:text-base space-y-6 mb-12 px-2 md:px-6 text-justify md:text-center">
            <p>
                Welcome to <span class="font-bold text-gray-900">ResepKuPro</span>, your all-in-one professional culinary management ecosystem. Designed specifically for modern chefs and restaurateurs, this platform serves as the <span class="text-[#f97316] font-bold">central intelligence hub</span> for your kitchen operations. Our primary goal is to bridge the gap between creative culinary arts and rigorous financial precision.
            </p>

            <p>
                Through this dashboard, you can achieve <span class="font-bold text-gray-900 border-b-2 border-orange-200">total cost control</span> by monitoring real-time ingredient price fluctuations, ensuring that every plate served maintains your desired profit margin. Beyond financial tracking, ResepKuPro acts as a digital vault for your <span class="font-bold text-gray-900">intellectual property</span>, allowing you to standardize <span class="text-[#d4af37] font-bold">Standard Operating Procedures (SOP)</span> and recipes across multiple outlets.
            </p>

            <p class="hidden md:block">
                Whether you are managing a single boutique cafe or a nationwide culinary chain, our system empowers you to eliminate waste, optimize inventory, and maintain consistent quality. Transform your kitchen from a cost center into a <span class="font-bold text-gray-900 italic">high-performance, data-driven culinary business.</span>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
            <a href="#" class="bg-[#1a1a1a] hover:bg-black text-white p-6 rounded-3xl flex flex-col items-center justify-center gap-3 transition-all hover:-translate-y-1 shadow-xl shadow-black/10 group cursor-pointer">
                <x-lucide-utensils-crossed class="w-7 h-7 text-[#f97316] group-hover:scale-110 transition-transform" />
                <span class="font-black uppercase tracking-[0.2em] text-[10px]">{{ __('New Menu') }}</span>
            </a>

            <a href="#" class="bg-[#1a1a1a] hover:bg-black text-white p-6 rounded-3xl flex flex-col items-center justify-center gap-3 transition-all hover:-translate-y-1 shadow-xl shadow-black/10 group cursor-pointer">
                <x-lucide-badge-dollar-sign class="w-7 h-7 text-[#d4af37] group-hover:scale-110 transition-transform" />
                <span class="font-black uppercase tracking-[0.2em] text-[10px]">{{ __('Costing Analysis') }}</span>
            </a>
        </div>
    </div>
</div>
