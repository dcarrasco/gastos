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
     * Item unitario para form
     * @param  string $name  Nombre del elemento
     * @param  string $value Valor del elemento
     * @param  string $type  Tipo del elemento
     * @return string
     */
    protected function formRadioItem(string $name, string $value, string $type = 'yes'): string
    {
        $radioValue = $type == 'yes' ? 1 : 0;
        $checked = $type == 'yes' ? ($value == '1') : ($value != '1');
        $label = $type == 'yes' ? trans('orm.radio_yes') : trans('orm.radio_no');

        $checkedAttribute = $checked ? 'checked' : '';

        $id = "id_{$name}_{$radioValue}";
        $form = "<input type=\"radio\" name=\"{$name}\" value=\"{$radioValue}\" {$checkedAttribute} id=\"{$id}\">";

        $classDiv = '';
        $classLabel = 'px-2';

        return "<div class=\"{$classDiv}\">{$form}<label class=\"{$classLabel}\" for=\"{$id}\">{$label}</label></div>";
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $value = (string) $resource->model()->getAttribute($this->attribute);

        return new HtmlString(
            $this->formRadioItem($this->attribute, $value, 'yes')
            . $this->formRadioItem($this->attribute, $value, 'no')
        );
    }
}
