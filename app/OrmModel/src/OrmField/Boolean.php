<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

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
        $statusStyle = $this->value
            ? "text-green-500"
            : "text-red-500";

        return new HtmlString("<small><span class=\"fa fa-circle {$statusStyle}\"></span></small>");
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  string[] $extraParam
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
