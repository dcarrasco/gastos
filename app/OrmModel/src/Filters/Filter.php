<?php

namespace App\OrmModel\src\Filters;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    protected $parameterPrefix = 'filter_';

    /**
     * Aplica filtro en la query
     * @param  Request $request
     * @param  Builder $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(Request $request, Builder $query, $value)
    {
        return $query;
    }

    /**
     * Opciones a mostrar para el filtrp
     * @return array
     */
    public function options()
    {
        return [];
    }

    /**
     * Devuelve nombre del filtro
     * @return string
     */
    public function getName()
    {
        $fullName = explode('\\', get_class($this));
        $name = array_pop($fullName);

        return $name;
    }

    /**
     * Devuelve etiqueta o titulo del recurso
     * @return string
     */
    public function getLabel()
    {
        return str_replace('_', ' ', Str::snake($this->getName()));
    }

    /**
     * Devuelve link para activar el filtro
     * @param  Request $request
     * @param  mixed  $value
     * @return string
     */
    public function getOptionUrl(Request $request, $value)
    {
        $parameters = ($this->isSet($request) and $this->getValue($request) == $value)
            ? [$this->getUrlParameter() => '']
            : [$this->getUrlParameter() => $value];

        $urlParameters = array_merge($request->all(), $parameters);

        return $request->fullUrlWithQuery($urlParameters);
    }

    /**
     * Devuelve nombre del parámetro string query del filtro
     * @return string
     */
    public function getUrlParameter()
    {
        return $this->parameterPrefix.$this->getName();
    }

    /**
     * Devuelve html para marcar la opcion de filtro activa
     * @param  Request $request
     * @param  mixed  $value
     * @return string
     */
    public function getUrlMark(Request $request, $value)
    {
        if (is_null($this->getValue($request)))
        {
            return '';
        }

        return $this->getValue($request) == $value
            ? '<span class="fa fa-check"></span>'
            : '';
    }

    /**
     * Devuelve el valor del filtro en el stringquery
     * @param  Request $request
     * @return mixed
     */
    public function getValue(Request $request)
    {
        $value = $request->get($this->getUrlParameter());

        if (is_null($value) or $value === '') {
            return null;
        }

        return $value;
    }

    /**
     * Determina si un filtro esta activo
     * @param  Request $request
     * @return boolean
     */
    public function isSet(Request $request)
    {
        return $request->has($this->getUrlParameter()) and ! is_null($this->getValue($request));
    }

}
