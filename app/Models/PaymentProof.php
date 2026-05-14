<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProof extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'financial_transaction_id',
        'person_id',
        'uploaded_by',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'amount',
        'paid_at',
        'status',
        'notes',
        'reviewed_by',
        'reviewed_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'date',
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

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
