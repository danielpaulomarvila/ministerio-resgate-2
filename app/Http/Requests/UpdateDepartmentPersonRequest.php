<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de atualização de vínculo pessoa-departamento
 * Módulo Secretaria - Etapa 10
 * 
 * Valida os dados enviados ao atualizar um vínculo pessoa-departamento,
 * garantindo que as regras de negócio sejam respeitadas.
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 * - Ser líder de departamento não cria permissão automaticamente sem controle
 */
class UpdateDepartmentPersonRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta request.
     * 
     * Apenas usuários com permissão departments.manage_people podem atualizar vínculos.
     * Isso é importante para segurança e auditoria.
     * 
     * @return bool True se usuário tem permissão
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para atualização de vínculo pessoa-departamento
     * 
     * Regras de negócio implementadas:
     * - Função obrigatória
     * - Flags booleanos
     * - Notas opcionais
     * 
     * @return array<string, string> Regras de validação
     */
    public function rules(): array
    {
        return [
            'role' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
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
            'role.required' => 'A função é obrigatória.',
        ];
    }
}
