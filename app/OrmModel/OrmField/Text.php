<?php

namespace App\OrmModel\OrmField;

use Form;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;

class Text extends Field
{
    public function getForm(Request $request, $resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->field;
        $extraParam['maxlength'] = $this->getFieldLength();

        if ($resource->getModelObject()->getKeyName() === $this->getField() && !is_null($resource->getModelObject()->getKey())) {
            $extraParam['readonly'] = 'readonly';
        }
        $value = $resource->getModelObject()->{$this->getField()};

        return Form::text($this->field, $value, $extraParam);
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
