<x-filament-panels::page>
    <div class="space-y-8">
        {{-- ... (Bagian Header dan FAQ tidak berubah) ... --}}
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                Pusat Bantuan & Dukungan Nestify
            </h1>
            <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-400">
                Kami siap membantu! Temukan jawaban atas pertanyaan Anda di bawah ini.
            </p>
        </div>
        
        {{-- ... (Seluruh bagian FAQ Anda) ... --}}


        {{-- Bagian Kontak & Kebijakan --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <x-filament::section>
                <x-slot name="heading">
                    Butuh Bantuan Lebih Lanjut?
                </x-slot>
                <p class="text-gray-600 dark:text-gray-400">
                    Jika Anda tidak menemukan jawaban yang Anda cari, jangan ragu untuk menghubungi tim dukungan kami.
                </p>
                <div class="mt-4">
                    <x-filament::link href="mailto:support@nestify.app" icon="heroicon-m-envelope">
                        andikaraka25@gmail.com
                    </x-filament::link>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    Legal & Kebijakan
                </x-slot>
                
                {{-- ✨ INILAH PERBAIKANNYA ✨ --}}
                {{-- Kita membaca dan merender file Markdown secara langsung --}}
                <div class="prose dark:prose-invert max-w-none mt-4">
                    {!! Str::markdown(file_get_contents(resource_path('markdown/policy.md'))) !!}
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
