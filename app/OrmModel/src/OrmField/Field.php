<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

abstract class Field
{
    use UsesSorting;
    use UsesValidation;

    protected $name = '';
    protected $attribute = '';
    protected $helpText = '';

    protected $value;
    protected $formattedValue;
    protected $formItem;

    protected $showOnList = true;
    protected $showOnDetail = true;
    protected $showOnForm = true;
    protected $alignOnList = 'text-left';

    protected $showValue = '';

    protected $onChange = '';
    protected $parentModel = null;
    protected $relationModel = null;
    protected $relationConditions = [];
    protected $eagerLoadsRelation = false;

    /**
     * Constructor de la clase
     *
     * @param string $name  Nombre o label de la clase
     * @param string $field Campo
     */
    public function __construct(string $name, string $field = '')
    {
        $this->name = $name;
        $this->attribute = empty($field) ? Str::snake($name) : $field;
    }

    /**
     * Genera una nueva instancia de la clase
     *
     * @param  string $name  Nombre o label de la clase
     * @param  string $field Campo
     * @return Field
     */
    public static function make(string $name = '', string $field = ''): Field
    {
        return new static($name, $field);
    }

    /**
     * Oculta el campo del listado Index
     *
     * @return Field
     */
    public function hideFromIndex(): Field
    {
        $this->showOnList = false;

        return $this;
    }

    /**
     * Oculta el campo del listado Detail
     *
     * @return Field
     */
    public function hideFromDetail(): Field
    {
        $this->showOnDetail = false;

        return $this;
    }

    /**
     * Oculta el campo del listado Form
     *
     * @return Field
     */
    public function hideFromForm(): Field
    {
        $this->showOnForm = false;

        return $this;
    }

    /**
     * Muestra campo en listado Index
     *
     * @return bool
     */
    public function showOnIndex(): bool
    {
        return $this->showOnList;
    }

    /**
     * Devuelve texto bootstrap para alinear campo en listado Index
     *
     * @return string
     */
    public function alignOnList(): string
    {
        return $this->alignOnList;
    }

    /**
     * Muestra campo en detalle
     *
     * @return bool
     */
    public function showOnDetail(): bool
    {
        return $this->showOnDetail;
    }

    /**
     * Muestra campo en formulario
     *
     * @return bool
     */
    public function showOnForm(): bool
    {
        return $this->showOnForm;
    }

    /**
     * Devuelve nombre del campo Field
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Fija nombre/label del campo
     *
     * @param string $name
     * @return Field
     */
    public function setName(string $name = ''): Field
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Devuelve nombre del campo de la BD
     *
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * Devuelve el atributo del modelo
     *
     * @param Resource $resource
     * @return string
     */
    public function getModelAttribute(Resource $resource): string
    {
        return $this->attribute;
    }

    /**
     * Indica si el campo es obligatorio
     *
     * @return boolean
     */
    public function isRequired(): bool
    {
        return collect($this->rules)->contains('required');
    }

    /**
     * Recupera la glosa onchange
     *
     * @return string
     */
    public function getOnChange(): string
    {
        return $this->onChange;
    }

    /**
     * Fija la glosa onchange
     *
     * @param string $onChange
     * @return Field
     */
    public function onChange(string $onChange): Field
    {
        $this->onChange = $onChange;

        return $this;
    }

    /**
     * Indica si el campo tiene glosa onchange
     *
     * @return boolean
     */
    public function hasOnChange(): bool
    {
        return !empty($this->onChange);
    }

    /**
     * Resuelve el valor del campo a partir del modelo y del request
     *
     * @param  Model    $model
     * @param  Request  $request
     * @return Field
     */
    public function resolveValue(Model $model, Request $request): Field
    {
        $this->value = $this->getValue($model, $request);

        return $this;
    }

    /**
     * Devuelve valor del campo
     * @param  Model    $model
     * @param  Request  $request
     * @return mixed
     */
    public function getValue(Model $model, Request $request)
    {
        return optional($model)->getAttribute($this->attribute);
    }

    /**
     * Setea el valor del campo
     *
     * @param mixed $value
     * @return Field
     */
    public function setValue($value): Field
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Formatea valor a mostrar a partir de modelo
     *
     * @return Field
     */
    public function resolveFormattedValue(): Field
    {
        $this->formattedValue = $this->getFormattedValue();

        return $this;
    }

    /**
     * Genera elemento de formulario a mostrar a partir de request y resource
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return Field
     */
    public function resolveFormItem(Request $request, Resource $resource, array $extraParam = []): Field
    {
        $this->formItem = $this->getForm($request, $resource, $extraParam);

        return $this;
    }

    /**
     * Devuelve elemento de formulario calculado
     *
     * @return string
     */
    public function formItem(): HtmlString
    {
        return $this->formItem ?? new HtmlString();
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @return mixed
     */
    public function getFormattedValue(): HtmlString
    {
        return new HtmlString($this->value);
    }

    /**
     * Devuelve valor del campo calculado
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
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
        return new HtmlString(view('orm.form-input', [
            'type' => 'text',
            'name' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute),
            'id' => $this->attribute,
        ])->render());
    }

    /**
     * Inidca si el campo es tipo relacion y eagerLoads su contenido

     * @return bool
     */
    public function eagerLoadsRelation(): bool
    {
        return $this->eagerLoadsRelation;
    }
}
