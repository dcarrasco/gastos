<?php

namespace App\OrmModel;

use Form;

class OrmField
{
    const TIPO_ID = 'ID';
    const TIPO_INT = 'INT';
    const TIPO_REAL = 'REAL';
    const TIPO_CHAR = 'CHAR';
    const TIPO_BOOLEAN = 'BOOLEAN';
    const TIPO_DATETIME = 'DATETIME';
    const TIPO_HAS_ONE = 'HAS_ONE';
    const TIPO_HAS_MANY = 'HAS_MANY';

    protected $name = '';
    protected $label = '';
    protected $tipo = '';
    protected $largo;
    protected $textoAyuda = '';
    protected $choices = [];
    protected $onChange = '';
    protected $mostrarLista = true;
    protected $parentModel = null;
    protected $relationModel = null;
    protected $relationConditions = [];
    protected $esObligatorio = false;
    protected $esUnico = false;
    protected $esId = false;
    protected $esIncrementing = false;

    public function __construct(array $atributos = [])
    {
        $atributosValidos = [
            'name',
            'label',
            'tipo',
            'largo',
            'textoAyuda',
            'mostrarLista',
            'choices',
            'onChange',
            'parentModel',
            'parentId',
            'relationModel',
            'relationConditions',
            'esObligatorio',
            'esUnico',
            'esId',
            'esIncrementing',
        ];

        foreach ($atributos as $atributo => $valor) {
            if (in_array($atributo, $atributosValidos)) {
                $this->{$atributo} = $valor;
            }
        }
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
    public function getLabel()
    {
        return $this->label;
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
    public function getTextoAyuda()
    {
        return $this->textoAyuda;
    }

    /**
     * @param mixed $textoAyuda
     *
     * @return self
     */
    public function setTextoAyuda($textoAyuda)
    {
        $this->textoAyuda = $textoAyuda;

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

    public function getValidation()
    {
        $validation = [];

        if ($this->esObligatorio) {
            $validation[] = 'required';
        }

        return collect($validation)->implode('|');
    }

    public function getRelatedModel($class = '')
    {
        $relatedModelClass = $class === '' ? $this->relationModel : $class;

        if (!empty($relatedModelClass)) {
            return new $relatedModelClass;
        }

        return;
    }

    public function getFormattedValue($value = null)
    {
        return $value;
    }

    public function getForm($value = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;

        return Form::text($this->name, $value, $extraParam);
    }
}
