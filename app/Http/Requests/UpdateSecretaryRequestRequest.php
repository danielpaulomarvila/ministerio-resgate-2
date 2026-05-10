<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSecretaryRequestRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high,urgent',
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
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in' => 'A prioridade deve ser válida.',
            'requester_person_id.exists' => 'A pessoa informada não existe.',
            'related_alert_id.exists' => 'O alerta informado não existe.',
            'assigned_to_user_id.exists' => 'O usuário informado não existe.',
            'due_at.after' => 'A data de prazo deve ser posterior a agora.',
        ];
    }
}
