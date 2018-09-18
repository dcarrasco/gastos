<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Field;

class Text extends Field
{
    public function getForm($resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->field;
        $extraParam['maxlength'] = $this->getFieldLength();

        if ($resource->getKeyName() === $this->getField() && !is_null($resource->getKey())) {
            $extraParam['readonly'] = 'readonly';
        }
        $value = $resource->{$this->getField()};

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
