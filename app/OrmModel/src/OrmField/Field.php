<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

abstract class Field
{
    protected $name = '';
    protected $attribute = '';
    protected $rules = [];
    protected $helpText = '';

    protected $value;
    protected $formItem;

    protected $showOnList = true;
    protected $showOnDetail = true;
    protected $showOnForm = true;
    protected $alignOnList = 'text-left';

    protected $isSortable = false;

    protected $sortByKey = 'sort-by';
    protected $sortDirectionKey = 'sort-direction';
    protected $sortIconDefault = 'fa fa-sort text-black-20';
    protected $sortIcons = [
        'asc' => 'fa fa-caret-up text-muted',
        'desc' => 'fa fa-caret-down text-muted',
    ];
    protected $sortingIcon;

    protected $showValue = '';

    protected $onChange = '';
    protected $parentModel = null;
    protected $relationModel = null;
    protected $relationConditions = [];

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
     * Indica que el campo es "ordenable"
     *
     * @return Field
     */
    public function sortable(): Field
    {
        $this->isSortable = true;

        return $this;
    }

    /**
     * Muestra campo en listado Index
     *
     * @return Field
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
     * @return Field
     */
    public function showOnDetail(): bool
    {
        return $this->showOnDetail;
    }

    /**
     * Muestra campo en formulario
     *
     * @return Field
     */
    public function showOnForm(): bool
    {
        return $this->showOnForm;
    }

    /**
     * Devuelve icono de ordenamiento
     *
     * @return string
     */
    public function sortingIcon(): HtmlString
    {
        return $this->sortingIcon ?? new HtmlString('');
    }

    /**
     * Genera iconos para ordenar por campo en listado Index
     *
     * @return HtmlString
     */
    public function makeSortingIcon(Request $request, Resource $resource): Field
    {
        if ($this->isSortable) {
            $iconClass = $this->getSortingIconClass($request, $resource);
            $sortOrder = $this->getSortingOrder($request, $resource);
            $sortUrl = $this->getSortUrl($request, $sortOrder);

            $this->sortingIcon = new HtmlString("<a href=\"{$sortUrl}\"><span class=\"{$iconClass}\"><span></a>");
        }

        return $this;
    }

    /**
     * Devuelve la clase o icono a aplicar en un campo
     *
     * @param  Request $request
     * @return string
     */
    protected function getSortingIconClass(Request $request, Resource $resource): string
    {
        $sortingField = $request->input($this->sortByKey, collect($resource->getOrderBy())->keys()->first());
        $sortDirection = $request->input($this->sortDirectionKey, collect($resource->getOrderBy())->first());

        return ($sortingField === $this->attribute)
            ? Arr::get($this->sortIcons, $sortDirection, $this->sortIconDefault)
            : $this->sortIconDefault;
    }

    /**
     * Devuelve el orden (asc/desc) de ordenamiento de un campo
     *
     * @param  Request $request
     * @return string
     */
    protected function getSortingOrder(Request $request, Resource $resource): string
    {
        $sortingField = $request->input($this->sortByKey, collect($resource->getOrderBy())->keys()->first());
        $sortDirection = $request->input($this->sortDirectionKey, collect($resource->getOrderBy())->first());
        $newSortOrder = ['asc' => 'desc', 'desc' => 'asc'];

        return ($sortingField === $this->attribute)
            ? Arr::get($newSortOrder, $sortDirection, 'asc')
            : 'asc';
    }

    /**
     * Devuelve URL de ordenamiento
     *
     * @param  Request $request
     * @param  string  $sortOrder
     * @return string
     */
    protected function getSortUrl(Request $request, string $sortOrder = ''): string
    {
        return $request->fullUrlWithQuery(array_merge($request->all(), [
            $this->sortByKey => $this->attribute,
            $this->sortDirectionKey => $sortOrder,
        ]));
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
     * @param  Resource|null $resource
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

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
     * @return mixed
     */
    public function getOnChange(): string
    {
        return $this->onChange;
    }

    /**
     * Fija la glosa onchange
     *
     * @param mixed $onChange
     * @return self
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
     * Devuelve validación del campo
     *
     * @param  Resource $resource
     * @return String
     */
    public function getValidation(Resource $resource): string
    {
        return collect($this->rules)
            ->map(function ($rule) use ($resource) {
                return ($rule === 'unique')
                    ? 'unique:' . $this->getUniqueRuleParameters($resource)
                    : $rule;
            })
            ->implode('|');
    }

    /**
     * Recupera los parametros de regla validacion unique
     *
     * @param  Resource $resource
     * @return string
     */
    protected function getUniqueRuleParameters(Resource $resource): string
    {
        return implode(',', [
            $resource->model()->getTable(),
            $this->attribute,
            $resource->model()->getKey(),
            $resource->model()->getKeyName()
        ]);
    }

    /**
     * Formatea valor a mostrar a partir de modelo
     *
     * @param  Model  $model
     * @return Field
     */
    public function resolveValue(Model $model, Request $request): Field
    {
        $this->value = $this->getFormattedValue($model, $request);

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
        $extraParam['class'] = Arr::get($extraParam, 'class', '')
            . (optional($request->session()->get('errors'))->has($this->attribute) ? ' is-invalid' : '');

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
        return $this->formItem;
    }

    /**
     * Devuelve valor del campo formateado
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request)
    {
        return optional($model)->{$this->attribute};
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
        $extraParam['id'] = $this->name;
        $value = $resource->{$this->attribute};

        return Form::text($this->name, $value, $extraParam);
    }

    /**
     * Fija las reglas de validacion del campo
     *
     * @param  mixed $rules
     * @return Field
     */
    public function rules(...$rules): Field
    {
        $rulesArray = [];

        foreach ($rules as $rule) {
            $rule = is_array($rule) ? $rule : [$rule];
            $rulesArray = array_merge($rulesArray, $rule);
        }

        $this->rules = $rulesArray;

        return $this;
    }
}
