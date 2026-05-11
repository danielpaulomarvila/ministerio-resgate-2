<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de criação de vínculo pessoa-departamento
 * Módulo Secretaria - Etapa 10
 * 
 * Valida os dados enviados ao adicionar uma pessoa a um departamento,
 * garantindo que as regras de negócio sejam respeitadas.
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 * - Ser líder de departamento não cria permissão automaticamente sem controle
 * - Não permite duplicidade de vínculo ativo da mesma pessoa no mesmo departamento
 */
class StoreDepartmentPersonRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta request.
     * 
     * Apenas usuários com permissão departments.manage_people podem adicionar pessoas.
     * Isso é importante para segurança e auditoria.
     * 
     * @return bool True se usuário tem permissão
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação de vínculo pessoa-departamento
     * 
     * Regras de negócio implementadas:
     * - Pessoa obrigatória e deve existir
     * - Função obrigatória
     * - Status obrigatório com valores controlados
     * - Data de início opcional, mas válida se preenchida
     * - Data de fim opcional, mas deve ser após a data de início se preenchida
     * - Flags booleanos
     * 
     * @return array<string, string> Regras de validação
     */
    public function rules(): array
    {
        return [
            'person_id' => 'required|exists:people,id',
            'role' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'status' => 'required|in:active,inactive,paused,removed',
            'is_leader' => 'boolean',
            'is_assistant' => 'boolean',
            'can_manage_department' => 'boolean',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Mensagens de erro personalizadas
     * 
     * Fornece mensagens em português para melhor UX.
     * 
     * @return array<string, string> Mensagens de erro
     */
    public function messages(): array
    {
        return [
            'person_id.required' => 'A pessoa é obrigatória.',
            'person_id.exists' => 'A pessoa não foi encontrada.',
            'role.required' => 'A função é obrigatória.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser active, inactive, paused ou removed.',
            'ends_at.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
        ];
    }
}
