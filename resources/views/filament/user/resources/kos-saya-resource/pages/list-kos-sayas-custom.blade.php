<x-filament-panels::page>

    {{-- ✅ Logika sekarang menggunakan variabel $isAPenghuni yang sudah pasti ada --}}
    @if ($isAPenghuni)
    
        {{-- Jika adalah penghuni aktif, tampilkan tabel --}}
        {{ $this->table }}

    @else

        {{-- Jika BUKAN penghuni aktif, tampilkan pesan panduan --}}
        <div class="flex flex-col items-center justify-center p-8 text-center bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-sm">
            <div class="mb-4">
                <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Anda Belum Menempati Kos</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Sepertinya Anda belum terdaftar sebagai penyewa aktif. Silakan cari kos yang cocok untuk Anda!
            </p>
            <div class="mt-6">
                <a href="{{ \App\Filament\User\Resources\KosSayaResource::getUrl('browse-properti') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-200 disabled:opacity-25 transition">
                    Cari & Daftar Kos Sekarang
                </a>
            </div>
        </div>
        
    @endif

</x-filament-panels::page>
