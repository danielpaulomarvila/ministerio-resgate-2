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
 * nif sejam iguais aos da própria pessoa sendo editada.
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
     * - Data de nascimento opcional, mas válida se preenchida (before_or_equal:today)
     * - Estado civil e escolaridade opcionais, mas com valores controlados
     * - Gênero opcional, mas deve ter valor válido se preenchido
     * - Documento opcional, mas único se preenchido (exceto para a própria pessoa)
     * - is_baptized deve ser boolean
     * - baptism_date opcional mesmo se is_baptized for true (pode não saber data exata)
     * - conversion_date opcional, válida se preenchida
     * - person_status deve ter valores controlados (novos valores adicionados)
     * - invited_by_person_id opcional, mas deve existir em people se preenchido
     * - Campos de endereço opcionais
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
            
            // Data de nascimento opcional, mas válida se preenchida (aceita hoje também)
            'birth_date' => 'nullable|date|before_or_equal:today',
            
            // Gênero opcional, mas deve ter valor válido se preenchido
            'gender' => 'nullable|in:male,female,other',
            
            // Estado civil opcional, mas com valores controlados
            'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
            
            // Nível de escolaridade opcional, mas com valores controlados
            'education_level' => 'nullable|in:elementary,high_school,college,postgraduate,other',
            
            // Email opcional, mas válido e único se preenchido (exceto para a própria pessoa)
            'email' => 'nullable|email|unique:people,email,' . $personId,
            
            // Telefone opcional
            'phone' => 'nullable|string|max:50',
            
            // Telefone secundário opcional
            'secondary_phone' => 'nullable|string|max:50',
            
            // NIF (Número de Identificação Fiscal) opcional, mas único se preenchido (exceto para a própria pessoa)
            // Documento fiscal principal em Portugal
            'nif' => 'nullable|string|max:50|unique:people,nif,' . $personId,
            
            // Outro documento opcional (Cartão de Cidadão, Título de Residência, Passaporte, etc.)
            'secondary_document' => 'nullable|string|max:100',
            
            // Foto opcional (path no storage)
            'photo_path' => 'nullable|string|max:255',
            
            // Endereço completo estruturado opcional
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:50',
            'address_complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            
            // is_baptized deve ser boolean
            'is_baptized' => 'required|boolean',
            
            // baptism_date opcional mesmo se is_baptized for true (pode não saber data exata)
            'baptism_date' => 'nullable|date|before_or_equal:today',
            
            // conversion_date opcional, válida se preenchida
            'conversion_date' => 'nullable|date|before_or_equal:today',
            
            // invited_by_person_id opcional, mas deve existir em people se preenchido
            'invited_by_person_id' => 'nullable|exists:people,id',
            
            // person_status deve ter valores controlados (novos valores adicionados)
            'person_status' => 'required|in:active,inactive,visitor,congregant,discipling,new_convert,regularization',
            
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
            'birth_date.before_or_equal' => 'A data de nascimento deve ser anterior ou igual a hoje.',
            
            'gender.in' => 'O gênero deve ser masculino, feminino ou outro.',
            
            'marital_status.in' => 'O estado civil deve ser solteiro, casado, divorciado, viúvo ou separado.',
            
            'education_level.in' => 'O nível de escolaridade deve ser fundamental, médio, superior, pós-graduação ou outro.',
            
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está cadastrado.',
            
            'nif.unique' => 'O NIF informado já está cadastrado.',
            'nif.max' => 'O NIF não pode ter mais de 50 caracteres.',
            
            'secondary_document.max' => 'O outro documento não pode ter mais de 100 caracteres.',
            
            'is_baptized.required' => 'É necessário informar se a pessoa é batizada.',
            'is_baptized.boolean' => 'O campo de batismo deve ser verdadeiro ou falso.',
            
            'baptism_date.date' => 'A data de batismo deve ser uma data válida.',
            'baptism_date.before_or_equal' => 'A data de batismo deve ser anterior ou igual a hoje.',
            
            'conversion_date.date' => 'A data de conversão deve ser uma data válida.',
            'conversion_date.before_or_equal' => 'A data de conversão deve ser anterior ou igual a hoje.',
            
            'invited_by_person_id.exists' => 'A pessoa que convidou não foi encontrada no sistema.',
            
            'person_status.required' => 'O status da pessoa é obrigatório.',
            'person_status.in' => 'O status deve ser ativo, inativo, visitante, congregado, discipulando, novo convertido ou em regularização.',
        ];
    }
}
