<?php
// app/Providers/Filament/AdminPanelProvider.php
namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard; // <-- Pastikan ini yang di-import
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
// Hapus atau komentari widget bawaan jika tidak ingin ditampilkan lagi
// use Filament\Widgets; // <-- Komentari atau hapus jika tidak menggunakan AccountWidget/FilamentInfoWidget
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Impor widget kustom Anda
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\LatestOrdersChart;
use App\Filament\Widgets\RecentUsersTable;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber, // Contoh warna
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class, // Pastikan halaman Dashboard standar terdaftar
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets') // Ini akan otomatis mendeteksi widget, TAPI untuk dashboard utama, lebih baik eksplisit
            ->widgets([ // Daftarkan widget Anda di sini untuk muncul di dashboard utama
                // Komentari atau hapus widget default jika tidak diinginkan
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,

                // Widget Kustom Anda
                StatsOverview::class,
                LatestOrdersChart::class,
                RecentUsersTable::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}