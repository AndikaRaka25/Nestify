<?php

    namespace App\Providers;
    
    use Illuminate\Support\ServiceProvider;
    use App\Models\Tagihan; 
    use App\Observers\TagihanObserver; 
    use App\Models\Penghuni;
use App\Observers\PenghuniObserver;
use App\Observers\TagihanNotifObserver; 
use Illuminate\Container\Attributes\Tag;
use App\Observers\TagihanKonfirmasiObserver;
use App\Observers\TagihanPenghuniObserver;
use App\Observers\KelolaKomplainObserver;
use App\Models\KelolaKomplain;

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
            
            Tagihan::observe(TagihanObserver::class);
            Penghuni::observe(PenghuniObserver::class);
            Tagihan::observe(TagihanNotifObserver::class);
            Tagihan::observe(TagihanKonfirmasiObserver::class);
            Tagihan::observe(TagihanPenghuniObserver::class);
            KelolaKomplain::observe(KelolaKomplainObserver::class);
        }
    }
    