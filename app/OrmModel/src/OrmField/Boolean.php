<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class Boolean extends Field
{
    protected string $alignOnList = 'text-center';

    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        $statusSymbol = $this->value
            ? view("components.heroicon.check-circle", ['class' => 'text-green-500 inline-block'])->render()
            : view("components.heroicon.x-circle", ['class' => 'text-red-500 inline-block'])->render();

        return new HtmlString($statusSymbol);
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  resource  $resource
     * @param  string[]  $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $value = (string) $resource->model()->getAttribute($this->attribute);

        return $this->renderForm([
            'type' => 'boolean',
            'name' => $this->attribute,
            'id' => $this->attribute,
            'value' => $value,
        ], $extraParam);
    }
}
