<?php

namespace App\Http\Requests;

use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Dados da oficina, editados pelo dono.
 */
class WorkshopRequest extends FormRequest
{
    public const STATES = [
        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA',
        'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO',
    ];

    public function authorize(): bool
    {
        // A rota já exige ser o dono da oficina (middleware "owner")
        return true;
    }

    /**
     * CPF/CNPJ e CEP são guardados só com números; a UF em maiúsculas.
     */
    protected function prepareForValidation(): void
    {
        $digits = fn ($value) => ($clean = preg_replace('/\D/', '', (string) $value)) === '' ? null : $clean;

        $this->merge([
            'document' => $digits($this->input('document')),
            'zip_code' => $digits($this->input('zip_code')),
            'state' => $this->filled('state') ? strtoupper(trim($this->input('state'))) : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'document' => [
                'nullable',
                new CpfCnpj,
                Rule::unique('tenants', 'document')->ignore($this->user()->tenant_id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'zip_code' => ['nullable', 'digits:8'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', Rule::in(self::STATES)],
        ];
    }

    public function messages(): array
    {
        return [
            'document.unique' => 'Este CPF/CNPJ já está cadastrado em outra oficina.',
            'zip_code.digits' => 'O CEP deve ter 8 números.',
            'state.in' => 'Selecione uma UF válida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome da oficina',
            'email' => 'e-mail de contato',
        ];
    }
}
