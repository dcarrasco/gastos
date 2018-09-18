<?php

namespace App\OrmModel\OrmField;

use Form;
use Illuminate\Support\Str;
use App\OrmModel\OrmField\Relation;

class BelongsTo extends Relation
{
    public function onChange()
    {
        return $this;
    }

    public function getField($resource = null)
    {
        if (is_null($resource)) {
            return $this->field;
        }

        $relationName = $this->field;
        return $resource->{$relationName}()->getForeignKey();
    }

    public function getFormattedValue($model = null)
    {
        $relatedModel = $model->{$this->getField()};
        $related = (new $this->relatedOrm)->injectModel($relatedModel);

        return $related->title();
    }


    public function getForm($resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->getField($resource);
        $extraParam['class'] = $extraParam['class'] . ' custom-select';

        $field = $this->getField($resource);
        $value = $resource->{$field};
        $foreignKey = $resource->{$this->getField()}()->getForeignKey();

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
            $foreignKey,
            $this->getRelationResourceOptions($resource, $this->getField(), $this->relationConditions),
            $value,
            $extraParam
        );
    }

}
