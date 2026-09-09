<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarSalidaInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'inventario_id' => [
                'required',
                'exists:inventarios,id',
            ],

            'cantidad' => [
                'required',
                'integer',
                'min:1',
            ],

            'motivo' => [
                'nullable',
                'string',
                'max:150',
            ],
        ];
    }
}
