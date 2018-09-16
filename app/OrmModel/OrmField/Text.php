<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Field;

class Text extends Field
{
    public function getFormattedValue($value = null)
    {
        if ($this->hasChoices()) {
            return array_get($this->choices, $value, '');
        }

        return $value;
    }


    public function getForm($resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->field;
        $extraParam['maxlength'] = $this->getFieldLength();

        $value = $resource->{$this->getField()};

        if ($this->hasChoices()) {
            return Form::select($this->field, $this->choices, $value, $extraParam);
        }

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
