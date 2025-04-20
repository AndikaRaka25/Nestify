<x-filament::page>
    <div class="mb-4">
        <!-- Tabs -->
        <div class="flex space-x-4">
            @foreach (['Belum Bayar', 'Butuh Konfirmasi', 'Lunas'] as $tab)
                <button
                    wire:click="setActiveTab('{{ $tab }}')"
                    class="px-4 py-2 rounded 
                        {{ $activeTab === $tab ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                    {{ $tab }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Tabel Tagihan -->
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Nama Penyewa</th>
                <th class="p-2 border">Tipe Kamar</th>
                <th class="p-2 border">Properti</th>
                <th class="p-2 border">Periode Pembayaran</th>
                <th class="p-2 border">Status Pembayaran</th>
                <th class="p-2 border">Jatuh Tempo</th>
                <th class="p-2 border">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tagihans as $tagihan)
                <tr>
                    <td class="p-2 border">{{ $tagihan->penyewa->name }}</td>
                    <td class="p-2 border">{{ $tagihan->tipeKamar->name }}</td>
                    <td class="p-2 border">{{ $tagihan->properti->name }}</td>
                    <td class="p-2 border">{{ $tagihan->periode_pembayaran }}</td>
                    <td class="p-2 border">{{ $tagihan->status }}</td>
                    <td class="p-2 border">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}</td>
                    <td class="p-2 border">
                        {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-2 border text-center">Tidak ada data tagihan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-filament::page>
