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
        '1/2' => 'col-md-6',
        '1/3' => 'col-md-4',
        '2/3' => 'col-md-8',
        'full' => 'col-md-12',
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
            view('orm.components.card', [
                'content' => $this->content($request),
                'contentScript' => $this->contentScript($request),
                'cardWidth' => $this->bootstrapCardWidth(),
                'title' => $this->title(),
                'cardId' => $this->cardId(),
                'ranges' => $this->ranges(),
                'uriKey' => $this->uriKey(),
                'resource' => $request->segment(2),
            ])->render()
        );
    }

    /**
     * Devuelve el ancho de la tarjeta tipo bootstrap
     *
     * @return string
     */
    protected function bootstrapCardWidth(): string
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
    protected function cardId(): string
    {
        return spl_object_hash($this);
    }
}
