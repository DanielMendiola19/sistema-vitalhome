<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicamentoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->get('buscar', ''));

        $medicamentos = Medicamento::query()
            ->when(
                $buscar !== '',
                function ($query) use ($buscar) {
                    $query->where(function ($q) use ($buscar) {
                        $q->where('nombre', 'like', "%{$buscar}%")
                            ->orWhere(
                                'principio_activo',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'presentacion',
                                'like',
                                "%{$buscar}%"
                            );
                    });
                }
            )
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view(
            'medicamentos.index',
            compact('medicamentos', 'buscar')
        );
    }

    public function create(): View
    {
        return view('medicamentos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:150',
            ],
            'principio_activo' => [
                'nullable',
                'string',
                'max:150',
            ],
            'presentacion' => [
                'required',
                'string',
                'max:100',
            ],
            'concentracion' => [
                'nullable',
                'string',
                'max:100',
            ],
            'unidad_medida' => [
                'nullable',
                'string',
                'max:50',
            ],
            'descripcion' => [
                'nullable',
                'string',
            ],
        ]);

        $datos['activo'] = true;

        Medicamento::create($datos);

        return redirect()
            ->route('medicamentos.index')
            ->with(
                'success',
                'Medicamento registrado correctamente.'
            );
    }

    public function edit(Medicamento $medicamento): View
    {
        return view(
            'medicamentos.edit',
            compact('medicamento')
        );
    }

    public function update(
        Request $request,
        Medicamento $medicamento
    ): RedirectResponse {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:150',
            ],
            'principio_activo' => [
                'nullable',
                'string',
                'max:150',
            ],
            'presentacion' => [
                'required',
                'string',
                'max:100',
            ],
            'concentracion' => [
                'nullable',
                'string',
                'max:100',
            ],
            'unidad_medida' => [
                'nullable',
                'string',
                'max:50',
            ],
            'descripcion' => [
                'nullable',
                'string',
            ],
            'activo' => [
                'nullable',
                'boolean',
            ],
        ]);

        $datos['activo'] = $request->boolean('activo');

        $medicamento->update($datos);

        return redirect()
            ->route('medicamentos.index')
            ->with(
                'success',
                'Medicamento actualizado correctamente.'
            );
    }
}
