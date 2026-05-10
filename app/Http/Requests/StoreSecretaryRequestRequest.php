<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSecretaryRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:registration_review,personal_data_change,family_link_review,guardianship_review,child_transition_review,alert_resolution_review,manual_secretary_request',
            'priority' => 'required|in:low,normal,high,urgent',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requester_person_id' => 'nullable|exists:people,id',
            'related_alert_id' => 'nullable|exists:system_alerts,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date|after:now',
            'internal_notes' => 'nullable|string',
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
            'type.required' => 'O tipo de solicitação é obrigatório.',
            'type.in' => 'O tipo de solicitação deve ser válido.',
            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in' => 'A prioridade deve ser válida.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'requester_person_id.exists' => 'A pessoa informada não existe.',
            'related_alert_id.exists' => 'O alerta informado não existe.',
            'assigned_to_user_id.exists' => 'O usuário informado não existe.',
            'due_at.after' => 'A data de prazo deve ser posterior a agora.',
        ];
    }
}
