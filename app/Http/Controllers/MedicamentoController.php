<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MedicamentoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->get('buscar', ''));

        $medicamentos = Medicamento::query()
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'ilike', "%{$buscar}%")
                        ->orWhere('presentacion', 'ilike', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->orderBy('presentacion')
            ->paginate(15)
            ->withQueryString();

        return view('medicamentos.index', compact('medicamentos', 'buscar'));
    }

    public function create(): View
    {
        return view('medicamentos.create');
    }

    public function verificarDuplicado(Request $request): JsonResponse
    {
        $nombre = trim((string) $request->query('nombre', ''));
        $presentacion = trim((string) $request->query('presentacion', ''));
        $concentracion = trim((string) $request->query('concentracion', ''));
        $unidadMedida = trim((string) $request->query('unidad_medida', ''));
        $ignorarId = $request->integer('ignorar_id');

        if ($nombre === '' || $presentacion === '') {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => $this->duplicadoExiste(
                $nombre,
                $presentacion,
                $concentracion,
                $unidadMedida,
                $ignorarId ?: null
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarDatos($request);
        $datos = $this->normalizarDatos($datos);

        if ($this->duplicadoExiste(
            $datos['nombre'],
            $datos['presentacion'],
            $datos['concentracion'],
            $datos['unidad_medida']
        )) {
            throw ValidationException::withMessages([
                'nombre' => 'Ya existe un medicamento con el mismo nombre, presentación, concentración y unidad de medida.',
            ]);
        }

        $datos['descripcion'] = null;
        $datos['activo'] = true;

        Medicamento::create($datos);

        return redirect()
            ->route('medicamentos.index')
            ->with('success', 'Medicamento registrado correctamente.');
    }

    public function edit(Medicamento $medicamento): View
    {
        return view('medicamentos.edit', compact('medicamento'));
    }

    public function update(Request $request, Medicamento $medicamento): RedirectResponse
    {
        $datos = $this->validarDatos($request, true);
        $datos = $this->normalizarDatos($datos);

        if ($this->duplicadoExiste(
            $datos['nombre'],
            $datos['presentacion'],
            $datos['concentracion'],
            $datos['unidad_medida'],
            $medicamento->id
        )) {
            throw ValidationException::withMessages([
                'nombre' => 'Ya existe otro medicamento con el mismo nombre, presentación, concentración y unidad de medida.',
            ]);
        }

        $datos['descripcion'] = null;
        $datos['activo'] = $request->boolean('activo');

        $medicamento->update($datos);

        return redirect()
            ->route('medicamentos.index')
            ->with('success', 'Medicamento actualizado correctamente.');
    }

    private function validarDatos(Request $request, bool $actualizando = false): array
    {
        $reglas = [
            'nombre' => ['required', 'string', 'max:150'],
            'principio_activo' => ['nullable', 'string', 'max:150'],
            'presentacion' => ['required', 'string', 'max:100'],
            'concentracion' => ['nullable', 'string', 'max:100'],
            'unidad_medida' => ['nullable', 'string', 'max:50'],
        ];

        if ($actualizando) {
            $reglas['activo'] = ['nullable', 'boolean'];
        }

        return $request->validate($reglas, [
            'nombre.required' => 'El nombre del medicamento es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'presentacion.required' => 'La presentación es obligatoria.',
            'presentacion.max' => 'La presentación no puede superar los 100 caracteres.',
            'principio_activo.max' => 'El principio activo no puede superar los 150 caracteres.',
            'concentracion.max' => 'La concentración no puede superar los 100 caracteres.',
            'unidad_medida.max' => 'La unidad de medida no puede superar los 50 caracteres.',
        ]);
    }

    private function normalizarDatos(array $datos): array
    {
        foreach (['nombre', 'principio_activo', 'presentacion', 'concentracion', 'unidad_medida'] as $campo) {
            if (!array_key_exists($campo, $datos)) {
                continue;
            }

            $valor = trim((string) ($datos[$campo] ?? ''));
            $datos[$campo] = $valor === '' ? null : $valor;
        }

        return $datos;
    }

    private function duplicadoExiste(
        string $nombre,
        string $presentacion,
        ?string $concentracion,
        ?string $unidadMedida,
        ?int $ignorarId = null
    ): bool {
        $query = Medicamento::query()
            ->whereRaw('LOWER(TRIM(nombre)) = LOWER(TRIM(?))', [$nombre])
            ->whereRaw('LOWER(TRIM(presentacion)) = LOWER(TRIM(?))', [$presentacion])
            ->whereRaw("LOWER(TRIM(COALESCE(concentracion, ''))) = LOWER(TRIM(?))", [$concentracion ?? ''])
            ->whereRaw("LOWER(TRIM(COALESCE(unidad_medida, ''))) = LOWER(TRIM(?))", [$unidadMedida ?? '']);

        if ($ignorarId !== null) {
            $query->whereKeyNot($ignorarId);
        }

        return $query->exists();
    }
}
