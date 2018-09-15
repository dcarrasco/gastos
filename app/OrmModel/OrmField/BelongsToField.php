<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\RelationField;

class BelongsToField extends RelationField
{
    public function onChange()
    {
        return $this;
    }

    public function getFormattedValue($value = null)
    {
        if ($this->hasChoices()) {
            return array_get($this->getChoices(), $value, '');
        }

        return $value;
    }


    public function getForm($value = null, $extraParam = [], $parentId = null)
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

        dump($this);
        $options = [];

        return Form::select($this->name, $options, $value, $extraParam);
    }

}
