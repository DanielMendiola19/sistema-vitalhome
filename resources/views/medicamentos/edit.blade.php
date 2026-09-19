@extends('layouts.app')

@section('title', 'Editar medicamento')

@section('content')
<style>
    .medicine-form-card { border:0; border-radius:18px; box-shadow:0 8px 24px rgba(15,23,42,.06); }
    .medicine-form-card .form-control { min-height:45px; border-radius:10px; }
    .field-message { min-height:20px; font-size:.82rem; margin-top:5px; }
    .duplicate-box { display:none; border-radius:12px; padding:12px 14px; margin-top:4px; }
    .duplicate-box.show { display:block; }
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('medicamentos.index') }}" class="text-decoration-none" style="color:#059669;">
            <i class="bi bi-arrow-left me-1"></i> Volver a medicamentos
        </a>
        <h1 class="fw-bold mt-3 mb-1" style="color:#0f172a;">Editar medicamento</h1>
        <p class="text-muted mb-0">Actualiza la información del medicamento.</p>
    </div>

    <div class="card medicine-form-card">
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    <strong>Revisa los datos ingresados.</strong>
                    <div class="small mt-1">{{ $errors->first() }}</div>
                </div>
            @endif

            <form id="medicamentoForm" method="POST" action="{{ route('medicamentos.update', $medicamento) }}" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input id="nombre" type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $medicamento->nombre) }}" maxlength="150" required>
                        <div class="field-message" data-message-for="nombre"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="principio_activo" class="form-label">Principio activo</label>
                        <input id="principio_activo" type="text" name="principio_activo" class="form-control @error('principio_activo') is-invalid @enderror" value="{{ old('principio_activo', $medicamento->principio_activo) }}" maxlength="150">
                        <div class="field-message" data-message-for="principio_activo"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="presentacion" class="form-label">Presentación *</label>
                        <input id="presentacion" type="text" name="presentacion" class="form-control @error('presentacion') is-invalid @enderror" placeholder="Ej. Tableta" value="{{ old('presentacion', $medicamento->presentacion) }}" maxlength="100" required>
                        <div class="field-message" data-message-for="presentacion"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="concentracion" class="form-label">Concentración</label>
                        <input id="concentracion" type="text" name="concentracion" class="form-control @error('concentracion') is-invalid @enderror" placeholder="Ej. 500" value="{{ old('concentracion', $medicamento->concentracion) }}" maxlength="100">
                        <div class="field-message" data-message-for="concentracion"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="unidad_medida" class="form-label">Unidad de medida</label>
                        <input id="unidad_medida" type="text" name="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" placeholder="Ej. mg" value="{{ old('unidad_medida', $medicamento->unidad_medida) }}" maxlength="50">
                        <div class="field-message" data-message-for="unidad_medida"></div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="activo" value="1" class="form-check-input" id="activo" {{ old('activo', $medicamento->activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Medicamento activo</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="duplicateBox" class="duplicate-box"></div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-light">Cancelar</a>
                    <button id="submitButton" type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('medicamentoForm');
    const submitButton = document.getElementById('submitButton');
    const duplicateBox = document.getElementById('duplicateBox');
    const fields = {
        nombre: document.getElementById('nombre'),
        principio_activo: document.getElementById('principio_activo'),
        presentacion: document.getElementById('presentacion'),
        concentracion: document.getElementById('concentracion'),
        unidad_medida: document.getElementById('unidad_medida')
    };
    let duplicate = false;
    let timer;
    let requestController;

    function message(name, text, valid) {
        const el = document.querySelector('[data-message-for="' + name + '"]');
        fields[name].classList.toggle('is-invalid', valid === false);
        fields[name].classList.toggle('is-valid', valid === true && fields[name].value.trim() !== '');
        el.textContent = text;
        el.className = 'field-message ' + (valid === false ? 'text-danger' : valid === true ? 'text-success' : 'text-muted');
    }

    function validateField(name) {
        const value = fields[name].value.trim();
        if ((name === 'nombre' || name === 'presentacion') && value === '') {
            message(name, 'Este campo es obligatorio.', false);
            return false;
        }
        const max = {nombre:150, principio_activo:150, presentacion:100, concentracion:100, unidad_medida:50}[name];
        if (value.length > max) {
            message(name, 'Máximo ' + max + ' caracteres.', false);
            return false;
        }
        message(name, value ? 'Correcto.' : '', value ? true : null);
        return true;
    }

    async function checkDuplicate() {
        if (!validateField('nombre') || !validateField('presentacion')) {
            duplicate = false;
            duplicateBox.className = 'duplicate-box';
            duplicateBox.textContent = '';
            return;
        }
        if (requestController) requestController.abort();
        requestController = new AbortController();
        const url = new URL(@json(route('medicamentos.verificar-duplicado')), window.location.origin);
        ['nombre','presentacion','concentracion','unidad_medida'].forEach(function (name) {
            url.searchParams.set(name, fields[name].value.trim());
        });
        url.searchParams.set('ignorar_id', @json($medicamento->id));
        try {
            const response = await fetch(url, {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, signal:requestController.signal});
            if (!response.ok) return;
            const data = await response.json();
            duplicate = Boolean(data.exists);
            if (duplicate) {
                duplicateBox.className = 'duplicate-box show alert alert-danger mb-0';
                duplicateBox.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Ya existe un medicamento con este nombre, presentación, concentración y unidad de medida.';
            } else {
                duplicateBox.className = 'duplicate-box show alert alert-success mb-0';
                duplicateBox.innerHTML = '<i class="bi bi-check-circle me-1"></i> Esta combinación está disponible.';
            }
            submitButton.disabled = duplicate;
        } catch (error) {
            if (error.name !== 'AbortError') duplicate = false;
        }
    }

    Object.keys(fields).forEach(function (name) {
        fields[name].addEventListener('blur', function () { validateField(name); });
        fields[name].addEventListener('input', function () {
            validateField(name);
            if (['nombre','presentacion','concentracion','unidad_medida'].includes(name)) {
                clearTimeout(timer);
                timer = setTimeout(checkDuplicate, 450);
            }
        });
    });

    form.addEventListener('submit', function (event) {
        let valid = true;
        Object.keys(fields).forEach(function (name) { if (!validateField(name)) valid = false; });
        if (!valid || duplicate) event.preventDefault();
    });

    if (fields.nombre.value.trim() && fields.presentacion.value.trim()) checkDuplicate();
});
</script>
@endsection
