<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="Kelola Kos Lebih Mudah dengan Nestify!" />
    <meta name="author" content="Nestify Team" />
    <title>Nestify - Manajemen Kos Modern</title>
    <link rel="icon" type="image/x-icon" href="<?= asset('storage/landing_page/nestify.png') ?>" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" {{-- Versi bootstrap icon sedikit diupdate --}}
      rel="stylesheet"
    />
    <link href="<?= asset('css/landingpage_styles.css') ?>" rel="stylesheet" />
    {{-- Tambahan style inline untuk overlay header --}}
    <style>
        .header-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.55); /* Overlay sedikit lebih gelap */
            z-index: 1;
        }
        .header-content {
            position: relative;
            z-index: 2;
        }
        /* Style tambahan untuk card fitur & faq agar lebih menarik */
        .feature-card, .faq-card-content {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }
        .faq-item {
            margin-bottom: 1.5rem;
        }
        .faq-question-card {
             border-bottom-left-radius: 0 !important;
             border-bottom-right-radius: 0 !important;
             border-bottom: 0;
        }
        .faq-answer-card {
             border-top-left-radius: 0 !important;
             border-top-right-radius: 0 !important;
        }
        .icon-feature {
             width: 60px;
             height: 60px;
             font-size: 1.75rem; /* Ukuran ikon diperbesar */
        }
    </style>
  </head>
  <body>

  {{-- Include header Anda (pastikan path-nya benar) --}}
  @include('layouts.partial.header') 
    
    {{-- Header dengan Overlay --}}
    <header class="position-relative py-5" style="background-image: url('storage/landing_page/foto_nestify_landingpage.JPG'); background-size: cover; background-position: center; color: white;">
      <div class="header-overlay"></div> {{-- Lapisan Overlay --}}
      <div class="container px-5 header-content"> {{-- Konten di atas overlay --}}
        <div class="row gx-5 justify-content-center">
          <div class="col-lg-7 col-xl-6"> {{-- Kolom sedikit diperlebar --}}
            <div class="text-center my-5">
              <h1 class="display-5 fw-bolder mb-2">
                Kelola Kos Lebih Mudah dengan Nestify!             
              </h1>
              <p class="lead text-white-75 mb-4"> {{-- Warna teks diubah agar kontras --}}
                Nestify adalah solusi manajemen kos modern yang dirancang untuk memudahkan
                Anda mengelola properti kos dengan lebih efisien. Dari pengelolaan penghuni hingga keuangan, 
                semua ada dalam satu platform yang mudah digunakan.
              </p>
              <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a class="btn btn-primary btn-lg px-4 me-sm-3 fw-bold" href="#fitur">Intip Fitur Nestify</a>
                {{-- Mungkin tambahkan tombol sekunder? Misal: --}}
                {{-- <a class="btn btn-outline-light btn-lg px-4" href="{{ route('register') }}">Daftar Sekarang</a> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section class="py-5 border-bottom bg-white" id="fitur"> {{-- Background putih --}}
      <div class="container px-5 my-5"> {{-- Margin y ditambah --}}
        <div class="text-center mb-5">
            <h2 class="fw-bolder">Fitur Utama Nestify! </h2>
            <p class="lead mb-4 text-muted"> {{-- mb dikurangi sedikit, warna teks disesuaikan --}}
                Nestify hadir dengan berbagai fitur yang mempermudah pemilik kos dalam mengelola propertinya secara efisien. Berikut fitur-fitur utama yang dapat Anda manfaatkan:
            </p>
        </div>
        <div class="row gx-5">
          <div class="col-lg-4 mb-5 mb-lg-0">
            <div class="card h-100 border-0 shadow-sm feature-card"> {{-- Menggunakan Card --}}
              <div class="card-body text-center p-4 p-lg-5">
                <div class="d-flex justify-content-center mb-4"> {{-- Margin bottom ditambah --}}
                  <div class="bg-primary bg-gradient text-white rounded-3 d-flex align-items-center justify-content-center icon-feature"> {{-- Kelas baru untuk styling icon --}}
                    <i class="bi bi-journal-text"></i> {{-- Icon diganti agar lebih relevan --}}
                  </div>
                </div>
                <h2 class="h4 fw-bolder">Pencatatan Daftar Kos</h2>
                <p class="mb-0 text-muted"> {{-- Warna text-muted --}}
                  Pencatatan datamu saat mendaftar akan jauh jadi lebih mudah!
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-5 mb-lg-0">
             <div class="card h-100 border-0 shadow-sm feature-card"> {{-- Menggunakan Card --}}
              <div class="card-body text-center p-4 p-lg-5">
                <div class="d-flex justify-content-center mb-4">
                  <div class="bg-primary bg-gradient text-white rounded-3 d-flex align-items-center justify-content-center icon-feature">
                    <i class="bi bi-receipt-cutoff"></i> {{-- Icon diganti --}}
                  </div>
                </div>
                <h2 class="h4 fw-bolder">Kelola Tagihan Pembayaran</h2>
                <p class="mb-0 text-muted">
                  Lacak dan atur pembayaran kos tanpa ribet, langsung dari aplikasi.
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
             <div class="card h-100 border-0 shadow-sm feature-card"> {{-- Menggunakan Card --}}
               <div class="card-body text-center p-4 p-lg-5">
                <div class="d-flex justify-content-center mb-4">
                  <div class="bg-primary bg-gradient text-white rounded-3 d-flex align-items-center justify-content-center icon-feature">
                    <i class="bi bi-chat-left-dots"></i> {{-- Icon diganti --}}
                  </div>
                </div>
                <h2 class="h4 fw-bolder">Fitur Komplain</h2>
                <p class="mb-0 text-muted">
                  Kalian bisa mengajukan keluhan langsung ke pemilik kos dengan cepat dan mudah!
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 border-bottom bg-light" id="testimoni"> {{-- Background light --}}
      <div class="container px-5 my-5">
        <div class="text-center mb-5">
          <h2 class="fw-bolder">Testimoni Pengguna</h2>
          <p class="lead mb-0 text-muted">Dengarkan apa kata mereka yang telah merasakan kemudahan Nestify</p>
        </div>
        <div class="row gx-5 justify-content-center">
          <div class="col-lg-6 col-xl-4 mb-4"> {{-- mb-4 ditambahkan --}}
            <div class="card shadow border-0 h-100">
              <div class="card-body p-4 p-lg-5"> {{-- Padding disesuaikan --}}
                 <img src="storage/landing_page/gambar_1.JPG" class="rounded-circle mb-3 mx-auto d-block" alt="Foto Pengguna 1" style="width: 80px; height: 80px; object-fit: cover;">
                 <h5 class="card-title text-center fw-bold mb-3">Pengelolaan Jadi Ringan!</h5>
                 <p class="card-text text-center text-muted small"> {{-- text small --}}
                    "Sebelum pakai Nestify, saya pusing catat pembayaran manual. Sekarang semua tagihan otomatis terjadwal dan bisa dipantau kapan saja. Hemat waktu banget!"
                 </p>
              </div>
              <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 text-center"> {{-- pb dikurangi --}}
                 <div class="small fw-bold text-primary">Budi Setiawan</div>
                 <div class="text-muted small">Pemilik Kos Bunga Indah</div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-xl-4 mb-4"> {{-- mb-4 ditambahkan --}}
            <div class="card shadow border-0 h-100">
              <div class="card-body p-4 p-lg-5">
                 <img src="storage/landing_page/gambar_2.JPG" class="rounded-circle mb-3 mx-auto d-block" alt="Foto Pengguna 2" style="width: 80px; height: 80px; object-fit: cover;">
                 <h5 class="card-title text-center fw-bold mb-3">Fitur Komplain Sangat Membantu</h5>
                 <p class="card-text text-center text-muted small">
                    "Fitur komplainnya keren! Penghuni bisa langsung lapor kalau ada masalah, dan saya bisa langsung tanggapi. Komunikasi jadi lebih lancar dan masalah cepat selesai."
                 </p>
              </div>
              <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 text-center">
                  <div class="small fw-bold text-primary">Citra Lestari</div>
                  <div class="text-muted small">Mahasiswi & Penghuni Kos</div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-xl-4 mb-4"> {{-- mb-4 ditambahkan --}}
            <div class="card shadow border-0 h-100">
              <div class="card-body p-4 p-lg-5">
                 <img src="storage/landing_page/gambar_3.JPG" class="rounded-circle mb-3 mx-auto d-block" alt="Foto Pengguna 3" style="width: 80px; height: 80px; object-fit: cover;">
                 <h5 class="card-title text-center fw-bold mb-3">Mudah Digunakan, Bahkan Untuk Pemula</h5>
                 <p class="card-text text-center text-muted small">
                    "Saya baru mulai bisnis kos, tadinya bingung soal manajemen. Nestify tampilannya simpel dan mudah dipahami. Data penghuni dan keuangan jadi rapi."
                 </p>
              </div>
               <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 text-center">
                  <div class="small fw-bold text-primary">Agus Santoso</div>
                  <div class="text-muted small">Pemilik Kos Baru</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 border-bottom bg-white" id="faq"> {{-- Background putih --}}
    <div class="container px-5 my-5">
        <div class="text-center mb-5">
            <h2 class="fw-bolder">FAQ</h2>
            <p class="lead mb-0 text-muted">Temukan jawaban cepat untuk pertanyaan seputar pengelolaan kos dengan Nestify!</p>
        </div>

        {{-- Container utama row untuk FAQ items --}}
        <div class="row gx-5 justify-content-center"> 
            {{-- Wrapper kolom diubah menjadi lg-12 agar kolom faq bisa memenuhi lebar --}}
            <div class="col-lg-12"> 
                {{-- Row untuk menampung item FAQ, tambahkan d-flex untuk potential equal height --}}
                <div class="row"> 
                    {{-- Item FAQ 1 --}}
                    {{-- Kolom diubah menjadi col-lg-4 dan ditambahkan h-100 --}}
                    <div class="col-lg-4 faq-item d-flex flex-column h-100 mb-4"> 
                        <div class="card faq-question-card bg-primary bg-gradient text-white border-0 shadow-sm">
                            <div class="card-body py-3 px-4">
                                <h2 class="text-center fw-bolder h6 my-0">Apakah Nestify bisa digunakan secara gratis?</h2>
                            </div>
                        </div>
                         {{-- Tambahkan flex-grow-1 agar card jawaban mengisi sisa tinggi --}}
                        <div class="card faq-answer-card border-0 shadow-sm faq-card-content flex-grow-1">
                            <div class="card-body p-4">
                                <p class="mb-0 text-muted small">
                                    Ya betul!, Nestify merupakan website manejemen kos gratis dengan fitur yang sudah disebutkan diatas.
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Item FAQ 2 --}}
                     {{-- Kolom diubah menjadi col-lg-4 dan ditambahkan h-100 --}}
                    <div class="col-lg-4 faq-item d-flex flex-column h-100 mb-4">
                        <div class="card faq-question-card bg-primary bg-gradient text-white border-0 shadow-sm">
                            <div class="card-body py-3 px-4">
                                <h2 class="text-center fw-bolder h6 my-0">Bagaimana cara mendaftarkan properti saya?</h2>
                            </div>
                        </div>
                         {{-- Tambahkan flex-grow-1 agar card jawaban mengisi sisa tinggi --}}
                        <div class="card faq-answer-card border-0 shadow-sm faq-card-content flex-grow-1">
                            <div class="card-body p-4">
                                <p class="mb-0 text-muted small">
                                    Anda hanya perlu membuat akun, kemudian tambahkan informasi properti seperti nama kos, jumlah kamar, harga sewa, dan fasilitas yang tersedia. Semua data akan tersimpan di sistem Nestify secara otomatis!
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Item FAQ 3 --}}
                    {{-- Kolom diubah menjadi col-lg-4 dan ditambahkan h-100 --}}
                    <div class="col-lg-4 faq-item d-flex flex-column h-100 mb-4">
                        <div class="card faq-question-card bg-primary bg-gradient text-white border-0 shadow-sm">
                            <div class="card-body py-3 px-4">
                                <h2 class="text-center fw-bolder h6 my-0">Apakah bisa digunakan di lebih dari satu perangkat?</h2>
                            </div>
                        </div>
                         {{-- Tambahkan flex-grow-1 agar card jawaban mengisi sisa tinggi --}}
                        <div class="card faq-answer-card border-0 shadow-sm faq-card-content flex-grow-1">
                            <div class="card-body p-4">
                                <p class="mb-0 text-muted small">
                                    Yups , Anda bisa mengakses Nestify di beberapa perangkat, baik smartphone maupun tablet, selama login menggunakan akun yang sama.
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Tambahkan item FAQ lain jika perlu dengan struktur col-lg-4 faq-item ... --}}
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="py-5 bg-light" id="kontak"> {{-- Background Light --}}
      <div class="container px-5 my-5">
        <div class="text-center mb-5">
          <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3 mx-auto d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="bi bi-envelope"></i></div>
          <h2 class="fw-bolder">Hubungi Kami</h2>
          <p class="lead mb-0 text-muted">Punya pertanyaan atau butuh bantuan? Jangan ragu kontak kami!</p>
        </div>
        <div class="row gx-5 justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="row text-center">
                    <div class="col-md-4 mb-5 mb-md-0"> {{-- mb-5 ditambahkan --}}
                        <div class="icon-circle bg-gradient-primary-to-secondary text-white mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(to right, #0d6efd, #6f42c1);"> {{-- Lingkaran ikon lebih besar & gradient --}}
                            <i class="bi bi-info-circle fs-3"></i> {{-- Icon lebih besar --}}
                        </div>
                        <h5 class="fw-bold">Tentang Nestify</h5>
                        <p class="text-muted small">Nestify hadir untuk merevolusi cara Anda mengelola properti kos. Misi kami adalah menyediakan platform yang intuitif, efisien, dan terjangkau bagi semua pemilik kos di Indonesia.</p>
                    </div>

                    <div class="col-md-4 mb-5 mb-md-0">
                         <div class="icon-circle bg-gradient-primary-to-secondary text-white mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(to right, #0d6efd, #6f42c1);">
                            <i class="bi bi-telephone fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Kontak Langsung</h5>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-telephone me-1"></i> +62 812-3456-7890 <br>
                            <span class="text-black-50">(Jam Kerja: 08.00 - 17.00 WIB)</span>
                        </p>
                         <p class="text-muted small">
                            <i class="bi bi-envelope me-1"></i>
                            <a href="mailto:support@nestify.co.id" class="text-muted text-decoration-none">support@nestify.co.id</a>
                        </p>
                    </div>

                    <div class="col-md-4">
                         <div class="icon-circle bg-gradient-primary-to-secondary text-white mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(to right, #0d6efd, #6f42c1);">
                            <i class="bi bi-share fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Ikuti Kami</h5>
                        <div class="d-flex justify-content-center fs-3 gap-4"> {{-- Ukuran icon lebih besar, gap ditambah --}}
                            <a class="text-primary" href="#!" aria-label="Instagram Nestify"><i class="bi bi-instagram"></i></a>
                            <a class="text-primary" href="#!" aria-label="Facebook Nestify"><i class="bi bi-facebook"></i></a>
                            <a class="text-success" href="https://wa.me/6281234567890" target="_blank" aria-label="WhatsApp Nestify"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>

    @include('layouts.partial.footer') {{-- Pastikan path footer benar --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= asset('js/scripts.js') ?>"></script>
    {{-- Hapus script ini jika tidak digunakan, karena bisa menyebabkan error jika form tidak ada --}}
    {{-- <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script> --}}
  </body>
</html>