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
            class="nav-link active"
        >

            <i class="bi bi-house"></i>

            <span>
                Dashboard
            </span>

        </a>


        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-people"></i>

            <span>
                Pacientes
            </span>

        </a>


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
            href="#"
            class="nav-link"
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


        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-bar-chart"></i>

            <span>
                Reportes
            </span>

        </a>


        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-gear"></i>

            <span>
                Configuración
            </span>

        </a>

    </nav>


    {{-- =====================================================
         USUARIO
         ===================================================== --}}

    <div class="sidebar-user">

        <div class="sidebar-user-info">

            <div class="user-avatar">
                DM
            </div>


            <div>

                <div class="sidebar-user-name">
                    Daniel Mendiola
                </div>

                <div class="sidebar-user-role">
                    Administrador
                </div>

            </div>

        </div>


        <button
            type="button"
            class="logout-btn"
        >

            <i class="bi bi-box-arrow-right"></i>

            Cerrar sesión

        </button>

    </div>

</aside>
