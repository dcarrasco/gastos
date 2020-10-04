<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\Metrics\Metric;

trait DisplayAsCard
{
    public $width = '1/3';
    protected $bootstrapWidths = [
        '1/2' => 'col-span-6',
        '1/3' => 'col-span-4',
        '2/3' => 'col-span-8',
        'full' => 'col-span-12',
    ];

    /**
     * Genera la vista de la tarjeta
     *
     * @param Request $request
     * @return void
     */
    public function render(Request $request): HtmlString
    {
        return new HtmlString(
            view('orm.card', [
                'card' => $this,
                'urlRoute' => $this->urlRoute($request),
                'resourceParams' => new HtmlString(json_encode($request->query())),
                'request' => $request,
            ])->render()
        );
    }

    /**
     * Devuelve el ancho de la tarjeta tipo bootstrap
     *
     * @return string
     */
    public function bootstrapCardWidth(): string
    {
        return Arr::get($this->bootstrapWidths, $this->width, '');
    }

    /**
     * Devuelve el titulo de la tarjeta
     *
     * @return string
     */
    public function title(): string
    {
        return Str::title(str_replace('_', ' ', Str::snake(class_basename($this))));
    }

    /**
     * Fija el ancho de la tarjeta
     *
     * @param  string $width
     * @return Metric
     */
    public function width(string $width = ''): Metric
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Genera el ID unico de la tarjeta
     *
     * @return string
     */
    public function cardId(): string
    {
        return hash('md5', get_class($this));
    }

    public function urlRoute(Request $request): string
    {
        [$prefixRouteName, $routeName] = explode('.', $request->route()->getName());

        return route("{$prefixRouteName}.ajaxCard");
    }
}
