<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

abstract class Metric
{
    use DisplayAsCard;


    /**
     * Genera rango de fechas para realizar consultas
     *
     * @param  Request $request
     * @return Array
     */
    protected function currentRange(Request $request): array
    {
        $range = $request->input('range', collect($this->ranges())->keys()->first());

        if ($range == 'MTD') {
            return [now()->startOfMonth(), now()];
        }
        if ($range == 'QTD') {
            return [now()->firstOfQuarter(), now()];
        }
        if ($range == 'YTD') {
            return [now()->startOfYear(), now()];
        }
        if ($range == 'CURR_MONTH') {
            return [now()->startOfMonth(), now()->endOfMonth()];
        }
        if ($range == 'LAST_MONTH') {
            return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
        }

        return [now()->subDays($range - 1), now()];
    }

    /**
     * Devuelve el intervalo de fechas del periodo anterior
     *
     * @param  array $dateInterval
     * @return array
     */
    protected function previousRange(Request $request): array
    {
        $range = $request->input('range', collect($this->ranges())->keys()->first());

        if ($range == 'MTD') {
            return [now()->subMonth()->startOfMonth(), now()->subMonth()];
        }
        if ($range == 'QTD') {
            return [now()->subQuarter()->firstOfQuarter(), now()->subQuarter()];
        }
        if ($range == 'YTD') {
            return [now()->subYear()->startOfYear(), now()->subYear()];
        }
        if ($range == 'CURR_MONTH') {
            return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
        }
        if ($range == 'LAST_MONTH') {
            return [now()->subMonth()->subMonth()->startOfMonth(), now()->subMonth()->subMonth()->endOfMonth()];
        }

        return [now()->subDays($range * 2), now()->subDays($range)];
    }

    /**
     * Inicializa query y agrega condiciones de rango fechas
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @param  array   $dateInterval
     * @return Builder
     */
    protected function rangedQuery(Request $request, string $resource, string $timeColumn, array $dateInterval): Builder
    {
        return $this->newQuery($request, $resource)->whereBetween($timeColumn, $dateInterval);
    }

    /**
     * Devuelve una nueva query con todos los filtros iniciales aplicados
     *
     * @param  Request $request
     * @param  string  $resource
     * @return Builder
     */
    protected function newQuery(Request $request, string $resource): Builder
    {
        $query = $this->newResourceObject($resource)->applyFilters($request)
            ->getModelQueryBuilder();

        return $this->filter($request, $query);
    }

    /**
     * Crea nuevo objeto Resource
     *
     * @param  string $resource
     * @return Resource
     */
    protected function newResourceObject(string $resource): Resource
    {
        return new $resource;
    }

    /**
     * Filtros adicionales para la query de la metrica
     * @param  Request $request
     * @param  Builder $query
     * @return Builder
     */
    protected function filter(Request $request, Builder $query): Builder
    {
        return $query;
    }

    /**
     * Devuelve HTML con contenido de la metrica
     *
     * @param  Request $request
     * @return HtmlString
     */
    public function content(Request $request): HtmlString
    {
        return new HtmlString(view('orm.metrics.metric_content', [
            'cardId' => $this->cardId(),
            'script' => $this->contentScript($request),
        ])->render());
    }

    /**
     * Devuelve script para dibujar valores
     *
     * @param  Request $request
     * @return HtmlString
     */
    public function contentScript(Request $request): HtmlString
    {
        return new HtmlString('');
    }

    /**
     * Devuelve arreglo con rangos a mostrar en card
     *
     * @return array
     */
    public function ranges(): array
    {
        return [];
    }

    /**
     * Genera identificador URI de la metrica
     *
     * @return string
     */
    public function uriKey(): string
    {
        return Str::slug($this->title());
    }

    /**
     * Compara identificador URI con string
     *
     * @return boolean
     */
    public function hasUriKey(string $uriKey): bool
    {
        return $this->uriKey() == $uriKey;
    }
}
