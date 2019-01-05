<?php

namespace App\Gastos;

use Carbon\Carbon;
use App\Acl\Usuario;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = [
        'cuenta_id', 'anno', 'mes', 'fecha', 'glosa', 'serie', 'tipo_gasto_id', 'monto', 'tipo_movimiento_id', 'usuario_id',
    ];

    protected $dates = [
        'fecha'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_gastos';
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function tipoGasto()
    {
        return $this->belongsTo(TipoGasto::class);
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function movimientosMes(Request $request)
    {
        return $this->whereCuentaId($request->cuenta_id)
            ->whereAnno($request->anno)
            ->whereMes($request->mes)
            ->orderBy('fecha', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function movimientosAnno(Request $request)
    {
         return $this->where('cuenta_id', $request->cuenta_id)
            ->where('anno', $request->anno)
            ->where('tipo_movimiento_id', '<>', 4) // excluye movimientos de saldos
            ->orderBy('fecha', 'asc')
            ->get();
    }

    public function saldos(Request $request)
    {
         return $this->where('cuenta_id', $request->cuenta_id)
            ->where('anno', $request->anno)
            ->where('tipo_movimiento_id', 4)
            ->orderBy('fecha', 'asc')
            ->get();
    }

    public function getTotalMes(Request $request)
    {
        return $this->movimientosMes($request)
            ->map(function($gasto) {
                return $gasto->monto * $gasto->tipoMovimiento->signo;
            })
            ->sum();
    }

    protected function getDataReporte(Request $request)
    {
        return $this
            ->select(DB::raw('mes, tipo_gasto_id, sum(monto) as sum_monto'))
            ->where('cuenta_id', $request->cuenta_id)
            ->where('anno', $request->anno)
            ->where('tipo_movimiento_id', $request->tipo_movimiento_id)
            ->groupBy(['mes', 'tipo_gasto_id'])
            ->get();
    }

    protected function getSumMesDataReporte($request)
    {
         return $this
            ->select(DB::raw('mes, sum(monto) as sum_monto'))
            ->where('cuenta_id', $request->cuenta_id)
            ->where('anno', $request->anno)
            ->where('tipo_movimiento_id', $request->tipo_movimiento_id)
            ->groupBy(['mes'])
            ->get();
    }

    public function getReporte(Request $request)
    {
        $data = $this->getDataReporte($request);

        $tipo_gasto_id = $data->pluck('tipo_gasto_id')->unique()->all();

        $datos = [];
        $data->each(function($gasto) use (&$datos) {
            $datos[$gasto->tipo_gasto_id][$gasto->mes] = $gasto->sum_monto;
        });

        $sum_tipo_gasto = collect($datos)->map(function($dato) {
            return collect($dato)->sum();
        })->all();

        $sum_mes = [];
        collect($this->getSumMesDataReporte($request))->each(function($gasto) use(&$sum_mes) {
            $sum_mes[$gasto->mes] = $gasto->sum_monto;
        });

        return [
            'datos' => $datos,
            'meses' => collect(range(1,12))->mapWithKeys(function($mes) {
                return [$mes => Carbon::create(2000, $mes, 1)->format('M')];
            }),
            'tipo_gasto_id' => $tipo_gasto_id,
            'sum_tipo_gasto' => $sum_tipo_gasto,
            'sum_mes' => $sum_mes,
        ];
    }
}
