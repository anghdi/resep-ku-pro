<div class="min-h-screen flex items-center justify-center bg-orange-50 p-6 md:p-10 font-sans relative">

    <div class="absolute top-6 right-6 z-30">
        <button wire:click="setLocale('en')"
            class="px-3 py-1 md:px-4 md:py-1.5 rounded-full text-[10px] md:text-xs font-extrabold transition-all {{ app()->getLocale() == 'en' ? 'bg-[#f97316] text-white shadow-md' : 'text-gray-500 hover:text-orange-600' }}">EN</button>
        <button wire:click="setLocale('id')"
            class="px-3 py-1 md:px-4 md:py-1.5 rounded-full text-[10px] md:text-xs font-extrabold transition-all {{ app()->getLocale() == 'id' ? 'bg-[#f97316] text-white shadow-md' : 'text-gray-500 hover:text-orange-600' }}">ID</button>
    </div>

    <div
        class="max-w-5xl w-full bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border-[6px] border-white ring-4 ring-orange-100/50">

        <div class="hidden md:flex md:w-[45%] bg-cover bg-center relative"
            style="background-image: url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=2070&auto=format&fit=crop');">
            <div
                class="absolute inset-0 bg-gradient-to-t from-[#f97316]/95 via-[#f97316]/70 to-orange-900/40 opacity-95">
            </div>
            <div class="relative z-10 p-10 flex flex-col justify-end h-full text-white text-center">
                <h2 class="text-3xl font-black italic mb-3 leading-tight">{{ __('Start Your Culinary Journey') }}</h2>
                <p class="text-sm font-medium opacity-90 tracking-wide">
                    {{ __('Efficiency is the secret ingredient of every professional kitchen.') }}</p>
            </div>
        </div>

        <div class="w-full md:w-[55%] p-8 md:p-12 flex flex-col items-center bg-white">

            <div class="text-center mb-6">
                <div class="flex items-center justify-center gap-2">
                    <h1 class="text-3xl font-black text-[#d4af37] tracking-tight">ResepKuPro</h1>
                </div>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-1 italic">Professional
                    Kitchen System</p>
            </div>

            <h3
                class="text-lg font-extrabold text-gray-800 mb-6 uppercase tracking-[0.2em] border-b-2 border-orange-100 pb-1">
                {{ __('Create Account') }}</h3>

            @if (session()->has('error'))
                <div
                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-xl shadow-sm italic text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="register" x-data="{ role: '' }" class="w-full space-y-3.5">
                <input type="text" wire:model="full_name"
                    class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#f97316] outline-none transition-all text-sm font-medium"
                    placeholder="{{ __('Full Name') }}">
                @error('full_name')
                    <span class="text-red-500 text-xs font-bold italic ml-2">{{ $message }}</span>
                @enderror

                <select x-model="role" wire:model.live="role"
                    class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#f97316] outline-none transition-all text-sm font-medium text-gray-500">
                    <option value="">{{ __('Select Role') }}</option>
                    <option value="owner">{{ __('Restaurant Owner / Head Chef') }}</option>
                    <option value="manager">{{ __('Kitchen Manager') }}</option>
                    <option value="staff">{{ __('Kitchen Staff') }}</option>
                </select>

                <div x-show="role === 'staff' || role === 'manager'" x-transition.duration.300ms x-cloak
                    class="space-y-1">
                    <input type="text" wire:model="team_org_id"
                        class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#f97316] outline-none transition-all text-sm font-medium"
                        placeholder="{{ __('Enter Team Org ID') }}">
                    <p class="text-[10px] text-gray-400 ml-1">
                        *{{ __('Ask your Owner for the Org ID.') }}
                    </p>
                    @error('team_org_id')
                        <span class="text-red-500 text-xs italic ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <div x-show="role === 'owner'" x-transition.duration.300ms x-cloak class="space-y-1">
                    <input type="text" wire:model="brand_name"
                        class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#f97316] outline-none transition-all text-sm font-medium"
                        placeholder="{{ __('Brand Name (e.g. HOLMES)') }}">
                    @error('brand_name')
                        <span class="text-red-500 text-xs italic ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <input type="email" wire:model="email"
                    class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#f97316] outline-none transition-all text-sm font-medium"
                    placeholder="{{ __('Email Address') }}">

                <div class="relative">
                    <input type="password" wire:model="password"
                        class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#f97316] outline-none transition-all text-sm font-medium"
                        placeholder="{{ __('Password') }}">
                    <x-lucide-eye-off class="absolute right-4 top-3 w-5 h-5 text-gray-300 cursor-pointer" />
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="register"
                    class="w-full relative bg-[#1a1a1a] hover:bg-black text-white font-black py-4 rounded-xl shadow-lg transition-all uppercase tracking-[0.2em] mt-2 text-sm transform active:scale-[0.97] hover:cursor-pointer disabled:opacity-80 disabled:cursor-not-allowed overflow-hidden">

                    <div wire:loading.remove wire:target="register" class="flex items-center justify-center gap-2">
                        <span>{{ __('Sign Up') }}</span>
                    </div>

                    <div wire:loading wire:target="register" class="flex items-center justify-center gap-3">
                        <svg class="animate-spin h-5 w-5 text-orange-500 leading-none"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="inline-block align-middle">{{ __('Processing...') }}</span>
                    </div>
                </button>
            </form>

            <div class="flex items-center w-full my-5 text-gray-100">
                <div class="flex-grow border-t-2"></div>
                <span class="px-3 text-[9px] font-black uppercase tracking-widest text-gray-300">OR</span>
                <div class="flex-grow border-t-2"></div>
            </div>

            <a href="{{ route('google.redirect') }}"
                class="w-full flex items-center justify-center gap-3 border-2 border-gray-100 py-3 rounded-xl hover:bg-gray-50 transition-all font-bold text-gray-700 text-sm group shadow-sm">
                <svg class="w-5 h-5 filter grayscale group-hover:grayscale-0 transition-all" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.245-.727-.382-1.5-.382-2.318 0-.818.137-1.591.382-2.318L1.33 6.16C.482 7.873 0 9.782 0 11.627c0 1.845.482 3.754 1.33 5.467l3.706-3.149z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                <span>{{ __('Continue with Google') }}</span>
            </a>

            <p class="mt-6 text-xs font-bold text-gray-400">
                {{ __('Already have an account?') }} <a href="{{ route('login') }}"
                    class="text-[#f97316] hover:underline">{{ __('Login') }}</a>
            </p>
        </div>
    </div>
</div>
