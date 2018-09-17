<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Relation;

class BelongsTo extends Relation
{
    public function onChange()
    {
        return $this;
    }

    public function getFormattedValue($value = null)
    {
        if (isset($value)) {
            return $value->title();
        }

        return '';
    }


    public function getForm($resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->field;
        $extraParam['class'] = $extraParam['class'] . ' custom-select';
        $value = is_null($resource->{$this->getField()}) ? null : $resource->{$this->getField()}->getKey();

        if ($this->hasOnChange()) {
            $route = \Route::currentRouteName();
            list($routeName, $routeAction) = explode('.', $route);

            $elemDest = $this->onChange;
            $url = route($routeName.'.ajaxOnChange', ['modelName' => $elemDest]);
            $extraParam['onchange'] = "$('#{$elemDest}').html('');"
                ."$.get('{$url}?{$this->field}='+$('#{$this->field}').val(), "
                ."function (data) { $('#{$elemDest}').html(data); });";
        }

        return Form::select(
            $this->field,
            $this->getRelationResourceOptions($resource, $this->getField(), $this->relationConditions),
            $value,
            $extraParam
        );
    }

}
