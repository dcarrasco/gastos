<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class RelationField extends OrmField
{
    protected $relatedResource = '';

    public function __construct($name = '', $field = '')
    {
        parent::__construct($name, $field);
        dump($this);
    }
}
