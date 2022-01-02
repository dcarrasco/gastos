<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

class Currency extends Field
{
    protected string $alignOnList = 'text-right';

    protected string $currencySign = '$';


    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        return fmtMonto($this->value);
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
            'type' => 'number',
            'name' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute),
            'id' => $this->attribute,
        ])->render());
    }
}
