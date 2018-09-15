<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class IdField extends OrmField
{
    public function __construct($name = '', $field = '')
    {
        $name = empty($name) ? 'id' : $name;
        $this->esIncrementing = true;

        parent::__construct($name, $field);
    }

    public function getFormattedValue($value = null)
    {
        if ($this->hasChoices()) {
            return array_get($this->getChoices(), $value, '');
        }

        return $value;
    }


    public function getForm($value = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->name;

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
