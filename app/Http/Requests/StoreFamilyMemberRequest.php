<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de vinculação de pessoa à família
 * Módulo Secretaria - Etapa 2
 *
 * Valida os dados enviados ao vincular uma pessoa a uma família,
 * garantindo que as regras de negócio sejam respeitadas.
 */
class StoreFamilyMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Apenas usuários autenticados podem vincular pessoas a famílias.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Regras de negócio implementadas:
     * - Pessoa obrigatória e deve existir em people
     * - Papel familiar obrigatório com valores controlados
     * - is_responsible deve ser boolean
     * - joined_at opcional, mas válida se preenchida
     * - left_at opcional, mas deve ser após joined_at se preenchida
     * - Observações opcionais
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'person_id' => 'required|exists:people,id',
            'role' => 'required|in:father,mother,son,daughter,spouse,guardian,relative,other',
            'is_responsible' => 'boolean',
            'joined_at' => 'nullable|date',
            'left_at' => 'nullable|date|after_or_equal:joined_at',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'person_id.required' => 'A pessoa é obrigatória.',
            'person_id.exists' => 'A pessoa não foi encontrada no sistema.',
            'role.required' => 'O papel familiar é obrigatório.',
            'role.in' => 'O papel familiar deve ser pai, mãe, filho, filha, cônjuge, responsável, familiar ou outro.',
            'left_at.after_or_equal' => 'A data de saída deve ser posterior ou igual à data de entrada.',
        ];
    }
}
