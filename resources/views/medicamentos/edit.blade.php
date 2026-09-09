@extends('layouts.app')

@section('title', 'Editar medicamento')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <a
            href="{{ route('medicamentos.index') }}"
            class="text-decoration-none"
            style="color:#059669;"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Volver a medicamentos
        </a>

        <h1
            class="fw-bold mt-3 mb-1"
            style="color:#0f172a;"
        >
            Editar medicamento
        </h1>

        <p class="text-muted mb-0">
            Actualiza la información del medicamento.
        </p>

    </div>


    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <form
                method="POST"
                action="{{ route(
                    'medicamentos.update',
                    $medicamento
                ) }}"
            >

                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Nombre *
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="{{ old(
                                'nombre',
                                $medicamento->nombre
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Principio activo
                        </label>

                        <input
                            type="text"
                            name="principio_activo"
                            class="form-control"
                            value="{{ old(
                                'principio_activo',
                                $medicamento->principio_activo
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Presentación *
                        </label>

                        <input
                            type="text"
                            name="presentacion"
                            class="form-control"
                            value="{{ old(
                                'presentacion',
                                $medicamento->presentacion
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Concentración
                        </label>

                        <input
                            type="text"
                            name="concentracion"
                            class="form-control"
                            value="{{ old(
                                'concentracion',
                                $medicamento->concentracion
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Unidad de medida
                        </label>

                        <input
                            type="text"
                            name="unidad_medida"
                            class="form-control"
                            value="{{ old(
                                'unidad_medida',
                                $medicamento->unidad_medida
                            ) }}"
                        >

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Descripción
                        </label>

                        <textarea
                            name="descripcion"
                            class="form-control"
                            rows="3"
                        >{{ old(
                            'descripcion',
                            $medicamento->descripcion
                        ) }}</textarea>

                    </div>


                    <div class="col-12">

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="activo"
                                value="1"
                                class="form-check-input"
                                id="activo"
                                {{ $medicamento->activo ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="activo"
                            >
                                Medicamento activo
                            </label>

                        </div>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('medicamentos.index') }}"
                        class="btn btn-light"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
