<?php
// app/Filament/Widgets/StatsOverview.php
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Transaction; // Asumsi Anda punya model Transaction
use App\Models\Collector; // Asumsi Anda punya model Collector

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Urutan tampil di dashboard

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Semua pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Contoh data chart kecil

            Stat::make('Total Collectors', Collector::count())
                ->description('Jumlah kolektor aktif')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('Pending Transactions', Transaction::where('status', 'pending')->count())
                ->description('Transaksi menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Completed Transactions', Transaction::where('status', 'completed')->count())
                ->description('Transaksi selesai bulan ini') // Anda bisa filter berdasarkan bulan
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}