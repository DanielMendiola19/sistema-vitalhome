<aside class="sidebar">

{{-- =====================================================
     LOGO
     ===================================================== --}}

<div class="sidebar-logo">

    <img
        src="{{ asset('images/logo-vitalhome.png') }}"
        alt="VITALHOME"
    >

</div>


{{-- =====================================================
     MENÚ
     ===================================================== --}}

<nav>

    {{-- DASHBOARD --}}

    <a
        href="{{ route('dashboard') }}"
        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
    >

        <i class="bi bi-house"></i>

        <span>
            Dashboard
        </span>

    </a>


    {{-- PACIENTES --}}

    <a
        href="{{ route('pacientes.index') }}"
        class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}"
    >

        <i class="bi bi-people"></i>

        <span>
            Pacientes
        </span>

    </a>


    {{-- MEDICAMENTOS --}}

    @if (Auth::user()->rol === 'administrador')

        <a
            href="{{ route('medicamentos.index') }}"
            class="nav-link {{ request()->routeIs('medicamentos.*') ? 'active' : '' }}"
        >

            <i class="bi bi-capsule"></i>

            <span>
                Medicamentos
            </span>

        </a>

    @endif


    {{-- TRATAMIENTOS / KARDEX --}}

    <a
        href="{{ route('tratamientos_kardex.lista') }}"
        class="nav-link {{ request()->routeIs('tratamientos_kardex.*') ? 'active' : '' }}"
    >

        <i class="bi bi-clipboard2"></i>

        <span>
            Tratamientos/Kardex
        </span>

    </a>


    {{-- INVENTARIO --}}

    <a
        href="{{ route('inventario.index') }}"
        class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}"
    >

        <i class="bi bi-box-seam"></i>

        <span>
            Inventario
        </span>

    </a>


    {{-- =====================================================
         OPCIONES SOLO ADMINISTRADOR
         ===================================================== --}}

    @if (Auth::user()->rol === 'administrador')

        {{-- REPORTES --}}

        <a
            href="{{ route('reportes.index') }}"
            class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}"
        >

            <i class="bi bi-bar-chart"></i>

            <span>
                Reportes
            </span>

        </a>


        {{-- USUARIOS --}}

        <a
            href="{{ route('usuarios.index') }}"
            class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
        >

            <i class="bi bi-person-gear"></i>

            <span>
                Usuarios
            </span>

        </a>


        {{-- CONFIGURACIÓN --}}

        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-gear"></i>

            <span>
                Configuración
            </span>

        </a>

    @endif

</nav>


{{-- =====================================================
     USUARIO
     ===================================================== --}}

<div class="sidebar-user">

    <div class="sidebar-user-info">

        <div class="user-avatar">

            {{ strtoupper(
                substr(Auth::user()->nombre, 0, 1) .
                substr(Auth::user()->apellido, 0, 1)
            ) }}

        </div>

        <div>

            <div class="fw-semibold">

                {{ Auth::user()->nombre }}
                {{ Auth::user()->apellido }}

            </div>

            <small class="text-secondary-vital">

                {{ ucfirst(Auth::user()->rol) }}

            </small>

        </div>

    </div>


    {{-- CERRAR SESIÓN --}}

    <form
        action="{{ route('logout') }}"
        method="POST"
    >

        @csrf

        <button
            type="submit"
            class="logout-btn"
        >

            <i class="bi bi-box-arrow-right"></i>

            Cerrar sesión

        </button>

    </form>

</div>

</aside>
