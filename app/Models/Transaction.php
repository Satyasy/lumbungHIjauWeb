<?php
// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'collector_id',
        'pickup_location',
        'total_weight',
        'total_price',
        'status',
        'verification_token',
        'token_expires_at',
        'rejection_reason',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'total_weight' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo // User yang membuat transaksi
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collector(): BelongsTo // Collector yang menangani
    {
        return $this->belongsTo(Collector::class, 'collector_id');
    }

    public function transactionDetails(): HasMany // Detail item sampah
    {
        return $this->hasMany(TransactionDetail::class);
    }
}