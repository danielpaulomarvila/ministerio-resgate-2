<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação de atualização de responsabilidade
 * Módulo Secretaria - Etapa 3
 *
 * Valida os dados enviados ao atualizar uma responsabilidade existente,
 * garantindo que as regras de negócio sejam respeitadas.
 */
class UpdateGuardianshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Apenas usuários autenticados podem atualizar responsabilidades.
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
     * - Menor obrigatório e deve existir em people
     * - Responsável obrigatório e deve existir em people
     * - Responsável não pode ser a mesma pessoa que o menor
     * - Relação obrigatória com valores controlados
     * - Permissões boolean
     * - Datas de período válidas
     * - Status obrigatório com valores controlados
     * - Observações opcionais
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'minor_person_id' => 'required|exists:people,id',
            'guardian_person_id' => 'required|exists:people,id|different:minor_person_id',
            'relationship_type' => 'required|in:father,mother,grandfather,grandmother,uncle,aunt,brother,sister,legal_guardian,tutor,other',
            'is_legal_guardian' => 'boolean',
            'is_financial_responsible' => 'boolean',
            'can_authorize_login' => 'boolean',
            'can_approve_changes' => 'boolean',
            'can_view_financial' => 'boolean',
            'can_receive_canteen_debts' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'status' => 'required|in:active,inactive,ended',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * Adiciona validações customizadas:
     * - Menor deve ter menos de 18 anos
     * - Responsável deve ser adulto (18+)
     * - Se menor de 11 anos, deve haver responsável legal e financeiro
     * - Não permitir duplicidade exata ativa (exceto o próprio registro)
     * - Se can_receive_canteen_debts for true, is_financial_responsible também deve ser true
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $minorId = $this->input('minor_person_id');
            $guardianId = $this->input('guardian_person_id');
            $guardianshipId = $this->route('guardianship')->id;

            // Verificar se o menor tem menos de 18 anos
            $minor = \App\Models\Person::find($minorId);
            if ($minor && $minor->age >= 18) {
                $validator->errors()->add('minor_person_id', 'A pessoa selecionada como menor deve ter menos de 18 anos.');
            }

            // Verificar se o responsável é adulto
            $guardian = \App\Models\Person::find($guardianId);
            if ($guardian && $guardian->age < 18) {
                $validator->errors()->add('guardian_person_id', 'O responsável deve ser adulto (18 anos ou mais).');
            }

            // Se menor de 11 anos, deve ter responsável legal e financeiro marcado
            if ($minor && $minor->isUnder11YearsOld()) {
                if (!$this->input('is_legal_guardian')) {
                    $validator->errors()->add('is_legal_guardian', 'Para menores de 11 anos, deve haver pelo menos um responsável legal.');
                }
                if (!$this->input('is_financial_responsible')) {
                    $validator->errors()->add('is_financial_responsible', 'Para menores de 11 anos, deve haver pelo menos um responsável financeiro.');
                }
            }

            // Verificar duplicidade exata ativa (ignorando o próprio registro)
            $existing = \App\Models\GuardianShip::where('minor_person_id', $minorId)
                ->where('guardian_person_id', $guardianId)
                ->where('relationship_type', $this->input('relationship_type'))
                ->where('status', 'active')
                ->whereNull('ends_at')
                ->where('id', '!=', $guardianshipId)
                ->first();

            if ($existing) {
                $validator->errors()->add('minor_person_id', 'Já existe uma responsabilidade ativa com as mesmas características.');
            }

            // Se can_receive_canteen_debts for true, is_financial_responsible também deve ser true
            if ($this->input('can_receive_canteen_debts') && !$this->input('is_financial_responsible')) {
                $validator->errors()->add('can_receive_canteen_debts', 'Para receber dívidas da cantina, o responsável também deve ser financeiramente responsável.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'minor_person_id.required' => 'A pessoa menor é obrigatória.',
            'minor_person_id.exists' => 'A pessoa menor não foi encontrada no sistema.',
            'guardian_person_id.required' => 'O responsável é obrigatório.',
            'guardian_person_id.exists' => 'O responsável não foi encontrado no sistema.',
            'guardian_person_id.different' => 'O responsável não pode ser a mesma pessoa que o menor.',
            'relationship_type.required' => 'O tipo de relação é obrigatório.',
            'relationship_type.in' => 'O tipo de relação deve ser pai, mãe, avô, avó, tio, tia, irmão, irmã, responsável legal, tutor ou outro.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser ativo, inativo ou encerrado.',
            'ends_at.after_or_equal' => 'A data de fim deve ser posterior ou igual à data de início.',
        ];
    }
}
