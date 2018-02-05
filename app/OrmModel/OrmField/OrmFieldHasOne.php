<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class OrmFieldHasOne extends OrmField
{
    public function getLabel()
    {
        return $this->getRelatedModel()->modelLabel;
    }

    public function getValidation()
    {
        $validation = [];

        if ($this->esObligatorio) {
            $validation[] = 'required';
        }

        return collect($validation)->implode('|');
    }

    public function getFormattedValue($value = null)
    {
        return (string) $this->getRelatedModel()->find($value);
    }

    public function getForm($value = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;

        if ($this->hasOnChange()) {
            $route = \Route::currentRouteName();
            list($routeName, $routeAction) = explode('.', $route);

            $elemDest = $this->onChange;
            $url = route($routeName.'.ajaxOnChange', ['modelName' => $elemDest]);
            $extraParam['onchange'] = "$('#{$elemDest}').html('');"
                ."$.get('{$url}?{$this->name}='+$('#{$this->name}').val(), "
                ."function (data) { $('#{$elemDest}').html(data); });";
        }

        return Form::select(
            $this->name,
            $this->getRelatedModel()->getModelFormOptions(),
            $value,
            $extraParam
        );
    }
}
