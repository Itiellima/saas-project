<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validação usada ao criar e ao editar uma ordem de serviço.
 */
class ServiceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // A rota já exige login; a OS é buscada pelo tenant no controller.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'vehicle_plate' => ['nullable', 'string', 'max:20'],
            'vehicle_model' => ['nullable', 'string', 'max:50'],
            'vehicle_km' => ['nullable', 'integer'],
            'vehicle_enter' => ['nullable', 'date'],
            'vehicle_leave' => ['nullable', 'date', 'after_or_equal:vehicle_enter'],
            'description' => ['nullable', 'string'],
        ];
    }
}
