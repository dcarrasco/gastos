<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait DisplayAsCard
{
    protected string $title = '';

    /**
     * Ancho de la tarjeta
     *
     * @var string
     */
    public string $width = '1/3';

    /**
     * Clases para fijar anchos
     *
     * @var string[]
     */
    protected array $bootstrapWidths = [
        '1/2' => 'col-span-6',
        '1/3' => 'col-span-4',
        '2/3' => 'col-span-8',
        'full' => 'col-span-12',
    ];

    /**
     * Genera la vista de la tarjeta
     *
     * @param  Request  $request
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
        return empty($this->title)
            ? Str::of(class_basename($this))->snake()->replace('_', ' ')->title()
            : $this->title;
    }

    public function setTitle(string $newTitle): static
    {
        $this->title = $newTitle;

        return $this;
    }

    /**
     * Fija el ancho de la tarjeta
     *
     * @param  string  $width
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

    /**
     * Genera la ruta de la tarjeta
     *
     * @param  Request  $request
     * @return string
     */
    public function urlRoute(Request $request): string
    {
        [$prefixRouteName, $routeName] = explode('.', $request->route()->getName());

        return route("{$prefixRouteName}.ajaxCard");
    }

    /**
     * Devuelve arreglo para actualizar metrica
     *
     * @param  Request  $request
     * @return string[]
     */
    public function contentAjaxRequest(Request $request): array
    {
        return [];
    }
}
