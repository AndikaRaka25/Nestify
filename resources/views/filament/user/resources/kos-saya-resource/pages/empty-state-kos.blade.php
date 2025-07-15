<div class="flex flex-col items-center justify-center p-8 text-center bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-sm">
    <div class="mb-4">
        <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-3-1m-3 1l-3 1m-3-1l3-1.091m15.75 0l-3 1m0 0l-3 1m3-1l-3-1M9 7.5l3 1.091M15 7.5l3 1.091M12 21v-8.25" />
        </svg>
    </div>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Anda Belum Terdaftar di Kos Manapun</h2>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Sepertinya Anda belum menyewa kos. Mari cari tempat tinggal yang nyaman untuk Anda!
    </p>
    <div class="mt-6">
        <a href="{{ KosSayaResource::getUrl('browse-properti') }}" 
           class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-200 disabled:opacity-25 transition">
            Cari & Daftar Kos Sekarang
        </a>
    </div>
</div>