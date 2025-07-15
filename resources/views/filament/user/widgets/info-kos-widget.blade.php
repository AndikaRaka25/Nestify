<x-filament-widgets::widget>
    <x-filament::section>
        @if ($penghuni)
            <div class="flex items-start gap-6">
                {{-- Foto Properti --}}
                <div class="w-1/3">
                    @if(!empty($penghuni->properti->foto) && isset($penghuni->properti->foto[0]))
                        <img src="{{ asset('storage/' . $penghuni->properti->foto[0]) }}" alt="Foto {{ $penghuni->properti->nama_properti }}" class="rounded-lg object-cover h-48 w-full">
                    @else
                        <div class="h-48 w-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg">
                            <span class="text-gray-500">Tidak ada foto</span>
                        </div>
                    @endif
                </div>

                {{-- Detail Kos dan Penghuni --}}
                <div class="w-2/3 space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Anda saat ini terdaftar di:</p>
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $penghuni->properti->nama_properti }}</h2>
                        <p class="text-gray-600 dark:text-gray-300">{!! $penghuni->properti->alamat_properti !!}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">Kamar Anda</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $penghuni->kamar->nama_kamar ?? '-' }} ({{ $penghuni->kamar->tipe_kamar ?? '-' }})</p>
                        </div>
                         <div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">Mulai Sewa</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($penghuni->mulai_sewa)->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                         <div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">Pemilik Kos</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $penghuni->properti->user->name ?? '-' }}</p>
                        </div>
                         <div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">Kontak Pemilik</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $penghuni->properti->user->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Tampilan jika pengguna belum menjadi penghuni aktif --}}
            <div class="text-center py-8">
                 <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Anda Belum Terdaftar di Kos Manapun</h3>
                 <p class="mt-1 text-sm text-gray-500">Silakan cari dan daftar kos untuk mulai menggunakan semua fitur.</p>
                 <div class="mt-6">
                    <x-filament::button
                        tag="a"
                        href="{{ \App\Filament\User\Resources\KosSayaResource::getUrl('browse-properti') }}"
                        icon="heroicon-o-magnifying-glass"
                    >
                        Cari Kos Sekarang
                    </x-filament::button>
                 </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
