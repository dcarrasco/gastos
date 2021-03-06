<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

class Id extends Field
{
    protected $esIncrementing = true;

    /**
     * Constructor de la clase
     *
     * @param string $name  Nombre o label de la clase
     * @param string $field Campo
     */
    public function __construct(string $name = '', string $field = '')
    {
        $name = empty($name) ? 'id' : $name;
        parent::__construct($name, $field);

        $this->showOnForm = false;
    }

    /**
     * Fija si el campo id es autoincrement
     *
     * @param boolean $esIncrementing
     * @return Id
     */
    public function esIncrementing(bool $esIncrementing = true): Id
    {
        $this->esIncrementing = $esIncrementing;

        return $this;
    }


    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $value = $resource->model()->getAttribute($this->attribute);

        if ($this->esIncrementing) {
            return new HtmlString(
                "<p class=\"form-control-static\">{$value}</p>"
                . view('orm.form-input', [
                    'type' => 'hidden',
                    'name' => $this->attribute,
                    'value' => $value,
                    'id' => $this->attribute,
                ])->render()
            );
        }

        return new HtmlString(view('orm.form-input', [
            'type' => 'text',
            'name' => $this->attribute,
            'value' => $value,
            'id' => $this->attribute,
        ])->render());
    }
}
