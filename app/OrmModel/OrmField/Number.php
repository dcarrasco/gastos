<?php

namespace App\OrmModel\OrmField;

use Form;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;

class Number extends Field
{
    public function getForm(Request $request, $resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->field;
        $value = $resource->getModelObject()->{$this->getField()};

        if ($this->hasChoices()) {
            return Form::select(
                $this->field,
                array_get($this->choices, $value, ''),
                $value,
                $extraParam
            );
        }

        return Form::number($this->field, $value, $extraParam);
    }

}
