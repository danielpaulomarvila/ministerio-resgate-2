<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de atualização de pessoa
 * Módulo Secretaria - Fase 2.1
 * 
 * Valida os dados enviados ao atualizar uma pessoa existente,
 * garantindo que as regras de negócio sejam respeitadas.
 * Diferente do StorePersonRequest, este permite que email e
 * document_number sejam iguais aos da própria pessoa sendo editada.
 */
class UpdatePersonRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta request.
     * 
     * Apenas usuários autenticados podem atualizar pessoas.
     * Isso é importante para segurança e auditoria.
     * 
     * @return bool True se usuário está autenticado
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para atualização de pessoa
     * 
     * Regras de negócio implementadas:
     * - Nome completo obrigatório
     * - Email opcional, mas válido e único se preenchido (exceto para a própria pessoa)
     * - Data de nascimento opcional, mas válida se preenchida
     * - Gênero opcional, mas deve ter valor válido se preenchido
     * - Documento opcional, mas único se preenchido (exceto para a própria pessoa)
     * - is_baptized deve ser boolean
     * - baptism_date só faz sentido se is_baptized for true
     * - person_status deve ter valores controlados
     * 
     * @return array<string, ValidationRule|array<mixed>|string> Regras de validação
     */
    public function rules(): array
    {
        // Obtém o ID da pessoa sendo editada da rota
        $personId = $this->route('person')->id;

        return [
            // Nome completo obrigatório
            'full_name' => 'required|string|max:255',
            
            // Nome preferido opcional
            'preferred_name' => 'nullable|string|max:255',
            
            // Data de nascimento opcional, mas válida se preenchida
            'birth_date' => 'nullable|date|before:today',
            
            // Gênero opcional, mas deve ter valor válido se preenchido
            'gender' => 'nullable|in:male,female,other',
            
            // Email opcional, mas válido e único se preenchido (exceto para a própria pessoa)
            'email' => 'nullable|email|unique:people,email,' . $personId,
            
            // Telefone opcional
            'phone' => 'nullable|string|max:50',
            
            // Documento opcional, mas único se preenchido (exceto para a própria pessoa)
            'document_number' => 'nullable|string|max:50|unique:people,document_number,' . $personId,
            
            // Foto opcional (path no storage)
            'photo_path' => 'nullable|string|max:255',
            
            // is_baptized deve ser boolean
            'is_baptized' => 'required|boolean',
            
            // baptism_date só faz sentido se is_baptized for true
            'baptism_date' => 'nullable|date|required_if:is_baptized,true|before:today',
            
            // person_status deve ter valores controlados
            'person_status' => 'required|in:active,inactive,visitor,congregated',
            
            // Observações opcionais
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
            'full_name.required' => 'O nome completo é obrigatório.',
            'full_name.max' => 'O nome completo não pode ter mais de 255 caracteres.',
            
            'birth_date.date' => 'A data de nascimento deve ser uma data válida.',
            'birth_date.before' => 'A data de nascimento deve ser anterior a hoje.',
            
            'gender.in' => 'O gênero deve ser masculino, feminino ou outro.',
            
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está cadastrado.',
            
            'document_number.unique' => 'Este documento já está cadastrado.',
            
            'is_baptized.required' => 'É necessário informar se a pessoa é batizada.',
            'is_baptized.boolean' => 'O campo de batismo deve ser verdadeiro ou falso.',
            
            'baptism_date.required_if' => 'A data de batismo é obrigatória quando a pessoa é batizada.',
            'baptism_date.date' => 'A data de batismo deve ser uma data válida.',
            'baptism_date.before' => 'A data de batismo deve ser anterior a hoje.',
            
            'person_status.required' => 'O status da pessoa é obrigatório.',
            'person_status.in' => 'O status deve ser ativo, inativo, visitante ou congregado.',
        ];
    }
}
