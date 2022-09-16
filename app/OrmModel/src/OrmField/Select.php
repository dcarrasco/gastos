<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class Select extends Field
{
    /** @var string[] */
    protected array $choices = [];

    /**
     * Fija opciones para tipo de campo Select
     *
     * @param  string[]  $options
     * @return Select
     */
    public function options(array $options = []): Select
    {
        $this->choices = $options;

        return $this;
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        if ($this->hasChoices()) {
            return new HtmlString(Arr::get($this->choices, $this->value, ''));
        }

        return new HtmlString($this->value);
    }

    /**
     * Indica si el campo tiene opciones
     *
     * @return bool
     */
    public function hasChoices(): bool
    {
        return count($this->choices) > 0;
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
            'type' => 'select',
            'name' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute),
            'id' => $this->attribute,
            'options' => $this->choices,
            'placeholder' => '&mdash;',
        ], $extraParam);
    }
}
