<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

class BooleanOptions extends Field
{

    protected $options = [];
    protected $relationName = '';
    protected $modelId = '';

    /**
     * Fija opciones para tipo de campo Select
     *
     * @param  array  $options
     * @return Field
     */
    public function options(array $options = []): BooleanOptions
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Indica si la relacion contiene el atributo indicado
     *
     * @param  Resource $resource
     * @param  string   $pivotRelation
     * @param  string   $option
     * @return bool
     */
    protected function hasOption(string $option): bool
    {
        return collect(json_decode($this->value))
            ->contains($option);
    }

    public function setRelationName(string $relationName): BooleanOptions
    {
        $this->relationName = $relationName;

        return $this;
    }

    public function setModelId(string $modelId): BooleanOptions
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(): HtmlString
    {
        $formattedValue = collect($this->options)
            ->map(fn($option) => $this->getFormattedOptionValue($option))
            ->implode('');

        return new HtmlString($formattedValue);
    }

    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $formValue = collect($this->options)
            ->map(fn($option) => $this->getFormattedOptionValue($option, true))
            ->implode('');

        return new HtmlString($formValue);
    }

    protected function getFormattedOptionValue(string $option, bool $edit = false): string
    {
        $selected = $this->hasOption($option)
            ? 'checked'
            : '';

        $editable = $edit ? '' : 'disabled';

        $input = "<input type=\"checkbox\" "
            . "name=\"attributes:{$this->relationName}:{$this->modelId}[]\" "
            . "value=\"{$option}\" "
            . "{$selected} {$editable}"
            . ">";

        return "<td class=\"text-center\">{$input}</td>";
    }
}
