<?php

namespace App\Toa;
use App\Toa\ControlesToaData;
use App\Toa\TecnicoToa;
use App\Stock\ClaseMovimiento;

class ControlesToa
{

    use ControlesToaData;

    public static $unidadesConsumo = [
        'peticiones' => 'Cantidad de peticiones',
        'unidades'   => 'Suma de unidades',
        'monto'      => 'Suma de montos',
    ];

    public static $selectDato = [
            'unidades'   => 'cant',
            'monto'      => 'monto',
            'peticiones' => '1',
        ];


    protected static function getDiasMes($anomes = null)
    {
        $mes = (int) substr($anomes, 4, 2);
        $ano = (int) substr($anomes, 0, 4);

         return collect(range(1, cal_days_in_month(CAL_GREGORIAN, $mes, $ano)))
            ->mapWithKeys(function ($item) {
                return [$item => null];
            });
    }

    private static function getFechaDesdeHasta($anomes = null)
    {
        $mes = (int) substr($anomes, 4, 2);
        $ano = (int) substr($anomes, 0, 4);

        return [
            $anomes.'01',
            (string) (($mes === 12) ? ($ano+1)*10000+(1)*100+1 : $ano*10000+($mes+1)*100+1),
        ];
    }

    public static function getUnidadesConsumo($tipo)
    {
        $unidades = static::$unidadesConsumo;

        if ($tipo === 'materiales') {
            unset($unidades['peticiones']);
        }

        return $unidades;
    }

    public static function controlTecnicos($request)
    {
        list($fechaDesde, $fechaHasta) = static::getFechaDesdeHasta($request->input('mes'));

        $diasMes = static::getDiasMes($request->input('mes'));
        $datos   = static::getControlTecnicosData($request->input('empresa'), $fechaDesde, $fechaHasta, $request->input('filtro_trx'), static::$selectDato[$request->input('dato', 'peticiones')]);

        return \DB::table(\DB::raw(config('invfija.bd_tecnicos_toa').' as t'))
            ->where('t.id_empresa', $request->input('empresa'))
            ->leftJoin(\DB::raw(config('invfija.bd_ciudades_toa').' as c'), 't.id_ciudad', '=', 'c.id_ciudad')
            ->get()
            ->mapWithKeys(function ($tecnico) use ($diasMes, $datos) {
                $tecnicoId = $tecnico->id_tecnico;

                $datosTecnico = $datos->filter(function ($dato) use ($tecnicoId) {
                    return $dato->tecnico === $tecnicoId;
                })->mapWithKeys(function ($dato) {
                    return [(int) substr($dato->fecha, 8, 2) => $dato->dato];
                })->all();

                return [
                    $tecnicoId => [
                        'ciudad'      => $tecnico->ciudad,
                        'ordenCiudad' => $tecnico->orden,
                        'tecnico'     => $tecnicoId.' - '.$tecnico->tecnico.' ('.fmt_rut($tecnico->rut).')',
                        'actuaciones' => $diasMes->map(function ($elem, $key) use ($datosTecnico) {
                            return array_get($datosTecnico, $key);
                        })->all(),
                        'datosTecnico' => $datosTecnico,
                    ],
                ];
            })->sort(function ($tecnico1, $tecnico2) {
                return $tecnico1['ordenCiudad'].$tecnico1['ciudad'].$tecnico1['tecnico'] > $tecnico2['ordenCiudad'].$tecnico2['ciudad'].$tecnico2['tecnico'];
            })->filter(function ($tecnico) {
                return count($tecnico['datosTecnico']) > 0;
            })->all();
    }

    public static function controlTecnicosCampos()
    {
        return [
            'ciudad'  => ['label' => 'Ciudad', 'class' => ''],
            'tecnico' => ['label' => 'T&eacute;cnico', 'class' => ''],
        ];
    }

    public static function controlMateriales($request)
    {
        $filtroTrx = $request->input('filtro_trx') === '000' ? ClaseMovimiento::transaccionesConsumoToa() : [$request->input('filtro_trx')];

        list($fechaDesde, $fechaHasta) = static::getFechaDesdeHasta($request->input('mes'));

        $diasMes = static::getDiasMes($request->input('mes'));
        $datos = static::getControlMaterialesData($request->input('empresa'), $fechaDesde, $fechaHasta, $filtroTrx, $request->input('dato'));

        return $datos->mapWithKeys(function ($elem) use ($diasMes, $datos) {
            $material = $elem->material;
            $datosMateriales = $datos->filter(function ($dato) use ($material) {
                return $dato->material === $material;
            })->mapWithKeys(function ($dato) {
                return [(int) substr($dato->fecha_contabilizacion, 8, 2) => $dato->dato];
            })->all();

            return [$elem->material => [
                'tipo'        => $elem->desc_tip_material,
                'material'    => $elem->material.' - '.$elem->descripcion,
                'unidad'      => $elem->ume,
                'actuaciones' => $diasMes->map(function ($elemActuacion, $keyActuacion) use ($datosMateriales) {
                    return array_get($datosMateriales, $keyActuacion);
                })->all(),
            ]];
        })->sort(function ($material1, $material2) {
            return $material1['tipo'].$material1['material'] > $material2['tipo'].$material2['material'];
        });
    }

    public static function controlMaterialesCampos()
    {
        return [
            'tipo'     => ['label' => 'Tipos', 'class' => ''],
            'material' => ['label' => 'Material', 'class' => ''],
            'unidad'   => ['label' => 'Unidad', 'class' => 'text-center'],
        ];
    }
}
