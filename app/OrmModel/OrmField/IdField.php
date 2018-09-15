<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class IdField extends OrmField
{
    public function __construct($name = '', $field = '')
    {
        $name = empty($name) ? 'id' : $name;

        parent::__construct($name, $field);
    }

    public function getValidation()
    {
        $validation = [];

        if ($this->esObligatorio) {
            $validation[] = 'required';
        }

        if ($this->tipo === OrmField::TIPO_INT) {
            $validation[] = 'integer';
        }

        return collect($validation)->implode('|');
    }

    public function getFormattedValue($value = null)
    {
        if ($this->hasChoices()) {
            return array_get($this->getChoices(), $value, '');
        }

        return $value;
    }


    public function getForm($value = null, $parentId = null, $extraParam = [])
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

        if ($this->esId and $this->esIncrementing) {
            return '<p class="form-control-static">'.$value.'</p>'
                .Form::hidden($this->name, null, $extraParam);
        }

        return Form::text($this->name, $value, $extraParam);
    }

}
