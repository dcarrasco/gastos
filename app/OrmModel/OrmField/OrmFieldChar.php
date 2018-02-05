<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class OrmFieldChar extends OrmField
{
    public function getValidation()
    {
        $validation = [];

        if ($this->esObligatorio) {
            $validation[] = 'required';
        }

        if ($this->largo) {
            $validation[] = 'max:'.$this->largo;
        }

        return collect($validation)->implode('|');
    }

    public function getFormattedValue($value = null)
    {
        if ($this->hasChoices()) {
            return array_get($this->choices, $value, '');
        }

        return $value;
    }


    public function getForm($value = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;

        if ($this->hasChoices()) {
            return Form::select($this->name, $this->choices, $value, $extraParam);
        }

        if ($this->largo) {
            $extraParam['maxlength'] = $this->largo;
        }

        return Form::text($this->name, $value, $extraParam);
    }

}
