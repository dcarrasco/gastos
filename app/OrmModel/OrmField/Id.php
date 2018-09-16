<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Field;

class Id extends Field
{
    public function __construct($name = '', $field = '')
    {
        $name = empty($name) ? 'id' : $name;
        $this->esIncrementing = true;
        $this->showOnDetail = false;

        parent::__construct($name, $field);
    }

    public function getFormattedValue($value = null)
    {
        if ($this->hasChoices()) {
            return array_get($this->getChoices(), $value, '');
        }

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

        if ($this->esIncrementing) {
            return '<p class="form-control-static">'.$value.'</p>'
                .Form::hidden($this->name, null, $extraParam);
        }

        return Form::text($this->name, $value, $extraParam);
    }

}
