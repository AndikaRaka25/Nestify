{{-- Menggunakan struktur dan kelas dari landing_page.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  {{-- Menggunakan Bootstrap untuk responsivitas --}}
  <div class="container-fluid px-5">
    {{-- Logo/Brand dari landing_page.blade.php --}}
    <a class="navbar-brand" href="{{ route('landing_page') }}"> {{-- Atau sesuaikan dengan route ke landing page Anda --}}
        <img src="<?= asset('storage/landing_page/logo_nestify.png') ?>" alt="Nestify Logo"  style="height: 40px; margin-right: 5px;">
        Nestify
    </a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    {{-- Gunakan ms-auto untuk mendorong item ke kanan di layar besar --}}
    {{-- Gunakan text-center di layar kecil (<lg) untuk link navigasi jika diinginkan --}}
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center"> 
        {{-- Link Navigasi --}}
        <li class="nav-item">
            {{-- Hapus margin inline, gunakan kelas Bootstrap me-lg-3 untuk margin kanan di layar besar --}}
            <a class="nav-link py-2 py-lg-0 me-lg-3" href="#fitur">Fitur</a> 
        </li>
        <li class="nav-item">
            <a class="nav-link py-2 py-lg-0 me-lg-3" href="#testimoni">Testimoni</a>
        </li>
        <li class="nav-item">
            <a class="nav-link py-2 py-lg-0 me-lg-3" href="#faq">FAQ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link py-2 py-lg-0 me-lg-3" href="#kontak">Kontak</a>
        </li>
        
        {{-- Pemisah visual hanya untuk mobile --}}
        <li class="nav-item d-lg-none"><hr class="my-2"></li> 

        {{-- Tombol Daftar dan Masuk (Styling disesuaikan untuk mobile) --}}
        <li class="nav-item mt-2 mt-lg-0 me-lg-2 d-grid d-lg-inline-block"> 
            {{-- d-grid di mobile, inline di large --}}
            {{-- Hapus btn-lg dan px-5 untuk mobile, tambahkan py-2 --}}
            <a class="btn btn-primary text-white w-100 w-lg-auto py-2 px-lg-4" href="{{ route('register') }}">Daftar</a> 
            {{-- w-100 di mobile, w-lg-auto di large --}}
        </li>
        <li class="nav-item mt-2 mt-lg-0 d-grid d-lg-inline-block"> 
             {{-- d-grid di mobile, inline di large --}}
             {{-- Hapus btn-lg dan px-5 untuk mobile, tambahkan py-2 --}}
            <a class="btn btn-success text-white w-100 w-lg-auto py-2 px-lg-4" href="{{ route('login') }}">Masuk</a>
             {{-- w-100 di mobile, w-lg-auto di large --}}
        </li>
    </ul>
</div>
  </div>
</nav>