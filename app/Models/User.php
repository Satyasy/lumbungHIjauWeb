<?php
// app/Models/User.php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Pastikan ini ada jika Anda menggunakan Sanctum

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role',
        'balance',
        'address',
        'email_verified_at', // Biasanya diisi otomatis oleh Laravel
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis hash jika menggunakan Laravel 10+
        'balance' => 'decimal:2',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // Sesuaikan dengan kebutuhan, misalnya hanya admin yang bisa akses
        // return $this->role === 'admin';
        return true; // Untuk development awal, bisa semua user terautentikasi
    }

    // Relasi ke Collector (akan kita buat nanti)
    public function collector()
    {
        return $this->hasOne(Collector::class);
    }

    // Relasi ke Transactions (akan kita buat nanti)
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}