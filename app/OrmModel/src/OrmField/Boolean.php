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
        $icon = $this->value ? 'check-circle' : 'x-circle';
        $color = $this->value ? 'text-green-500' : 'text-red-500';

        return new HtmlString(
            view("components.heroicon.{$icon}", ['class' => "{$color} inline-block"])->render()
        );
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
