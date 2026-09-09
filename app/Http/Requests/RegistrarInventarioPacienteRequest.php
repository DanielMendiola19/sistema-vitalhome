<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarInventarioPacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'paciente_id' => [
                'required',
                'exists:pacientes,id',
            ],

            'medicamento_id' => [
                'required',
                'exists:medicamentos,id',
            ],

            'cantidad' => [
                'required',
                'integer',
                'min:1',
            ],

            'cantidad_minima' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'fecha_vencimiento' => [
                'nullable',
                'date',
            ],

            'lote' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }
}
