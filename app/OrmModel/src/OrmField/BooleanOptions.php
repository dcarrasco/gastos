<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class BooleanOptions extends Field
{
    /** @var string[] */
    protected array $options = [];

    protected string $relationName = '';

    protected string $modelId = '';

    /**
     * Fija opciones para tipo de campo Select
     *
     * @param  string[]  $options
     * @return BooleanOptions
     */
    public function options(array $options = []): BooleanOptions
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Devuelve arreglo de opciones
     *
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Indica si la relacion contiene el atributo indicado
     *
     * @param  string  $option
     * @return bool
     */
    protected function hasOption(string $option): bool
    {
        return collect(json_decode($this->value))
            ->contains($option);
    }

    /**
     * Fija el nombre de la relacion
     *
     * @param  string  $relationName
     * @return BooleanOptions
     */
    public function setRelationName(string $relationName): BooleanOptions
    {
        $this->relationName = $relationName;

        return $this;
    }

    /**
     * Fija el id del modelo padre
     *
     * @param  string  $modelId
     * @return BooleanOptions
     */
    public function setModelId(string $modelId): BooleanOptions
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        return new HtmlString($this->getOptionsValues());
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
        return new HtmlString($this->getOptionsValues(true));
    }

    /**
     * Devuelve texto de representacion de todas las opciones
     *
     * @param  bool  $isEditable
     * @return string
     */
    protected function getOptionsValues(bool $isEditable = false): string
    {
        return collect($this->options)
            ->map(fn ($option) => $this->getFormattedOptionValue($option, $isEditable))
            ->implode('');
    }

    /**
     * Devuelve texto de representacion de una opcion
     *
     * @param  string  $option
     * @param  bool  $isEditable
     * @return string
     */
    protected function getFormattedOptionValue(string $option, bool $isEditable = false): string
    {
        $selected = $this->hasOption($option) ? 'checked' : '';

        $editable = $isEditable ? '' : 'disabled';

        $input = '<input type="checkbox" '
            ."name=\"attributes:{$this->relationName}:{$this->modelId}[]\" "
            ."value=\"{$option}\" "
            ."{$selected} {$editable}"
            .'>';

        return "<td class=\"text-center\">{$input}</td>";
    }
}
