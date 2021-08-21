<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\Metrics\Metric;

trait DisplayAsCard
{
    public string $width = '1/3';

    protected array $bootstrapWidths = [
        '1/2' => 'col-span-6',
        '1/3' => 'col-span-4',
        '2/3' => 'col-span-8',
        'full' => 'col-span-12',
    ];

    /**
     * Genera la vista de la tarjeta
     *
     * @param Request $request
     * @return HtmlString
     */
    public function render(Request $request): HtmlString
    {
        return new HtmlString(
            view('orm.card', [
                'card' => $this,
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
        return $this->bootstrapWidths[$this->width] ?? '';
    }

    /**
     * Devuelve el titulo de la tarjeta
     *
     * @return string
     */
    public function title(): string
    {
        return Str::of(class_basename($this))->snake()->replace('_', ' ')->title();
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
        return Str::camel($this->title());
    }

    public function urlRoute(Request $request): string
    {
        [$prefixRouteName, $routeName] = explode('.', $request->route()->getName());

        return route("{$prefixRouteName}.ajaxCard");
    }
}
