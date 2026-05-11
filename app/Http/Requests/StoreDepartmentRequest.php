<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de criação de departamento
 * Módulo Secretaria - Etapa 10
 * 
 * Valida os dados enviados ao criar um novo departamento,
 * garantindo que as regras de negócio sejam respeitadas.
 * 
 * IMPORTANTE:
 * - Departamento não cria membro automaticamente
 * - Participar de departamento não cria usuário automaticamente
 * - Liderar departamento não cria permissão automaticamente sem controle
 */
class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta request.
     * 
     * Apenas usuários com permissão departments.create podem criar departamentos.
     * Isso é importante para segurança e auditoria.
     * 
     * @return bool True se usuário tem permissão
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação de departamento
     * 
     * Regras de negócio implementadas:
     * - Nome obrigatório
     * - Slug obrigatório e único
     * - Tipo de departamento obrigatório com valores controlados
     * - Status obrigatório com valores controlados
     * - Departamento pai opcional, mas deve existir se preenchido
     * - Líder opcional, mas deve existir em people se preenchido
     * - Auxiliar opcional, mas deve existir em people se preenchido
     * - Horário de reunião opcional, mas válido se preenchido
     * - Sort_order deve ser inteiro
     * 
     * @return array<string, string> Regras de validação
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:departments,slug',
            'description' => 'nullable|string',
            'department_type' => 'required|in:ministry,administrative,youth,support,financial,worship,children,evangelism,other',
            'status' => 'required|in:active,inactive,archived',
            'parent_department_id' => 'nullable|exists:departments,id',
            'leader_person_id' => 'nullable|exists:people,id',
            'assistant_leader_person_id' => 'nullable|exists:people,id',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'meeting_day' => 'nullable|string|max:50',
            'meeting_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_system' => 'boolean',
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
            'name.required' => 'O nome do departamento é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'slug.required' => 'O identificador é obrigatório.',
            'slug.unique' => 'Este identificador já está em uso.',
            'department_type.required' => 'O tipo de departamento é obrigatório.',
            'department_type.in' => 'O tipo deve ser ministry, administrative, youth, support, financial, worship, children, evangelism ou other.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser active, inactive ou archived.',
            'parent_department_id.exists' => 'O departamento pai não foi encontrado.',
            'leader_person_id.exists' => 'A pessoa líder não foi encontrada.',
            'assistant_leader_person_id.exists' => 'A pessoa auxiliar não foi encontrada.',
            'meeting_time.date_format' => 'O horário deve estar no formato HH:MM.',
        ];
    }
}
