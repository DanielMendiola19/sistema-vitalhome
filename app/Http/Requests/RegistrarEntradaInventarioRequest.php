<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarEntradaInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'medicamento_id' => [
                'required',
                'exists:medicamentos,id',
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

            'observaciones' => [
                'nullable',
                'string',
            ],

            'lote' => [
                'nullable',
                'string',
                'max:100',
            ],

            'fecha_vencimiento' => [
                'nullable',
                'date',
            ],
        ];
    }
}
