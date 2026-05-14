<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'person_id',
        'family_id',
        'origin_transaction_id',
        'amount',
        'remaining_amount',
        'currency',
        'status',
        'reason',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'expires_at' => 'date',
        'metadata' => 'array',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function originTransaction(): BelongsTo
    {
        return $this->belongsTo(FinancialTransaction::class, 'origin_transaction_id');
    }
}
