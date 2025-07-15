<?php

    namespace App\Providers;
    
    use Illuminate\Support\ServiceProvider;
    use App\Models\Tagihan; // <-- 1. Import model Tagihan
    use App\Observers\TagihanObserver; // <-- 2. Import Observer Anda
    
    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            //
        }
    
        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            // ✅ --- 3. DAFTARKAN OBSERVER DI SINI --- ✅
            Tagihan::observe(TagihanObserver::class);
        }
    }
    