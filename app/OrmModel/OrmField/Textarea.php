<?php

namespace App\OrmModel\OrmField;

use Form;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;

class Textarea extends Field
{
    public function getForm(Request $request, $resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->field;
        $extraParam['rows'] = 5;
        $extraParam['maxlength'] = $this->getFieldLength();
        $value = $resource->getModelObject()->{$this->getField()};

        return Form::textarea($this->field, $value, $extraParam);
    }

    protected function getFieldLength()
    {
        $maxRule = collect($this->rules)
            ->first(function($rule) {
                return strpos($rule, 'max:') !== false;
            });

        return substr($maxRule, strpos($maxRule, ':') + 1, strlen($maxRule));
    }
}
