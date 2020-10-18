<?php

namespace App\OrmModel\src\OrmField;

use Form;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Boolean extends Field
{
    protected $alignOnList = 'text-center';

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request): HtmlString
    {
        $statusStyle = $model->{$this->attribute} ? "text-green-500" : "text-red-500";

        return new HtmlString("<small><span class=\"fa fa-circle {$statusStyle}\"></span></small>");
    }

    /**
     * Item unitario para form
     * @param  string $name  Nombre del elemento
     * @param  int    $value Valor del elemento
     * @param  string $type  Tipo del elemento
     * @return string
     */
    protected function formRadioItem(string $name, $value, $type = 'yes'): string
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
        $value = $resource->model()->{$this->attribute};

        return new HtmlString(
            $this->formRadioItem($this->attribute, $value, 'yes')
            . $this->formRadioItem($this->attribute, $value, 'no')
        );
    }
}
