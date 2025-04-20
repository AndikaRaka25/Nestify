<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Nestify</title> {{-- Anda bisa menggantinya dengan {{ config('app.name', 'Nestify') }} jika diinginkan --}}
    <link rel="icon" type="image/x-icon" href="<?= asset('storage/landing_page/nestify.png') ?>" />

    {{-- Hapus link ke landingpage_styles.css karena gaya sekarang ditangani oleh Tailwind via Vite --}}
    {{-- <link href="<?= asset('css/landingpage_styles.css') ?>" rel="stylesheet" /> --}}

    {{-- Bootstrap Icons (Hanya jika masih diperlukan oleh ikon di header/footer/slot versi Tailwind) --}}
    {{-- Jika tidak ada ikon Bootstrap yang dipakai, baris ini bisa dihapus --}}
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    {{-- Gaya utama aplikasi dari Vite (Tailwind CSS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Sisipkan gaya Livewire --}}
    @livewireStyles
</head>
<body class="bg-gray-100"> {{-- Menambahkan warna latar belakang dasar jika diperlukan --}}

    {{-- Include header (versi Tailwind) --}}
    @include('layouts.partial.header_tailwind')

    

    {{-- Konten utama --}}
    <main class="font-sans text-gray-900 antialiased">
        {{-- $slot biasanya tidak perlu dibungkus div tambahan di sini,
             karena halaman yang di-render di $slot (misal: login, register)
             biasanya sudah memiliki layoutnya sendiri (seperti x-authentication-card) --}}
        {{ $slot }}
    </main>

    {{-- Include footer (versi Tailwind) --}}
    @include('layouts.partial.footer_tailwind')

    {{-- Sisipkan script Vite --}}

    {{-- Sisipkan script Livewire --}}
    @livewireScripts

    {{-- Sisipkan script lain jika diperlukan, misal AlpineJS untuk menu mobile --}}
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
</body>
</html>