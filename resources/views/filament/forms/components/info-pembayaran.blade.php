    <div class="mb-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            Informasi Pembayaran
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Silakan lakukan transfer ke salah satu rekening di bawah ini.
        </p>
        
        @if (!empty($infoPembayaran))
            <div class="space-y-3">
                @foreach ($infoPembayaran as $rekening)
                    <div class="p-3 border rounded-md dark:border-gray-500">
                        <p class="font-bold text-gray-800 dark:text-gray-200">{{ $rekening['nama_bank'] ?? 'N/A' }}</p>
                        <p class="text-gray-600 dark:text-gray-300">Nomor: <strong>{{ $rekening['nomor_rekening'] ?? 'N/A' }}</strong></p>
                        <p class="text-gray-600 dark:text-gray-300">a/n: {{ $rekening['nama_pemilik_rekening'] ?? 'N/A' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 dark:text-gray-400">
                Informasi pembayaran belum diatur oleh pemilik.
            </p>
        @endif
    </div>
    