<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Field;

class Number extends Field
{
    public function getFormattedValue($value = null)
    {
        return $value;
    }


    public function getForm($resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->name;
        $value = $resource->{$this->getField()};

        if ($this->hasChoices()) {
            return Form::select(
                $this->name,
                array_get($this->choices, $value, ''),
                $value,
                $extraParam
            );
        }

        return Form::number($this->name, $value, $extraParam);
    }

}
