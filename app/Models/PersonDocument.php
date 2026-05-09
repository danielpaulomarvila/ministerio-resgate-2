<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PersonDocument
 * 
 * Este model representa os documentos de uma pessoa.
 * 
 * Os documentos foram separados da tabela principal people para:
 * - Evitar que a tabela people fique inchada com muitos campos
 * - Permitir histórico de documentos futuramente
 * - Facilitar a expansão para novos tipos de documentos
 * - Melhorar a organização do banco de dados
 * 
 * Contexto: Sistema para uso em Portugal
 * - NIF é o documento fiscal principal
 * - Cartão de Cidadão, Passaporte e Título de Residência são documentos comuns
 */
class PersonDocument extends Model
{
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'person_id',
        'nif',
        'citizen_card_number',
        'passport_number',
        'residence_permit_number',
        'other_document',
        'document_notes',
    ];

    /**
     * Relacionamento: Um registro de documentos pertence a uma pessoa
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
