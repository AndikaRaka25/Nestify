<?php

namespace App\Filament\Widgets;

use App\Models\Properti;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class PropertiOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';
    
    protected int $limit = 10;
    
    // Properti untuk menyimpan filter aktif
    public ?string $activeTab = 'semua';
    
    public function mount()
    {
        $this->activeTab = Request::input('jenis', 'semua');
    }
    
    // Render fungsi kustom yang menyertakan tab
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.widgets.properti-overview', [
            'stats' => $this->getStats(),
            'tabs' => $this->getTabs(),
            'activeTab' => $this->activeTab,
        ]);
    }
    
    // Definisi tab
    protected function getTabs(): array
    {
        return [
            'semua' => [
                'label' => 'Semua Properti',
                'badge' => Properti::count(),
            ],
            'putra' => [
                'label' => 'Properti Putra',
                'badge' => Properti::where('jenis', 'putra')->count(),
            ],
            'putri' => [
                'label' => 'Properti Putri',
                'badge' => Properti::where('jenis', 'putri')->count(),
            ],
            'campur' => [
                'label' => 'Properti Campur',
                'badge' => Properti::where('jenis', 'campur')->count(),
            ]
        ];
    }
    
    // Fungsi untuk mengganti tab
    public function changeTab(string $tab)
    {
        $this->activeTab = $tab;
    }
    
    protected function getStats(): array
    {
        // Buat query untuk properti berdasarkan tab aktif
        $query = Properti::query();
        
        if ($this->activeTab !== 'semua') {
            $query->where('jenis', $this->activeTab);
        }
        
        // Dapatkan properti dengan urutan terbaru
        $properties = $query->latest()->limit($this->limit)->get();
        
        // Buat array stats
        $stats = [];
        
        // Tambahkan card statistik untuk setiap propert
        foreach ($properties as $property) {
            // --- AWAL MODIFIKASI TAMPILAN FOTO ---
            $fotos = $property->foto ?? [];
            
            $fotoHtml = '';
            
            if (!empty($fotos) && is_array($fotos)) {
                $uniqueId = 'slider-' . $property->id . '-' . uniqid();
            
                // Container dengan Alpine.js state
                $fotoHtml .= '<div x-data="{ activeSlide: 0, slides: ' . count($fotos) . ' }" id="' . $uniqueId . '" class="relative w-full overflow-hidden rounded-lg bg-gray-100" style="height: 200px;">'; // Tambah bg-gray-100 untuk debug
            
                // Slides (Div untuk setiap gambar)
                foreach ($fotos as $index => $fotoPath) {
                    if (empty($fotoPath) || !is_string($fotoPath)) continue;
                    try {
                        $imageUrl = Storage::disk('public')->url($fotoPath);
                    } catch (\Exception $e) {
                        $imageUrl = ''; // Kosongkan jika error
                    }
            
                    // Pastikan URL tidak kosong sebelum menampilkan img
                    if (!empty($imageUrl)) {
                         $fotoHtml .= '<div x-show="activeSlide === ' . $index . '" class="absolute inset-0 duration-300 ease-in-out" x-transition>';
                         $fotoHtml .= '<img src="' . e($imageUrl) . '" class="object-cover w-full h-full" alt="Foto ' . e($property->nama_properti) . ' ' . ($index + 1) . '" loading="lazy">'; // Tambah loading lazy
                         $fotoHtml .= '</div>';
                    }
                }
            
                // Panah Navigasi (Muncul jika > 1 foto)
                if (count($fotos) > 1) {
                    // Panah Kiri
                    $fotoHtml .= '<button type="button" @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none z-10">'; // p-2, w-4 h-4
                    $fotoHtml .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
                    $fotoHtml .= '</button>';
   
                    
                }
            
                
            
                $fotoHtml .= '</div>'; // Tutup container slider
            
            } else {
                // Fallback jika tidak ada foto
                try {
                     $noImageUrl = Storage::disk('public')->url('no-image.png'); // Asumsi no-image.png ada di root public storage
                } catch (\Exception $e) { $noImageUrl = ''; }
            
                if (!empty($noImageUrl)) {
                     $fotoHtml = '<div class="relative w-full overflow-hidden rounded-lg bg-gray-200" style="height: 200px;">
                                    <img src="' . e($noImageUrl) . '" class="object-contain w-full h-full" alt="Tidak ada foto properti">
                                 </div>';
                } else {
                    $fotoHtml = '<div class="relative w-full overflow-hidden rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center" style="height: 200px;">Tidak ada foto</div>';
                }
            
            }
            // --- AKHIR MODIFIKASI TAMPILAN FOTO ---

            // Gabungkan HTML slider ke dalam deskripsi Stat
            $stats[] = Stat::make($property->id, $property->nama_properti)
                ->description(new HtmlString(
                    '<div class="flex flex-col gap-2">
                        <div class="flex items-center">
                            <span class="text-lg text-black">Properti ' . ucfirst($property->jenis) . '</span>
                        </div>
                        <div class="mt-2">' . $fotoHtml . '</div> 
                        <div class="mt-2">
                            <span class="text-lg text-black">Alamat: ' . strip_tags($property->alamat_properti) . '</span>
                        </div>
                        <div class="mt-2">
                            <a href="' . route('filament.admin.resources.propertis.view', $property) . '"
                               class="inline-flex items-center justify-center py-1 px-3 rounded-lg bg-primary-600 text-white text-sm hover:bg-primary-500 focus:outline-none transition">
                               Lihat Detail
                            </a>
                        </div>
                    </div>'
                ))
                ->extraAttributes([
                    'class' => 'border rounded-xl shadow-sm p-4',
                    'style' => 'height: auto; min-height: 400px;', // Sesuaikan min-height jika perlu
                ]);
        }

        if (empty($stats)) {
            $stats[] = Stat::make('Tidak ada properti', '0')
                ->description('Tidak ada properti ' . ($this->activeTab !== 'semua' ? $this->activeTab : '') . ' yang ditemukan.');
        }

        return $stats;
    }
}