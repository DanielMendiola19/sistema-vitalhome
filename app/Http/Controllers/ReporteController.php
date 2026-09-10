<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReporteController extends Controller
{
    /**
     * Listado general de pacientes
     * disponibles para reportes.
     */
    public function index(Request $request)
    {
        $buscar = trim((string) $request->input('buscar'));

        $pacientes = Paciente::query()
            ->when($buscar !== '', function ($query) use ($buscar) {

                $query->where(function ($subquery) use ($buscar) {

                    $subquery
                        ->where('nombre', 'ILIKE', "%{$buscar}%")
                        ->orWhere('apellido', 'ILIKE', "%{$buscar}%")
                        ->orWhere('ci', 'ILIKE', "%{$buscar}%")
                        ->orWhereRaw(
                            "CONCAT(nombre, ' ', apellido) ILIKE ?",
                            ["%{$buscar}%"]
                        );

                });

            })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view(
            'reportes.index',
            compact(
                'pacientes',
                'buscar'
            )
        );
    }


    /**
     * Centro de reportes de un paciente.
     */
    public function paciente(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        return view(
            'reportes.paciente',
            compact(
                'paciente',
                'periodo',
                'desde',
                'hasta'
            )
        );
    }


    /**
     * Reporte de información general.
     */
    public function informacionGeneral(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $modoPdf = false;

        return view(
            'reportes.informacion_general',
            compact(
                'paciente',
                'periodo',
                'desde',
                'hasta',
                'modoPdf'
            )
        );
    }


    /**
     * Descargar información general como PDF.
     *
     * Utiliza la MISMA vista:
     * reportes.informacion_general
     */
    public function informacionGeneralPdf(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $modoPdf = true;

        $logoBase64 = $this->obtenerLogoBase64();

        $nombreArchivo = $this->generarNombreArchivo(
            'Informacion_General',
            $paciente
        );

        $pdf = Pdf::loadView(
            'reportes.informacion_general',
            compact(
                'paciente',
                'periodo',
                'desde',
                'hasta',
                'modoPdf',
                'logoBase64'
            )
        );

        $pdf->setPaper(
            'letter',
            'portrait'
        );

        return $pdf->download(
            $nombreArchivo
        );
    }


    /**
     * Reporte de Kardex / Tratamientos.
     */
    public function kardex(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $tratamientos = $this->obtenerTratamientos(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = false;

        return view(
            'reportes.kardex',
            compact(
                'paciente',
                'tratamientos',
                'periodo',
                'desde',
                'hasta',
                'modoPdf'
            )
        );
    }


    /**
     * Descargar Kardex / Tratamientos como PDF.
     */
    public function kardexPdf(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $tratamientos = $this->obtenerTratamientos(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = true;

        $logoBase64 = $this->obtenerLogoBase64();

        $nombreArchivo = $this->generarNombreArchivo(
            'Kardex',
            $paciente
        );

        $pdf = Pdf::loadView(
            'reportes.kardex',
            compact(
                'paciente',
                'tratamientos',
                'periodo',
                'desde',
                'hasta',
                'modoPdf',
                'logoBase64'
            )
        );

        $pdf->setPaper(
            'letter',
            'portrait'
        );

        return $pdf->download(
            $nombreArchivo
        );
    }


    /**
     * Reporte de Historia Clínica.
     */
    public function historiaClinica(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $observaciones = $this->obtenerObservaciones(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = false;

        return view(
            'reportes.historia_clinica',
            compact(
                'paciente',
                'observaciones',
                'periodo',
                'desde',
                'hasta',
                'modoPdf'
            )
        );
    }


    /**
     * Descargar Historia Clínica como PDF.
     *
     * Utiliza la MISMA vista:
     * reportes.historia_clinica
     */
    public function historiaClinicaPdf(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $observaciones = $this->obtenerObservaciones(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = true;

        $logoBase64 = $this->obtenerLogoBase64();

        $nombreArchivo = $this->generarNombreArchivo(
            'Historia_Clinica',
            $paciente
        );

        $pdf = Pdf::loadView(
            'reportes.historia_clinica',
            compact(
                'paciente',
                'observaciones',
                'periodo',
                'desde',
                'hasta',
                'modoPdf',
                'logoBase64'
            )
        );

        $pdf->setPaper(
            'letter',
            'portrait'
        );

        return $pdf->download(
            $nombreArchivo
        );
    }


    /**
     * Reporte de Signos Vitales.
     */
    public function signosVitales(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $signosVitales = $this->obtenerSignosVitales(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = false;

        return view(
            'reportes.signos_vitales',
            compact(
                'paciente',
                'signosVitales',
                'periodo',
                'desde',
                'hasta',
                'modoPdf'
            )
        );
    }


    /**
     * Descargar Signos Vitales como PDF.
     *
     * Utiliza la MISMA vista:
     * reportes.signos_vitales
     */
    public function signosVitalesPdf(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $signosVitales = $this->obtenerSignosVitales(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = true;

        $logoBase64 = $this->obtenerLogoBase64();

        $nombreArchivo = $this->generarNombreArchivo(
            'Signos_Vitales',
            $paciente
        );

        $pdf = Pdf::loadView(
            'reportes.signos_vitales',
            compact(
                'paciente',
                'signosVitales',
                'periodo',
                'desde',
                'hasta',
                'modoPdf',
                'logoBase64'
            )
        );

        $pdf->setPaper(
            'letter',
            'portrait'
        );

        return $pdf->download(
            $nombreArchivo
        );
    }


    /**
     * Reporte general.
     *
     * Consolida:
     * - Información general
     * - Kardex / Tratamientos
     * - Historia clínica
     * - Signos vitales
     */
    public function general(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $tratamientos = $this->obtenerTratamientos(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $observaciones = $this->obtenerObservaciones(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $signosVitales = $this->obtenerSignosVitales(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = false;

        return view(
            'reportes.general',
            compact(
                'paciente',
                'tratamientos',
                'observaciones',
                'signosVitales',
                'periodo',
                'desde',
                'hasta',
                'modoPdf'
            )
        );
    }


    /**
     * Descargar Reporte General como PDF.
     *
     * Utiliza la MISMA vista:
     * reportes.general
     */
    public function generalPdf(
        Request $request,
        Paciente $paciente
    ) {
        [$periodo, $desde, $hasta] =
            $this->resolverPeriodo($request);

        $tratamientos = $this->obtenerTratamientos(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $observaciones = $this->obtenerObservaciones(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $signosVitales = $this->obtenerSignosVitales(
            $paciente,
            $periodo,
            $desde,
            $hasta
        );

        $modoPdf = true;

        $logoBase64 = $this->obtenerLogoBase64();

        $nombreArchivo = $this->generarNombreArchivo(
            'Reporte_General',
            $paciente
        );

        $pdf = Pdf::loadView(
            'reportes.general',
            compact(
                'paciente',
                'tratamientos',
                'observaciones',
                'signosVitales',
                'periodo',
                'desde',
                'hasta',
                'modoPdf',
                'logoBase64'
            )
        );

        $pdf->setPaper(
            'letter',
            'portrait'
        );

        return $pdf->download(
            $nombreArchivo
        );
    }


    /**
     * Obtiene tratamientos según el período.
     */
    private function obtenerTratamientos(
        Paciente $paciente,
        string $periodo,
        ?string $desde,
        ?string $hasta
    ) {
        return $paciente
            ->tratamientos()
            ->with('medicamento')
            ->when(
                $periodo === 'rango',
                function ($query) use ($desde, $hasta) {

                    $query
                        ->whereDate(
                            'fecha_inicio',
                            '<=',
                            $hasta
                        )
                        ->where(function ($subquery) use ($desde) {

                            $subquery
                                ->whereNull('fecha_fin')
                                ->orWhereDate(
                                    'fecha_fin',
                                    '>=',
                                    $desde
                                );

                        });

                }
            )
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->get();
    }


    /**
     * Obtiene observaciones clínicas según el período.
     */
    private function obtenerObservaciones(
        Paciente $paciente,
        string $periodo,
        ?string $desde,
        ?string $hasta
    ) {
        return $paciente
            ->observacionesClinicas()
            ->with('usuario')
            ->when(
                $periodo === 'rango',
                function ($query) use ($desde, $hasta) {

                    $inicio = Carbon::parse(
                        $desde
                    )->startOfDay();

                    $fin = Carbon::parse(
                        $hasta
                    )->endOfDay();

                    $query->whereBetween(
                        'created_at',
                        [
                            $inicio,
                            $fin,
                        ]
                    );

                }
            )
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }


    /**
     * Obtiene signos vitales según el período.
     */
    private function obtenerSignosVitales(
        Paciente $paciente,
        string $periodo,
        ?string $desde,
        ?string $hasta
    ) {
        return $paciente
            ->signosVitales()
            ->when(
                $periodo === 'rango',
                function ($query) use ($desde, $hasta) {

                    $inicio = Carbon::parse(
                        $desde
                    )->startOfDay();

                    $fin = Carbon::parse(
                        $hasta
                    )->endOfDay();

                    $query->whereBetween(
                        'fecha_registro',
                        [
                            $inicio,
                            $fin,
                        ]
                    );

                }
            )
            ->orderByDesc('fecha_registro')
            ->orderByDesc('id')
            ->get();
    }


    /**
     * Convierte el logo institucional a Base64
     * para que DomPDF pueda mostrarlo.
     */
    private function obtenerLogoBase64(): ?string
    {
        $logoPath = public_path(
            'images/logo-vitalhome.png'
        );

        if (!file_exists($logoPath)) {
            return null;
        }

        $tipoImagen = pathinfo(
            $logoPath,
            PATHINFO_EXTENSION
        );

        return
            'data:image/' .
            $tipoImagen .
            ';base64,' .
            base64_encode(
                file_get_contents($logoPath)
            );
    }


    /**
     * Genera el nombre del archivo PDF.
     */
    private function generarNombreArchivo(
        string $tipoReporte,
        Paciente $paciente
    ): string {
        $nombrePaciente = trim(
            $paciente->nombre .
            ' ' .
            $paciente->apellido
        );

        $nombreSeguro = preg_replace(
            '/[^A-Za-z0-9_\-]/',
            '_',
            str_replace(
                ' ',
                '_',
                $nombrePaciente
            )
        );

        return
            'VitalHome_' .
            $tipoReporte .
            '_' .
            $nombreSeguro .
            '_' .
            now()->format('Ymd') .
            '.pdf';
    }


    /**
     * Valida y normaliza el período solicitado.
     */
    private function resolverPeriodo(
        Request $request
    ): array {
        $periodo = $request->input(
            'periodo',
            'todo'
        );

        $desde = $request->input(
            'desde'
        );

        $hasta = $request->input(
            'hasta'
        );


        if ($periodo === 'rango') {

            $request->validate([
                'desde' => [
                    'required',
                    'date',
                ],

                'hasta' => [
                    'required',
                    'date',
                    'after_or_equal:desde',
                ],
            ], [
                'desde.required' =>
                    'Debe seleccionar la fecha inicial del reporte.',

                'desde.date' =>
                    'La fecha inicial no es válida.',

                'hasta.required' =>
                    'Debe seleccionar la fecha final del reporte.',

                'hasta.date' =>
                    'La fecha final no es válida.',

                'hasta.after_or_equal' =>
                    'La fecha final debe ser igual o posterior a la fecha inicial.',
            ]);

        } else {

            $periodo = 'todo';

            $desde = null;

            $hasta = null;

        }


        return [
            $periodo,
            $desde,
            $hasta,
        ];
    }
}
