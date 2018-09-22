<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class Field
{
    protected $name = '';
    protected $field = '';
    protected $rules = [];
    protected $helpText = '';

    protected $showOnList = true;
    protected $showOnDetail = true;

    protected $isSortable = false;

    protected $sortByKey = 'sort-by';
    protected $sortDirectionKey = 'sort-direction';
    protected $sortIconDefault = 'fa fa-sort text-black-50';
    protected $sortIcons = [
        'asc' => 'fa fa-caret-up text-dark',
        'desc' => 'fa fa-caret-down text-dark',
    ];

    protected $onChange = '';
    protected $parentModel = null;
    protected $relationModel = null;
    protected $relationConditions = [];

    /**
     * Constructor de la clase
     * @param string $name  Nombre o label de la clase
     * @param string $field Campo
     */
    public function __construct($name = '', $field = '')
    {
        $this->name = $name;
        $this->field = empty($field) ? Str::snake($name) : $field;
    }

    /**
     * Genera una nueva instancia de la clase
     * @param  string $name  Nombre o label de la clase
     * @param  string $field Campo
     * @return Field
     */
    public static function make($name = '', $field = '')
    {
        return new static($name, $field);
    }

    /**
     * Oculta el campo del listado Index
     * @return Field
     */
    public function hideFromIndex()
    {
        $this->showOnList = false;

        return $this;
    }

    /**
     * Indica que el campo es "ordenable"
     * @return Field
     */
    public function sortable()
    {
        $this->isSortable = true;

        return $this;
    }

    /**
     * Muestra campo en listado Index
     * @return Field
     */
    public function showOnIndex()
    {
        return $this->showOnList;
    }

    /**
     * Muestra campo en detalle / formulario
     * @return Field
     */
    public function showOnDetail()
    {
        return $this->showOnDetail;
    }

    /**
     * Genera iconos para ordenar por campo en listado Index
     * @return HtmlString
     */
    public function getSortingIcon(Request $request, Resource $resource)
    {
        if (! $this->isSortable) {
            return '';
        }

        $iconClass = $this->getSortingIconClass($request, $resource);
        $sortOrder = $this->getSortingOrder($request, $resource);
        $sortUrl = $this->getSortUrl($request, $sortOrder);

        return new HtmlString("<a href=\"{$sortUrl}\"><span class=\"{$iconClass}\"><span></a>");
    }

    /**
     * Devuelve la clase o icono a aplicar en un campo
     * @param  Request $request
     * @return string
     */
    protected function getSortingIconClass(Request $request, Resource $resource)
    {
        $iconClass = $this->sortIconDefault;
        $sortingField = $request->input($this->sortByKey, collect($resource->getOrder())->keys()->first());
        $sortDirection = $request->input($this->sortDirectionKey, collect($resource->getOrder())->first());

        if ($sortingField === $this->field)
        {
            $iconClass = array_get($this->sortIcons, $sortDirection, $this->sortIconDefault);
        }

        return $iconClass;
    }

    /**
     * Devuelve el orden (asc/desc) de ordenamiento de un campo
     * @param  Request $request
     * @return string
     */
    protected function getSortingOrder(Request $request, Resource $resource)
    {
        $sortOrder = 'asc';
        $sortingField = $request->input($this->sortByKey, collect($resource->getOrder())->keys()->first());
        $sortDirection = $request->input($this->sortDirectionKey, collect($resource->getOrder())->first());
        $newSortOrder = ['asc' => 'desc', 'desc' => 'asc'];

        if ($sortingField === $this->field)
        {
            $sortOrder = array_get($newSortOrder, $sortDirection, 'asc');
        }

        return $sortOrder;
    }

    protected function getSortUrl(Request $request, $sortOrder = '')
    {
        return $request->fullUrlWithQuery(array_merge($request->all(), [
            $this->sortByKey => $this->field,
            $this->sortDirectionKey => $sortOrder,
        ]));
    }

    /**
     * Devuelve nombre del campo Field
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Fija nombre/label del campo
     * @param string $name
     * @return Field
     */
    public function setName($name = '')
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Devuelve nombre del campo de la BD
     * @param  Resource|null $resource
     * @return string
     */
    public function getField(Resource $resource = null)
    {
        return $this->field;
    }

    /**
     * Indica si el campo es obligatorio
     * @return boolean
     */
    public function isRequired()
    {
        return collect($this->rules)->contains('required');
    }




    /**
    /**
     * @return mixed
     */
    public function getOnChange()
    {
        return $this->onChange;
    }

    /**
     * @param mixed $onChange
     *
     * @return self
     */
    public function onChange($onChange)
    {
        $this->onChange = $onChange;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasOnChange()
    {
        return !empty($this->onChange);
    }

    /**
     * Devuelve validación del campo
     * @param  Resource $resource
     * @return String
     */
    public function getValidation(Resource $resource)
    {
        return collect($this->rules)
            ->map(function($rule) use ($resource) {
                return ($rule === 'unique')
                    ? 'unique:'.$this->getUniqueRuleParameters($resource)
                    : $rule;
            })
            ->implode('|');
    }

    /**
     * Recupera los parametros de regla validacion unique
     * @param  Resource $resource
     * @return string
     */
    protected function getUniqueRuleParameters(Resource $resource)
    {
        return implode(',', [
            $resource->model()->getTable(),
            $this->getField($resource),
            $resource->model()->getKey(),
            $resource->model()->getKeyName()
        ]);
    }

    /**
     * Devuelve valor del campo formateado
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getValue(Request $request, Model $model = null)
    {
        if (is_null($model)) {
            return null;
        }

        return $model->{$this->getField()};
    }

    /**
     * Devuelve elemento de formulario para el campo
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = [])
    {
        $extraParam['id'] = $this->name;
        $value = $resource->{$this->getField($resource)};

        return Form::text($this->name, $value, $extraParam);
    }

    /**
     * Fija las reglas de validacion del campo
     * @param  mixed $rules
     * @return Field
     */
    public function rules(...$rules)
    {
        $rulesArray = [];

        foreach ($rules as $rule)
        {
            if (is_array($rule))
            {
                $rulesArray = array_merge($rulesArray, $rule);
            }
            else
            {
                $rulesArray[] = (string) $rule;
            }
        }

        $this->rules = $rulesArray;

        return $this;
    }


    // public function setLabel($label)
    // {
    //     $this->label = $label;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getTipo()
    // {
    //     return $this->tipo;
    // }

    // /**
    //  * @param mixed $tipo
    //  *
    //  * @return self
    //  */
    // public function setTipo($tipo)
    // {
    //     $this->tipo = $tipo;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getLargo()
    // {
    //     return $this->largo;
    // }

    // /**
    //  * @param mixed $largo
    //  *
    //  * @return self
    //  */
    // public function setLargo($largo)
    // {
    //     $this->largo = $largo;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getHelpText()
    // {
    //     return $this->helpText;
    // }

    // /**
    //  * @param mixed $textoAyuda
    //  *
    //  * @return self
    //  */
    // public function helpText($helpText)
    // {
    //     $this->helpText = $helpText;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getMostrarLista()
    // {
    //     return $this->mostrarLista;
    // }

    // /**
    //  * @param mixed $mostrarLista
    //  *
    //  * @return self
    //  */
    // public function setMostrarLista($mostrarLista)
    // {
    //     $this->mostrarLista = $mostrarLista;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getParentModel()
    // {
    //     return $this->parentModel;
    // }

    // /**
    //  * @param mixed $parentModel
    //  *
    //  * @return self
    //  */
    // public function setParentModel($parentModel)
    // {
    //     $this->parentModel = $parentModel;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getRelationModel()
    // {
    //     return $this->relationModel;
    // }

    // /**
    //  * @param mixed $relationModel
    //  *
    //  * @return self
    //  */
    // public function setRelationModel($relationModel)
    // {
    //     $this->relationModel = $relationModel;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getRelationConditions()
    // {
    //     return $this->relationConditions;
    // }

    // /**
    //  * @param mixed $relationConditions
    //  *
    //  * @return self
    //  */
    // public function setRelationConditions($relationConditions)
    // {
    //     $this->relationConditions = $relationConditions;

    //     return $this;
    // }

    // /**
    //  * @return boolean
    //  */
    // public function hasRelationConditions()
    // {
    //     return count($this->relationConditions) > 0;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getEsObligatorio()
    // {
    //     return $this->esObligatorio;
    // }

    // /**
    //  * @param mixed $esObligatorio
    //  *
    //  * @return self
    //  */
    // public function setEsObligatorio($esObligatorio)
    // {
    //     $this->esObligatorio = $esObligatorio;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getEsUnico()
    // {
    //     return $this->esUnico;
    // }

    // /**
    //  * @param mixed $esUnico
    //  *
    //  * @return self
    //  */
    // public function setEsUnico($esUnico)
    // {
    //     $this->esUnico = $esUnico;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getEsId()
    // {
    //     return $this->esId;
    // }

    // /**
    //  * @param mixed $esId
    //  *
    //  * @return self
    //  */
    // public function setEsId($esId)
    // {
    //     $this->esId = $esId;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getEsIncrementing()
    // {
    //     return $this->esIncrementing;
    // }

    // /**
    //  * @param mixed $esIncrementing
    //  *
    //  * @return self
    //  */
    // public function setEsIncrementing($esIncrementing)
    // {
    //     $this->esIncrementing = $esIncrementing;

    //     return $this;
    // }

    // public function getRelatedModel($class = '')
    // {
    //     $relatedModelClass = $class === '' ? $this->relationModel : $class;

    //     if (!empty($relatedModelClass)) {
    //         return new $relatedModelClass;
    //     }

    //     return null;
    // }



}
