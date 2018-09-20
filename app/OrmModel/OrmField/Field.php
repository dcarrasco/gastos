<?php

namespace App\OrmModel\OrmField;

use Form;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

    protected $choices = [];
    protected $onChange = '';
    protected $parentModel = null;
    protected $relationModel = null;
    protected $relationConditions = [];

    public function __construct($name = '', $field = '')
    {
        $this->name = $name;
        $this->field = empty($field) ? Str::snake($name) : $field;
    }

    public static function make($name = '', $field = '')
    {
        return new static($name, $field);
    }

    public function hideFromIndex()
    {
        $this->showOnList = false;

        return $this;
    }

    public function sortable()
    {
        $this->isSortable = true;

        return $this;
    }

    public function options($options = [])
    {
        $this->choices = $options;

        return $this;
    }

    public function showOnIndex()
    {
        return $this->showOnList;
    }

    public function showOnDetail()
    {
        return $this->showOnDetail;
    }

    public function getSortingIcon()
    {
        if (! $this->isSortable) {
            return '';
        }

        $iconDefault = 'fa fa-sort text-black-50';
        $icons = [
            'asc' => 'fa fa-caret-up text-dark',
            'desc' => 'fa fa-caret-down text-dark',
        ];

        $iconClass = $iconDefault;
        $newSortOrder = 'asc';

        if (request($this->sortByKey, '') === $this->field)
        {
            $iconClass = array_get($icons, request($this->sortDirectionKey, ''), $iconDefault);
            $newSortOrder = array_get(['asc' => 'desc', 'desc' => 'asc'], request($this->sortDirectionKey, ''), 'asc');
        }

        $getParams = array_merge(request()->only('filter'), [
            $this->sortByKey => $this->field,
            $this->sortDirectionKey => $newSortOrder
        ]);
        $sortUrl = request()->fullUrlWithQuery($getParams);

        return "<a href=\"{$sortUrl}\"><span class=\"{$iconClass}\"><span></a>";
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getField($resource = null)
    {
        return $this->field;
    }

    public function isRequired()
    {
        return collect($this->rules)->contains('required');
    }



    /**
     * @param mixed $label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     *
     * @return self
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLargo()
    {
        return $this->largo;
    }

    /**
     * @param mixed $largo
     *
     * @return self
     */
    public function setLargo($largo)
    {
        $this->largo = $largo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * @param mixed $textoAyuda
     *
     * @return self
     */
    public function helpText($helpText)
    {
        $this->helpText = $helpText;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMostrarLista()
    {
        return $this->mostrarLista;
    }

    /**
     * @param mixed $mostrarLista
     *
     * @return self
     */
    public function setMostrarLista($mostrarLista)
    {
        $this->mostrarLista = $mostrarLista;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param mixed $choices
     *
     * @return self
     */
    public function setChoices($choices)
    {
        $this->choices = $choices;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasChoices()
    {
        return count($this->choices) > 0;
    }

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
    public function setOnChange($onChange)
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
     * @return mixed
     */
    public function getParentModel()
    {
        return $this->parentModel;
    }

    /**
     * @param mixed $parentModel
     *
     * @return self
     */
    public function setParentModel($parentModel)
    {
        $this->parentModel = $parentModel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelationModel()
    {
        return $this->relationModel;
    }

    /**
     * @param mixed $relationModel
     *
     * @return self
     */
    public function setRelationModel($relationModel)
    {
        $this->relationModel = $relationModel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelationConditions()
    {
        return $this->relationConditions;
    }

    /**
     * @param mixed $relationConditions
     *
     * @return self
     */
    public function setRelationConditions($relationConditions)
    {
        $this->relationConditions = $relationConditions;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasRelationConditions()
    {
        return count($this->relationConditions) > 0;
    }

    /**
     * @return mixed
     */
    public function getEsObligatorio()
    {
        return $this->esObligatorio;
    }

    /**
     * @param mixed $esObligatorio
     *
     * @return self
     */
    public function setEsObligatorio($esObligatorio)
    {
        $this->esObligatorio = $esObligatorio;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEsUnico()
    {
        return $this->esUnico;
    }

    /**
     * @param mixed $esUnico
     *
     * @return self
     */
    public function setEsUnico($esUnico)
    {
        $this->esUnico = $esUnico;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEsId()
    {
        return $this->esId;
    }

    /**
     * @param mixed $esId
     *
     * @return self
     */
    public function setEsId($esId)
    {
        $this->esId = $esId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEsIncrementing()
    {
        return $this->esIncrementing;
    }

    /**
     * @param mixed $esIncrementing
     *
     * @return self
     */
    public function setEsIncrementing($esIncrementing)
    {
        $this->esIncrementing = $esIncrementing;

        return $this;
    }

    public function getValidation($resource)
    {
        return collect($this->rules)
            ->map(function($rule) use ($resource) {
                return ($rule === 'unique')
                    ? 'unique:'.implode(',', [
                        $resource->getModelObject()->getTable(),
                        $this->getField($resource),
                        $resource->getModelObject()->getKey(),
                        $resource->getModelObject()->getKeyName()
                    ])
                    : $rule;
            })
            ->implode('|');
    }

    public function getRelatedModel($class = '')
    {
        $relatedModelClass = $class === '' ? $this->relationModel : $class;

        if (!empty($relatedModelClass)) {
            return new $relatedModelClass;
        }

        return null;
    }

    public function getFormattedValue(Request $request, $model = null)
    {
        return $model->{$this->getField($request)};
    }

    public function getForm(Request $request, $resource = null, $extraParam = [], $parentId = null)
    {
        $extraParam['id'] = $this->name;
        $value = $resource->{$this->getField($resource)};

        return Form::text($this->name, $value, $extraParam);
    }

    public function rules (...$rules)
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
}
