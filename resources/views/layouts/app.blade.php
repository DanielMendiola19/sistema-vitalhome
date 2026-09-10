<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

@if(!($modoPdf ?? false))

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

@endif


<title>
    @yield('title', 'VITALHOME | Sistema de Gestión')
</title>


@if(!($modoPdf ?? false))

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- CSS y JS de Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

@endif


@yield('head')

</head>


<body>


{{-- =========================================================
     MODO PDF

     DomPDF solo recibe el contenido del reporte.
     No carga sidebar, Vite, menú móvil ni estructura web.
     ========================================================= --}}

@if($modoPdf ?? false)

    @yield('content')


{{-- =========================================================
     MODO NORMAL DEL SISTEMA
     ========================================================= --}}

@else


    @if(Auth::check())

        {{-- =====================================================
             ESTRUCTURA PARA USUARIOS AUTENTICADOS
             ===================================================== --}}

        <div class="app-container">


            {{-- SIDEBAR DESKTOP --}}

            <div class="desktop-sidebar">

                @include('layouts.sidebar')

            </div>


            {{-- CONTENIDO PRINCIPAL --}}

            <main class="main-content">


                {{-- HEADER MÓVIL --}}

                <div class="mobile-header">

                    <button
                        class="menu-toggle"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#sidebarMobile"
                        aria-controls="sidebarMobile"
                        aria-label="Abrir menú"
                    >

                        <i class="bi bi-list"></i>

                    </button>

                </div>


                {{-- CONTENIDO DE CADA PÁGINA --}}

                @yield('content')


            </main>

        </div>


        {{-- =====================================================
             SIDEBAR MÓVIL
             ===================================================== --}}

        <div
            class="offcanvas offcanvas-start"
            tabindex="-1"
            id="sidebarMobile"
            aria-labelledby="sidebarMobileLabel"
        >

            <div class="offcanvas-header">

                <h5
                    class="offcanvas-title"
                    id="sidebarMobileLabel"
                >
                    Menú
                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Cerrar"
                >
                </button>

            </div>


            <div class="offcanvas-body p-0">

                @include('layouts.sidebar')

            </div>

        </div>


    @else


        {{-- =====================================================
             ESTRUCTURA PARA PÁGINAS PÚBLICAS
             ===================================================== --}}

        @yield('content')


    @endif


@endif


@if(!($modoPdf ?? false))

    @yield('scripts')

@endif


</body>

</html>
