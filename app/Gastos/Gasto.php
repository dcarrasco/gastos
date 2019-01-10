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

    public function scopeCuentaAnno($query, $cuentaId, $anno)
    {
        return $query->where('cuenta_id', $cuentaId)
            ->where('anno', $anno);
    }


    public function scopeCuentaAnnoTipMov($query, $cuentaId, $anno, $tipoMovimientoId)
    {
        return $query->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->where('tipo_movimiento_id', $tipoMovimientoId);
    }

    public function movimientosMes(Request $request)
    {
        return $this->cuentaAnno($request->cuenta_id, $request->anno)
            ->whereMes($request->mes)
            ->orderBy('fecha', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function movimientosAnno(Request $request)
    {
        return $this->cuentaAnno($request->cuenta_id, $request->anno)
            ->where('tipo_movimiento_id', '<>', 4) // excluye movimientos de saldos
            ->orderBy('fecha', 'asc')
            ->get();
    }

    public function saldos(Request $request)
    {
        return $this->cuentaAnnoTipMov($request->cuenta_id, $request->anno, 4)
            ->orderBy('fecha', 'asc')
            ->get();
    }

    public function getTotalMes(Request $request)
    {
        return $this->movimientosMes($request)
            ->map(function($gasto) {
                return $gasto->monto * $gasto->tipoMovimiento->signo;
            })->sum();
    }

    protected function getDataReporte(Request $request)
    {
        return $this->cuentaAnnoTipMov($request->cuenta_id, $request->anno, $request->tipo_movimiento_id)
            ->select(DB::raw('mes, tipo_gasto_id, sum(monto) as sum_monto'))
            ->groupBy(['mes', 'tipo_gasto_id'])
            ->get();
    }

    protected function getSumDataReporte(Request $request, string $campo)
    {
        return $this->cuentaAnnoTipMov($request->cuenta_id, $request->anno, $request->tipo_movimiento_id)
            ->select(DB::raw($campo.', sum(monto) as sum_monto'))
            ->groupBy([$campo])
            ->get()
            ->pluck('sum_monto', $campo);
    }

    public function getReporte(Request $request)
    {
        $data = $this->getDataReporte($request);
        $tipo_gasto_id = $data->pluck('tipo_gasto_id')->unique()->all();

        $datos = collect($tipo_gasto_id)->combine($tipo_gasto_id)
            ->map(function($tipo_gasto_id) use ($data) {
               return $data->where('tipo_gasto_id', $tipo_gasto_id)->pluck('sum_monto', 'mes')->all();
            })->all();

        $meses = (new Cuenta)->getFormMes('M');
        $sum_tipo_gasto = $this->getSumDataReporte($request, 'tipo_gasto_id');
        $sum_mes = $this->getSumDataReporte($request, 'mes');

        return compact ('datos', 'meses', 'tipo_gasto_id', 'sum_tipo_gasto', 'sum_mes');
    }
}
