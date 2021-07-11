<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

class Text extends Field
{
    protected $defaultMaxLength = 250;

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
        return new HtmlString(view('orm.form-input', [
            'type' => 'text',
            'name' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute),
            'id' => $this->attribute,
            'maxlength' => $this->getFieldLength(),
        ])->render());
    }

    /**
     * Devuelve largo del campo, de acuerdo a regla de validacion
     *
     * @return int
     */
    protected function getFieldLength(): int
    {
        $maxRule = collect($this->rules)
            ->first(fn($rule) => strpos($rule, 'max:') !== false);

        return is_null($maxRule) ? $this->defaultMaxLength : (int) Str::after($maxRule, ':');
    }
}
