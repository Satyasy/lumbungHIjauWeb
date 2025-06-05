<?php

// app/Models/TransactionDetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'category_id',
        'estimated_weight',
        'actual_weight',
        'price_per_kg_at_transaction',
        'photo_path',
    ];

    protected $casts = [
        'estimated_weight' => 'decimal:2',
        'actual_weight' => 'decimal:2',
        'price_per_kg_at_transaction' => 'decimal:2',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function wasteCategory(): BelongsTo // Menggunakan nama yang jelas
    {
        return $this->belongsTo(WasteCategory::class, 'category_id');
    }
}