<?php

namespace App\OrmModel;

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

    protected $atributosValidos = [
        'label',
        'tipo',
        'largo',
        'textoAyuda',
        'mostrarLista',
        'choices',
        'relationModel',
        'esObligatorio',
        'esUnico'
    ];

    protected $label = '';
    protected $tipo = '';
    protected $largo;
    protected $textoAyuda = '';
    protected $choices = [];
    protected $mostrarLista = true;
    protected $relationModel = null;
    protected $esObligatorio = false;
    protected $esUnico = false;

    public function __construct(array $atributos = [])
    {
        foreach ($atributos as $atributo => $valor) {
            if (in_array($atributo, $this->atributosValidos)) {
                $this->{$atributo} = $valor;
            }
        }
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
     * @return mixed
     */
    public function hasChoices()
    {
        return count($this->choices) > 0;
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
    public function getAtributosValidos()
    {
        return $this->atributosValidos;
    }

    /**
     * @param mixed $atributosValidos
     *
     * @return self
     */
    public function setAtributosValidos($atributosValidos)
    {
        $this->atributosValidos = $atributosValidos;

        return $this;
    }
}
