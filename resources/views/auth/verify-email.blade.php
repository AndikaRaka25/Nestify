<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 text-justify">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, Anda perlu memverifikasi alamat email Anda dengan mengklik tautan yang kami kirimkan ke email Anda.') }}
            {{ __('Silahkan check email bagian "spam" jika tidak menemukan link verifikasinya.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('Kirim Ulang Link Verifikasi') }}
                    </x-button>
                </div>
            </form>

            <div class="mt-2 flex items-center justify-between">
                <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Kembali ke halaman login') }}
                </a>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
