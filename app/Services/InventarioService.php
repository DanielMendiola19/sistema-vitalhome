<?php

namespace App\Services;

use App\Models\Inventario;
use App\Models\InventarioPaciente;
use App\Models\Medicamento;
use App\Models\MovimientoInventario;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class InventarioService
{
    /**
     * Registrar una entrada de medicamento al inventario general.
     *
     * Si el medicamento ya tiene inventario:
     * - aumenta el stock existente.
     *
     * Si todavía no tiene inventario:
     * - crea el registro de inventario.
     */
    public function registrarEntrada(
        Medicamento $medicamento,
        int $cantidad,
        ?string $motivo = null,
        ?string $lote = null,
        ?string $fechaVencimiento = null
    ): Inventario {
        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        if (!$medicamento->activo) {
            throw new RuntimeException(
                'No se puede registrar una entrada para un medicamento inactivo.'
            );
        }

        return DB::transaction(function () use (
            $medicamento,
            $cantidad,
            $motivo,
            $lote,
            $fechaVencimiento
        ) {

            $inventario = Inventario::query()
                ->where('medicamento_id', $medicamento->id)
                ->lockForUpdate()
                ->first();

            /*
             * Si el medicamento todavía no tiene inventario,
             * creamos su registro inicial.
             */
            if (!$inventario) {

                $inventario = Inventario::create([
                    'medicamento_id' => $medicamento->id,
                    'cantidad_actual' => 0,
                    'cantidad_minima' => 0,
                    'cantidad_maxima' => null,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'lote' => $lote,
                    'ubicacion' => null,
                    'estado' => 'disponible',
                ]);
            }

            $stockAnterior = (int) $inventario->cantidad_actual;

            $inventario->cantidad_actual =
                $stockAnterior + $cantidad;

            /*
             * Si la entrada proporciona lote o vencimiento,
             * actualizamos esos datos del inventario.
             */
            if ($fechaVencimiento) {
                $inventario->fecha_vencimiento =
                    $fechaVencimiento;
            }

            if ($lote) {
                $inventario->lote = $lote;
            }

            $inventario->estado = $this->determinarEstado(
                $inventario->cantidad_actual,
                $inventario->cantidad_minima
            );

            $inventario->save();

            /*
             * Registramos el movimiento de entrada.
             */
            MovimientoInventario::create([
                'inventario_id' => $inventario->id,
                'inventario_paciente_id' => null,
                'paciente_id' => null,
                'usuario_id' => auth()->id(),
                'fecha' => now(),
                'tipo_movimiento' => 'entrada',
                'grupo_movimiento' => (string) Str::uuid(),
                'cantidad' => $cantidad,
                'motivo' => $motivo,
                'observaciones' => null,
            ]);

            return $inventario->fresh([
                'medicamento',
            ]);
        });
    }

    /**
     * Registrar salida del inventario general.
     */
    public function registrarSalidaGeneral(
        Inventario $inventario,
        int $cantidad,
        ?string $motivo = null
    ): Inventario {
        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        return DB::transaction(function () use (
            $inventario,
            $cantidad,
            $motivo
        ) {

            $inventario = Inventario::query()
                ->whereKey($inventario->id)
                ->lockForUpdate()
                ->firstOrFail();

            $stockAnterior =
                (int) $inventario->cantidad_actual;

            if ($cantidad > $stockAnterior) {
                throw new RuntimeException(
                    'No hay suficiente stock disponible para realizar la salida.'
                );
            }

            $inventario->cantidad_actual =
                $stockAnterior - $cantidad;

            $inventario->estado =
                $this->determinarEstado(
                    $inventario->cantidad_actual,
                    $inventario->cantidad_minima
                );

            $inventario->save();

            MovimientoInventario::create([
                'inventario_id' => $inventario->id,
                'inventario_paciente_id' => null,
                'paciente_id' => null,
                'usuario_id' => auth()->id(),
                'fecha' => now(),
                'tipo_movimiento' => 'salida',
                'grupo_movimiento' => (string) Str::uuid(),
                'cantidad' => $cantidad,
                'motivo' => $motivo
            ]);

            return $inventario->fresh([
                'medicamento',
            ]);
        });
    }

    /**
     * Registrar salida del inventario de un paciente.
     */
   public function registrarSalidaPaciente(
        InventarioPaciente $inventarioPaciente,
        int $cantidad,
        ?string $motivo = null
    ): InventarioPaciente {
        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        return DB::transaction(function () use (
            $inventarioPaciente,
            $cantidad,
            $motivo
        ) {
            $inventarioPaciente =
                InventarioPaciente::query()
                    ->whereKey($inventarioPaciente->id)
                    ->lockForUpdate()
                    ->firstOrFail();

            $stockAnterior =
                (int) $inventarioPaciente->cantidad_actual;

            if ($cantidad > $stockAnterior) {
                throw new RuntimeException(
                    'El paciente no tiene suficiente stock para realizar esta salida.'
                );
            }

            $inventarioPaciente->cantidad_actual =
                $stockAnterior - $cantidad;

            $inventarioPaciente->estado =
                $this->determinarEstado(
                    $inventarioPaciente->cantidad_actual,
                    $inventarioPaciente->cantidad_minima
                );

            $inventarioPaciente->save();

            MovimientoInventario::create([
                'inventario_id' => null,
                'inventario_paciente_id' =>
                    $inventarioPaciente->id,
                'paciente_id' =>
                    $inventarioPaciente->paciente_id,
                'usuario_id' => auth()->id(),
                'fecha' => now(),
                'tipo_movimiento' => 'salida',
                'grupo_movimiento' => (string) Str::uuid(),
                'cantidad' => $cantidad,
                'motivo' => $motivo,
                'observaciones' => null,
            ]);

            return $inventarioPaciente->fresh([
                'medicamento',
                'paciente',
            ]);
        });
    }

    /**
     * Transferir medicamento del inventario general
     * al inventario individual de un paciente.
     */
    public function transferirAPaciente(
        Inventario $inventarioGeneral,
        Paciente $paciente,
        int $cantidad,
        ?string $motivo = null,
        ?string $observaciones = null
    ): array {
        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        return DB::transaction(function () use (
            $inventarioGeneral,
            $paciente,
            $cantidad,
            $motivo,
            $observaciones
        ) {

            $inventarioGeneral =
                Inventario::query()
                    ->whereKey($inventarioGeneral->id)
                    ->lockForUpdate()
                    ->firstOrFail();

            $stockGeneral =
                (int) $inventarioGeneral->cantidad_actual;

            if ($cantidad > $stockGeneral) {
                throw new RuntimeException(
                    'No existe suficiente stock en el inventario general.'
                );
            }

            $grupoMovimiento =
                (string) Str::uuid();

            /*
             * Descontar del inventario general.
             */
            $inventarioGeneral->cantidad_actual =
                $stockGeneral - $cantidad;

            $inventarioGeneral->estado =
                $this->determinarEstado(
                    $inventarioGeneral->cantidad_actual,
                    $inventarioGeneral->cantidad_minima
                );

            $inventarioGeneral->save();

            /*
             * Buscar el inventario del medicamento
             * correspondiente al paciente.
             */
            $inventarioPaciente =
                InventarioPaciente::query()
                    ->where(
                        'paciente_id',
                        $paciente->id
                    )
                    ->where(
                        'medicamento_id',
                        $inventarioGeneral->medicamento_id
                    )
                    ->lockForUpdate()
                    ->first();

            /*
             * Si el paciente todavía no tiene ese
             * medicamento, se crea.
             */
            if (!$inventarioPaciente) {

                $inventarioPaciente =
                    InventarioPaciente::create([
                        'paciente_id' => $paciente->id,
                        'medicamento_id' =>
                            $inventarioGeneral->medicamento_id,
                        'cantidad_actual' => 0,
                        'cantidad_minima' =>
                            $inventarioGeneral->cantidad_minima,
                        'fecha_vencimiento' =>
                            $inventarioGeneral->fecha_vencimiento,
                        'lote' =>
                            $inventarioGeneral->lote,
                        'ubicacion' => null,
                        'estado' => 'disponible',
                    ]);
            }

            $stockPaciente =
                (int) $inventarioPaciente->cantidad_actual;

            $inventarioPaciente->cantidad_actual =
                $stockPaciente + $cantidad;

            if (
                !$inventarioPaciente->fecha_vencimiento
                &&
                $inventarioGeneral->fecha_vencimiento
            ) {
                $inventarioPaciente->fecha_vencimiento =
                    $inventarioGeneral->fecha_vencimiento;
            }

            if (
                !$inventarioPaciente->lote
                &&
                $inventarioGeneral->lote
            ) {
                $inventarioPaciente->lote =
                    $inventarioGeneral->lote;
            }

            $inventarioPaciente->estado =
                $this->determinarEstado(
                    $inventarioPaciente->cantidad_actual,
                    $inventarioPaciente->cantidad_minima
                );

            $inventarioPaciente->save();

            /*
             * Movimiento de salida del inventario general.
             */
            MovimientoInventario::create([
                'inventario_id' =>
                    $inventarioGeneral->id,
                'inventario_paciente_id' => null,
                'paciente_id' =>
                    $paciente->id,
                'usuario_id' => auth()->id(),
                'fecha' => now(),
                'tipo_movimiento' =>
                    'transferencia',
                'grupo_movimiento' =>
                    $grupoMovimiento,
                'cantidad' => $cantidad,
                'motivo' => $motivo,
                'observaciones' =>
                    $observaciones,
            ]);

            /*
             * Movimiento de entrada al inventario
             * individual del paciente.
             */
            MovimientoInventario::create([
                'inventario_id' => null,
                'inventario_paciente_id' =>
                    $inventarioPaciente->id,
                'paciente_id' =>
                    $paciente->id,
                'usuario_id' => auth()->id(),
                'fecha' => now(),
                'tipo_movimiento' =>
                    'transferencia',
                'grupo_movimiento' =>
                    $grupoMovimiento,
                'cantidad' => $cantidad,
                'motivo' => $motivo,
                'observaciones' =>
                    $observaciones,
            ]);

            return [
                'general' =>
                    $inventarioGeneral->fresh([
                        'medicamento',
                    ]),

                'paciente' =>
                    $inventarioPaciente->fresh([
                        'medicamento',
                        'paciente',
                    ]),
            ];
        });
    }

    public function registrarInventarioPaciente(
        Paciente $paciente,
        Medicamento $medicamento,
        int $cantidad,
        int $cantidadMinima = 0,
        ?string $fechaVencimiento = null,
        ?string $lote = null
    ): InventarioPaciente {
        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        if (!$medicamento->activo) {
            throw new RuntimeException(
                'No se puede registrar un medicamento inactivo.'
            );
        }

        return DB::transaction(function () use (
            $paciente,
            $medicamento,
            $cantidad,
            $cantidadMinima,
            $fechaVencimiento,
            $lote
        ) {
            $inventarioPaciente =
                InventarioPaciente::query()
                    ->where('paciente_id', $paciente->id)
                    ->where('medicamento_id', $medicamento->id)
                    ->lockForUpdate()
                    ->first();

            if ($inventarioPaciente) {
                $inventarioPaciente->cantidad_actual += $cantidad;

                if ($fechaVencimiento) {
                    $inventarioPaciente->fecha_vencimiento =
                        $fechaVencimiento;
                }

                if ($lote) {
                    $inventarioPaciente->lote = $lote;
                }

                $inventarioPaciente->cantidad_minima =
                    $cantidadMinima;

            } else {
                $inventarioPaciente =
                    new InventarioPaciente();

                $inventarioPaciente->paciente_id =
                    $paciente->id;

                $inventarioPaciente->medicamento_id =
                    $medicamento->id;

                $inventarioPaciente->cantidad_actual =
                    $cantidad;

                $inventarioPaciente->cantidad_minima =
                    $cantidadMinima;

                $inventarioPaciente->fecha_vencimiento =
                    $fechaVencimiento;

                $inventarioPaciente->lote =
                    $lote;

                $inventarioPaciente->ubicacion = null;
            }

            $inventarioPaciente->estado =
                $this->determinarEstado(
                    $inventarioPaciente->cantidad_actual,
                    $inventarioPaciente->cantidad_minima
                );

           $inventarioPaciente->save();

            MovimientoInventario::create([
                'inventario_id' => null,
                'inventario_paciente_id' => $inventarioPaciente->id,
                'paciente_id' => $paciente->id,
                'usuario_id' => auth()->id(),
                'fecha' => now(),
                'tipo_movimiento' => 'entrada',
                'grupo_movimiento' => (string) Str::uuid(),
                'cantidad' => $cantidad,
                'motivo' => 'Registro directo en paciente',
                'observaciones' => null,
            ]);

            return $inventarioPaciente->fresh([
                'medicamento',
                'paciente',
            ]);
        });
    }

    /**
     * Determinar el estado del inventario.
     */
    public function determinarEstado(
        int $cantidad,
        int $minimo
    ): string {

        if ($cantidad <= 0) {
            return 'agotado';
        }

        if ($cantidad <= $minimo) {
            return 'stock_bajo';
        }

        return 'disponible';
    }
}
