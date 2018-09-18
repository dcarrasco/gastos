<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Field;

class Select extends Field
{
    public function getFormattedValue($model = null)
    {
        $value = $model->{$this->getField()};

        if ($this->hasChoices()) {
            return array_get($this->choices, $value, '');
        }

        return $value;
    }


    public function getForm($resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->field;
        $extraParam['class'] = $extraParam['class'] . ' custom-select';
        $value = $resource->{$this->getField()};

        return Form::select($this->field, $this->choices, $value, $extraParam);
    }

}
