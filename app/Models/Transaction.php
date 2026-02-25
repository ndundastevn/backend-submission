<?php

namespace App\Models;

use App\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\TransactionFactory> */
class Transaction extends Model
{
    use HasFactory;
    /**
     * @var list<string>
     */
    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'description',
        'date',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
