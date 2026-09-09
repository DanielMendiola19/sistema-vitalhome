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

        <a
            href="{{ route('dashboard') }}"
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >

            <i class="bi bi-house"></i>

            <span>
                Dashboard
            </span>

        </a>


        <a
            href="{{ route('pacientes.index') }}"
            class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}"
        >

            <i class="bi bi-people"></i>

            <span>
                Pacientes
            </span>

        </a>

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


        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-clipboard2"></i>

            <span>
                Kardex
            </span>

        </a>


        <a
            href="{{ route('inventario.index') }}"
            class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}"
        >
            <i class="bi bi-box-seam"></i>

            <span>
                Inventario
            </span>
        </a>


        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-file-earmark-text"></i>

            <span>
                Tratamientos
            </span>

        </a>


        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-exclamation-triangle"></i>

            <span>
                Reposición
            </span>

        </a>


        @if (Auth::user()->rol === 'administrador')

            {{-- =====================================================
                REPORTES
                ===================================================== --}}

            <a
                href="#"
                class="nav-link"
            >

                <i class="bi bi-bar-chart"></i>

                <span>
                    Reportes
                </span>

            </a>


            {{-- =====================================================
                USUARIOS
                ===================================================== --}}

            <a
                href="{{ route('usuarios.index') }}"
                class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
            >

                <i class="bi bi-person-gear"></i>

                <span>
                    Usuarios
                </span>

            </a>


            {{-- =====================================================
                CONFIGURACIÓN
                ===================================================== --}}

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

        <form action="{{ route('logout') }}" method="POST">
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
