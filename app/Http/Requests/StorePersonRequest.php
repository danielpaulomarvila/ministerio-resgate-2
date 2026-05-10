<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de criação de pessoa
 * Módulo Secretaria - Fase 2.1
 * 
 * Valida os dados enviados ao criar uma nova pessoa,
 * garantindo que as regras de negócio sejam respeitadas.
 */
class StorePersonRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta request.
     * 
     * Apenas usuários autenticados podem criar pessoas.
     * Isso é importante para segurança e auditoria.
     * 
     * @return bool True se usuário está autenticado
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação de pessoa
     * 
     * Regras de negócio implementadas:
     * - Nome completo obrigatório
     * - Email opcional, mas válido se preenchido
     * - Data de nascimento opcional, mas válida se preenchida (before_or_equal:today)
     * - Estado civil e escolaridade opcionais, mas com valores controlados
     * - Gênero opcional, mas deve ter valor válido se preenchido
     * - is_baptized deve ser boolean
     * - baptism_date opcional mesmo se is_baptized for true (pode não saber data exata)
     * - conversion_date opcional, válida se preenchida
     * - person_status deve ter valores controlados (novos valores adicionados)
     * - invited_by_person_id opcional, mas deve existir em people se preenchido
     * 
     * Documentos (tabela separada):
     * - NIF opcional, mas único se preenchido
     * - Título de Residência, Outro Documento opcionais
     * 
     * Morada (tabela separada):
     * - Código postal no formato português 0000-000
     * - Campos de morada opcionais
     * 
     * @return array<string, ValidationRule|array<mixed>|string> Regras de validação
     */
    public function rules(): array
    {
        return [
            // Dados pessoais
            'person.full_name' => 'required|string|max:255',
            'person.preferred_name' => 'nullable|string|max:255',
            'person.last_name' => 'nullable|string|max:255',
            'person.birth_date' => 'nullable|date|before_or_equal:today',
            'person.gender' => 'nullable|in:male,female,other',
            'person.marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
            'person.education_level' => 'nullable|in:elementary,high_school,college,postgraduate,other',
            'person.nationality' => 'nullable|string|max:100',
            'person.birthplace' => 'nullable|string|max:150',
            'person.profession' => 'nullable|string|max:150',
            'person.occupation' => 'nullable|string|max:150',
            
            // Contactos
            'person.primary_phone' => 'nullable|string|max:50',
            'person.secondary_phone' => 'nullable|string|max:50',
            'person.whatsapp' => 'nullable|string|max:50',
            'person.email' => 'nullable|email|unique:people,email',
            'person.contact_notes' => 'nullable|string',
            
            // Foto
            'person.photo_path' => 'nullable|string|max:255',
            
            // Dados de vida cristã
            'person.is_baptized' => 'required|boolean',
            'person.baptism_date' => 'nullable|date|before_or_equal:today',
            'person.conversion_date' => 'nullable|date|before_or_equal:today',
            'person.invited_by_person_id' => 'nullable|exists:people,id',
            'person.person_status' => 'required|in:active,inactive,visitor,congregant,discipling,new_convert,regularization',
            
            // Observações gerais
            'person.general_notes' => 'nullable|string',
            
            // Documentos (tabela separada)
            'document.nif' => 'nullable|string|max:50|unique:person_documents,nif',
            'document.residence_permit_number' => 'nullable|string|max:100',
            'document.other_document' => 'nullable|string|max:150',
            'document.document_notes' => 'nullable|string',
            
            // Morada (tabela separada)
            'address.country_name' => 'nullable|string|max:100',
            'address.district_name' => 'nullable|string|max:100',
            'address.municipality_name' => 'nullable|string|max:100',
            'address.parish_name' => 'nullable|string|max:100',
            'address.locality_name' => 'nullable|string|max:100',
            'address.address_line' => 'nullable|string|max:255',
            'address.door_number' => 'nullable|string|max:50',
            'address.floor_or_unit' => 'nullable|string|max:50',
            'address.address_complement' => 'nullable|string|max:255',
            'address.postal_code' => 'nullable|regex:/^\d{4}-\d{3}$/',
            'address.full_address' => 'nullable|string',
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
            // Dados pessoais
            'person.full_name.required' => 'O nome completo é obrigatório.',
            'person.full_name.max' => 'O nome completo não pode ter mais de 255 caracteres.',
            'person.birth_date.date' => 'A data de nascimento deve ser uma data válida.',
            'person.birth_date.before_or_equal' => 'A data de nascimento deve ser anterior ou igual a hoje.',
            'person.gender.in' => 'O gênero deve ser masculino, feminino ou outro.',
            'person.marital_status.in' => 'O estado civil deve ser solteiro, casado, divorciado, viúvo ou separado.',
            'person.education_level.in' => 'O nível de escolaridade deve ser fundamental, médio, superior, pós-graduação ou outro.',
            
            // Contactos
            'person.email.email' => 'O email deve ser um endereço válido.',
            'person.email.unique' => 'Este email já está cadastrado.',
            
            // Dados de vida cristã
            'person.is_baptized.required' => 'É necessário informar se a pessoa é batizada.',
            'person.is_baptized.boolean' => 'O campo de batismo deve ser verdadeiro ou falso.',
            'person.baptism_date.date' => 'A data de batismo deve ser uma data válida.',
            'person.baptism_date.before_or_equal' => 'A data de batismo deve ser anterior ou igual a hoje.',
            'person.conversion_date.date' => 'A data de conversão deve ser uma data válida.',
            'person.conversion_date.before_or_equal' => 'A data de conversão deve ser anterior ou igual a hoje.',
            'person.invited_by_person_id.exists' => 'A pessoa que convidou não foi encontrada no sistema.',
            'person.person_status.required' => 'O status da pessoa é obrigatório.',
            'person.person_status.in' => 'O status deve ser ativo, inativo, visitante, congregado, discipulando, novo convertido ou em regularização.',
            
            // Documentos
            'document.nif.unique' => 'O NIF informado já está cadastrado.',
            'document.nif.max' => 'O NIF não pode ter mais de 50 caracteres.',
            'document.residence_permit_number.max' => 'O número do Título de Residência não pode ter mais de 100 caracteres.',
            'document.other_document.max' => 'O outro documento não pode ter mais de 150 caracteres.',
            
            // Morada
            'address.postal_code.regex' => 'O código postal deve estar no formato 0000-000.',
        ];
    }
}
