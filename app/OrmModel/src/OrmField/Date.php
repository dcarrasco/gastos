<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

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
     * @param  Resource $resource
     * @param  string[] $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        return new HtmlString(view('orm.form-input', [
            'type' => 'date',
            'name' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute)->format('Y-m-d'),
            'id' => $this->attribute,
        ])->render());
    }
}
