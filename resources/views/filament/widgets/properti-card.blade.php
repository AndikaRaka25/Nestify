<div class="properti-card space-y-4 relative">
    <div class="absolute top-0 left-0 bg-gray-800 text-black px-2 py-1 rounded-br-md">
        #{{ $properti->id }}
    </div>
    
    <div class="badge {{ $properti->jenis == 'putra' ? 'bg-blue-500' : ($properti->jenis == 'putri' ? 'bg-green-500' : 'bg-yellow-500') }} text-black px-2 py-1 rounded absolute top-0 right-0">
        {{ ucfirst($properti->jenis) }}
    </div>
    
    <div class="w-full h-48 overflow-hidden rounded-lg">
        @if($properti->foto)
        <img src="{{ asset('storage/kos_foto/' . $properti->foto) }}" alt="{{ $properti->nama_properti }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400">Tidak ada foto</span>
            </div>
        @endif
    </div>
    
    <div class="alamat-section">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Alamat Properti:</h3>
        <div class="text-sm">{!! $properti->alamat_properti !!}</div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('filament.admin.resources.propertis.edit', $properti->id) }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-200 disabled:opacity-25 transition">
            Lihat Detail
        </a>
    </div>
</div>