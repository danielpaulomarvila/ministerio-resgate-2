<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialCorrectionRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'financial_transaction_id',
        'person_id',
        'requested_by',
        'reason',
        'description',
        'status',
        'attachment_path',
        'reviewed_by',
        'reviewed_at',
        'response',
        'metadata',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function financialTransaction(): BelongsTo
    {
        return $this->belongsTo(FinancialTransaction::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
