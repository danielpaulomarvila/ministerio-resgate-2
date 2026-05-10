<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de criação de família
 * Módulo Secretaria - Etapa 2
 *
 * Valida os dados enviados ao criar uma nova família,
 * garantindo que as regras de negócio sejam respeitadas.
 */
class StoreFamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Apenas usuários autenticados podem criar famílias.
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
     * - Nome obrigatório
     * - Descrição opcional
     * - Responsável principal opcional, mas deve existir em people se preenchido
     * - Status obrigatório com valores controlados
     * - Observações opcionais
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsible_person_id' => 'nullable|exists:people,id',
            'status' => 'required|in:active,inactive',
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
            'name.required' => 'O nome da família é obrigatório.',
            'name.max' => 'O nome da família não pode ter mais de 255 caracteres.',
            'responsible_person_id.exists' => 'A pessoa responsável não foi encontrada no sistema.',
            'status.required' => 'O status da família é obrigatório.',
            'status.in' => 'O status deve ser ativo ou inativo.',
        ];
    }
}
