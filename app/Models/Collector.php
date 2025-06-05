<?php
// app/Models/Collector.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collector extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_area',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Transactions (akan kita buat nanti)
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'collector_id');
    }
}