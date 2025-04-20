<x-filament-widgets::widget>
    <div class="mb-4 flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row">
        @foreach($tabs as $tabId => $tab)
            <button
                wire:click="changeTab('{{ $tabId }}')"
                @class([
                    'px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center gap-x-2 transition duration-150 ease-in-out',
                    // Gaya untuk Tab Aktif
                    'bg-primary-600 text-white shadow-sm' => $activeTab === $tabId,
                    // Gaya untuk Tab Tidak Aktif (dibuat lebih kontras)
                    'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500' => $activeTab !== $tabId,
                 ])
            >
                {{ $tab['label'] }}
                <span @class([
                    'ml-4 px-2 py-0.5 text-xs rounded-full font-semibold',
                    // Gaya Badge Aktif
                    'bg-white text-primary-600' => $activeTab === $tabId,
                    // Gaya Badge Tidak Aktif
                    'bg-gray-200 text-gray-600' => $activeTab !== $tabId,
                 ])>
                    {{ $tab['badge'] }}
                </span>
            </button>
        @endforeach
    </div>

    <x-filament::section>
        <div @class([
            'filament-stats grid gap-4 lg:gap-8',
            'md:grid-cols-3' => count($stats) === 3 || count($stats) === 6,
            'md:grid-cols-1' => count($stats) === 1,
            'md:grid-cols-2' => count($stats) === 2 || count($stats) === 4,
            'md:grid-cols-4' => count($stats) === 4 || count($stats) === 8,
            'md:grid-cols-2 xl:grid-cols-4' => count($stats) > 4 && count($stats) < 8,
        ])>
            @foreach ($stats as $stat)
                {{ $stat }}
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>