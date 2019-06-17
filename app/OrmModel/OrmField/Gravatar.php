<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;
use Collective\Html\HtmlBuilder;
use Illuminate\Database\Eloquent\Model;

class Gravatar extends Field
{

    /**
     * Constructor de la clase
     * @param string $name  Nombre o label de la clase
     * @param string $field Campo
     */
    public function __construct($field = '')
    {
        parent::__construct('', 'email');
    }


    protected function getGravatarUrl($email = '', $size = 256)
    {
        $md5Email = md5($email);

        return "https://secure.gravatar.com/avatar/{$md5Email}?size={$size}";
    }
    /**
     * Devuelve valor del campo formateado
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getValue(Model $model = null)
    {
        return app()->make(HtmlBuilder::class)
            ->image($this->getGravatarUrl(optional($model)->{$this->attribute}, 24), null, ['class' => 'rounded-circle border']);
    }

    /**
     * Devuelve elemento de formulario para el campo
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, $extraParam = [])
    {
        $extraParam['id'] = $this->attribute;
        $value = $resource->model()->{$this->attribute};

        return Form::number($this->attribute, $value, $extraParam);
    }

}
