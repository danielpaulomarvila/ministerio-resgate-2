<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PersonAddress
 * 
 * Este model representa as moradas de uma pessoa.
 * 
 * As moradas foram separadas da tabela principal people para:
 * - Evitar que a tabela people fique inchada com muitos campos
 * - Permitir que uma pessoa tenha múltiplas moradas futuramente
 * - Facilitar a expansão para novos campos de endereço
 * - Melhorar a organização do banco de dados
 * 
 * Contexto: Sistema para uso em Portugal
 * - Estrutura portuguesa de morada: Distrito, Concelho, Freguesia, Localidade
 * - Código postal português no formato 0000-000
 * - Futuramente poderemos normalizar com tabelas oficiais ou dados externos
 */
class PersonAddress extends Model
{
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'person_id',
        'country_name',
        'district_name',
        'municipality_name',
        'parish_name',
        'locality_name',
        'locality_manual',
        'address_line',
        'door_number',
        'floor_or_unit',
        'address_complement',
        'postal_code',
        'full_address',
        'is_primary',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relacionamento: Uma morada pertence a uma pessoa
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
