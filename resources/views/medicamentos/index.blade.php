@extends('layouts.app')

@section('title', 'Medicamentos')

@section('content')
<style>
    .med-page { color:#0f172a; }
    .med-card { border:0; border-radius:18px; box-shadow:0 8px 24px rgba(15,23,42,.06); }
    .med-search { position:relative; }
    .med-search i { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#94a3b8; z-index:2; }
    .med-search input { padding-left:42px; min-height:46px; border-radius:12px; }
    .med-clear { min-height:46px; border-radius:12px; white-space:nowrap; }
    .med-table th { font-size:.75rem; color:#64748b; white-space:nowrap; }
    .med-table td { vertical-align:middle; }
    .med-edit { display:inline-flex; align-items:center; justify-content:center; gap:6px; min-width:92px; min-height:36px; border-radius:9px; background:#ecfdf5; color:#047857; text-decoration:none; font-weight:700; font-size:.84rem; }
    .med-edit:hover { background:#d1fae5; color:#065f46; }
    .med-pagination .pagination { margin-bottom:0; flex-wrap:wrap; gap:5px; }
    .med-pagination .page-link { border-radius:9px !important; border:1px solid #e2e8f0; color:#475569; }
    .med-pagination .page-item.active .page-link { background:#059669; border-color:#059669; }
    @media (max-width:767.98px) {
        .med-header { flex-direction:column; gap:14px; }
        .med-header .btn { width:100%; }
        .med-filter-row { flex-direction:column; }
        .med-clear { width:100%; }
    }
</style>

<div class="container-fluid py-4 med-page">
    <div class="d-flex justify-content-between align-items-start mb-4 med-header">
        <div>
            <h1 class="fw-bold mb-1">Medicamentos</h1>
            <p class="text-muted mb-0">Catálogo de medicamentos registrados en VITALHOME.</p>
        </div>
        <a href="{{ route('medicamentos.create') }}" class="btn btn-success rounded-3 px-3">
            <i class="bi bi-plus-lg me-1"></i> Registrar medicamento
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="card med-card mb-4">
        <div class="card-body p-3 p-md-4">
            <form id="medicamentoSearchForm" method="GET" action="{{ route('medicamentos.index') }}">
                <div class="d-flex gap-2 med-filter-row">
                    <div class="med-search flex-grow-1">
                        <i class="bi bi-search"></i>
                        <input
                            id="buscarMedicamento"
                            type="search"
                            name="buscar"
                            class="form-control"
                            value="{{ $buscar }}"
                            placeholder="Buscar por nombre o presentación..."
                            autocomplete="off"
                            aria-label="Buscar medicamento por nombre o presentación"
                        >
                    </div>
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-outline-secondary med-clear d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-x-lg"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card med-card">
        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table align-middle med-table mb-0">
                    <thead>
                        <tr>
                            <th>MEDICAMENTO</th>
                            <th>PRINCIPIO ACTIVO</th>
                            <th>PRESENTACIÓN</th>
                            <th>CONCENTRACIÓN</th>
                            <th>UNIDAD</th>
                            <th>ESTADO</th>
                            <th>ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicamentos as $medicamento)
                            <tr>
                                <td class="fw-semibold">{{ $medicamento->nombre }}</td>
                                <td>{{ $medicamento->principio_activo ?? '—' }}</td>
                                <td>{{ $medicamento->presentacion }}</td>
                                <td>{{ $medicamento->concentracion ?? '—' }}</td>
                                <td>{{ $medicamento->unidad_medida ?? '—' }}</td>
                                <td>
                                    @if($medicamento->activo)
                                        <span class="badge rounded-pill text-bg-success">Activo</span>
                                    @else
                                        <span class="badge rounded-pill text-bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('medicamentos.edit', $medicamento) }}" class="med-edit">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-capsule fs-1 d-block mb-3"></i>
                                    @if($buscar !== '')
                                        No se encontraron medicamentos para “{{ $buscar }}”.
                                    @else
                                        No hay medicamentos registrados.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($medicamentos->hasPages() || $medicamentos->total() > 0)
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4 med-pagination">
            <div class="small text-muted">
                @if($medicamentos->total() > 0)
                    Mostrando {{ $medicamentos->firstItem() }}–{{ $medicamentos->lastItem() }} de {{ $medicamentos->total() }} medicamentos
                @endif
            </div>
            @if ($medicamentos->hasPages())
                <div class="d-flex justify-content-end">
                    <nav aria-label="Paginación de medicamentos">
                        <ul class="pagination mb-0">

                            {{-- Anterior --}}
                            @if ($medicamentos->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                    href="{{ $medicamentos->previousPageUrl() }}"
                                    aria-label="Página anterior">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Páginas --}}
                            @foreach ($medicamentos->getUrlRange(1, $medicamentos->lastPage()) as $pagina => $url)
                                <li class="page-item {{ $pagina == $medicamentos->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">
                                        {{ $pagina }}
                                    </a>
                                </li>
                            @endforeach

                            {{-- Siguiente --}}
                            @if ($medicamentos->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link"
                                    href="{{ $medicamentos->nextPageUrl() }}"
                                    aria-label="Página siguiente">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            @endif

                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('medicamentoSearchForm');
    const input = document.getElementById('buscarMedicamento');
    let timer;
    let valorInicial = input.value.trim();

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            const actual = input.value.trim();
            if (actual !== valorInicial) {
                form.submit();
            }
        }, 450);
    });
});
</script>
@endsection
