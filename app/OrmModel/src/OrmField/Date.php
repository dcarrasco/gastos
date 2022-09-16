<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class Date extends Field
{
    public string $inputDateFormat = 'Y-m-d';

    public string $outputDateFormat = 'Y-m-d';

    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        return new HtmlString(optional($this->value)->format($this->outputDateFormat) ?: '');
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
        return $this->renderForm([
            'type' => 'date',
            'name' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute)->format($this->inputDateFormat),
            'id' => $this->attribute,
        ], $extraParam);
    }
}
