{{-- /resources/views/layouts/partial/header.blade.php --}}
{{-- Tambahkan kelas sticky, top-0, dan z-index (misal: z-30) --}}
<nav class="bg-slate-800 w-full shadow-md sticky top-0 z-30" x-data="{ open: false }">
    <div class="container mx-auto px-6 h-16 flex items-center justify-between">
        {{-- Bagian Kiri: Logo --}}
        <div class="flex-shrink-0">
            <a class="flex items-center text-white text-xl font-bold" href="{{ route('landing_page') }}">
                <img src="<?= asset('storage/landing_page/logo_nestify.png') ?>" alt="Nestify Logo" class="h-8 w-auto mr-2">
                <span>Nestify</span>
            </a>
        </div>

        {{-- Bagian Kanan: Nav Links & Buttons (Desktop) --}}
        <div class="hidden md:flex items-center space-x-8">
            {{-- Nav Links --}}
            <div class="flex items-center space-x-5">
                <a href="{{ route('landing_page') }}#fitur" class="text-sm text-gray-300 hover:text-white transition duration-150 ease-in-out">Fitur</a>
                <a href="{{ route('landing_page') }}#testimoni" class="text-sm text-gray-300 hover:text-white transition duration-150 ease-in-out">Testimoni</a>
                <a href="{{ route('landing_page') }}#faq" class="text-sm text-gray-300 hover:text-white transition duration-150 ease-in-out">FAQ</a>
                <a href="{{ route('landing_page') }}#kontak" class="text-sm text-gray-300 hover:text-white transition duration-150 ease-in-out">Kontak</a>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center space-x-3">
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-7 rounded-lg transition duration-150 ease-in-out">
                    Daftar
                </a>
                <a href="{{ route('login') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 px-7 rounded-lg transition duration-150 ease-in-out">
                    Masuk
                </a>
            </div>
        </div>

        {{-- Tombol Mobile Menu Toggler --}}
        <div class="md:hidden flex items-center">
            <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" :aria-expanded="open.toString()">
                <span class="sr-only">Open main menu</span>
                <svg x-show="!open" class="h-6 w-6 block" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                <svg x-show="open" x-cloak class="h-6 w-6 block" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Dropdown Mobile Menu --}}
    <div class="md:hidden" id="mobile-menu" x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="#fitur" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Fitur</a>
            <a href="#testimoni" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Testimoni</a>
            <a href="#faq" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">FAQ</a>
            <a href="#kontak" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Kontak</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="px-5 space-y-3">
                <a href="{{ route('register') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">Daftar</a>
                <a href="{{ route('login') }}" class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">Masuk</a>
            </div>
        </div>
    </div>
</nav>