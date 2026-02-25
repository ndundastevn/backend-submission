<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @use HasFactory<\Database\Factories\WalletFactory> */
class Wallet extends Model
{
    use HasFactory;
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Transaction>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Balance = sum of income amounts minus sum of expense amounts.
     */
    public function getBalanceAttribute(): string
    {
        $total = (float) $this->transactions()
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as balance")
            ->value('balance');

        return number_format($total, 2, '.', '');
    }
}
