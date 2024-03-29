<?php

namespace App\OrmModel\src\Metrics;

use App\OrmModel\src\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

abstract class Metric
{
    use DisplayAsCard;

    protected const MONTH_TO_DATE = 'MTD';

    protected const QUARTER_TO_DATE = 'QTD';

    protected const YEAR_TO_DATE = 'YTD';

    protected const CURRENT_MONTH = 'CURR_MONTH';

    protected const LAST_MONTH = 'LAST_MONTH';

    final public function __construct()
    {
    }

    /**
     * Devuelve nueva instancia
     *
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Genera rango de fechas para realizar consultas
     *
     * @param  Request  $request
     * @return mixed[]
     */
    protected function currentRange(Request $request): array
    {
        $range = $request->input('range', collect($this->ranges())->keys()->first());

        $ranges = collect([
            Metric::MONTH_TO_DATE => [now()->startOfMonth(), now()],
            Metric::QUARTER_TO_DATE => [now()->firstOfQuarter(), now()],
            Metric::YEAR_TO_DATE => [now()->startOfYear(), now()],
            Metric::CURRENT_MONTH => [now()->startOfMonth(), now()->endOfMonth()],
            Metric::LAST_MONTH => [
                now()->modify('first day of last month')->startOfMonth(),
                now()->modify('first day of last month')->endOfMonth(),
            ],
        ]);

        if ($ranges->has($range)) {
            return $ranges->get($range);
        }

        return [now()->subDays($range - 1), now()];
    }

    /**
     * Devuelve el intervalo de fechas del periodo anterior
     *
     * @param  Request  $request
     * @return mixed[]
     */
    protected function previousRange(Request $request): array
    {
        $range = $request->input('range', collect($this->ranges())->keys()->first());

        $ranges = collect([
            Metric::MONTH_TO_DATE => [
                now()->modify('first day of last month')->startOfMonth(),
                now()->modify('first day of last month')->month == now()->subMonth()->month
                    ? now()->subMonth()
                    : now()->modify('first day of last month')->endOfMonth(),
            ],
            Metric::QUARTER_TO_DATE => [now()->subQuarter()->firstOfQuarter(), now()->subQuarter()],
            Metric::YEAR_TO_DATE => [now()->subYear()->startOfYear(), now()->subYear()],
            Metric::CURRENT_MONTH => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            Metric::LAST_MONTH => [
                now()->modify('first day of last month')->subMonth()->startOfMonth(),
                now()->modify('first day of last month')->subMonth()->endOfMonth(),
            ],
        ]);

        if ($ranges->has($range)) {
            return $ranges->get($range);
        }

        return [now()->subDays((int) $range * 2 - 1), now()->subDays($range)];
    }

    /**
     * Inicializa query y agrega condiciones de rango fechas
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @param  mixed[]  $dateInterval
     * @return Builder<Model>
     */
    protected function rangedQuery(Request $request, string $resource, string $timeColumn, array $dateInterval): Builder
    {
        return $this->newQuery($request, $resource)->whereBetween($timeColumn, $dateInterval);
    }

    /**
     * Devuelve una nueva query con todos los filtros iniciales aplicados
     *
     * @param  Request  $request
     * @param  string  $resource
     * @return Builder<Model>
     */
    protected function newQuery(Request $request, string $resource): Builder
    {
        $query = $this->newResource($resource)
            ->applyFilters($request)
            ->getModelQueryBuilder();

        return $this->filter($request, $query);
    }

    /**
     * Crea nuevo objeto Resource
     *
     * @param  string  $resource
     * @return resource
     */
    protected function newResource(string $resource): Resource
    {
        /** @var resource */
        $newResource = new $resource();

        return $newResource;
    }

    /**
     * Crea objeto Model
     *
     * @param  string  $resource
     * @return Model
     */
    protected function getModel(string $resource): Model
    {
        return $this->newResource($resource)->model();
    }

    /**
     * Filtros adicionales para la query de la metrica
     *
     * @param  Request  $request
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    protected function filter(Request $request, Builder $query): Builder
    {
        return $query;
    }

    /**
     * Devuelve HTML con contenido de la metrica
     *
     * @param  Request  $request
     * @return HtmlString
     */
    public function content(Request $request): HtmlString
    {
        return new HtmlString(
            view('orm.metrics.metric_content', [
                'cardId' => $this->cardId(),
                'baseUrl' => asset(''),
                'script' => $this->contentScript($request),
            ])
            ->render()
        );
    }

    /**
     * Devuelve arreglo para actualizar metrica
     *
     * @param  Request  $request
     * @return string[]
     */
    public function ajaxRequest(Request $request): array
    {
        return [];
    }

    /**
     * Devuelve script para dibujar valores
     *
     * @param  Request  $request
     * @return HtmlString
     */
    public function contentScript(Request $request): HtmlString
    {
        return new HtmlString('');
    }

    /**
     * Devuelve arreglo con rangos a mostrar en card
     *
     * @return mixed[]
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
     * @param  string  $uriKey
     * @return bool
     */
    public function hasUriKey(string $uriKey): bool
    {
        return $this->uriKey() == $uriKey;
    }
}
