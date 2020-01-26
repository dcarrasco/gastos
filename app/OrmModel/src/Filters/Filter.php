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
     *
     * @param  Request $request
     * @param  Builder $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(Request $request, Builder $query, $value): Builder
    {
        return $query;
    }

    /**
     * Opciones a mostrar para el filtro
     *
     * @return array
     */
    public function options(): array
    {
        return [];
    }

    /**
     * Devuelve nombre del filtro
     *
     * @return string
     */
    public function getName(): string
    {
        $fullName = explode('\\', get_class($this));
        $name = array_pop($fullName);

        return $name;
    }

    /**
     * Devuelve etiqueta o titulo del recurso
     *
     * @return string
     */
    public function getLabel(): string
    {
        return str_replace('_', ' ', Str::snake($this->getName()));
    }

    /**
     * Devuelve link para activar el filtro
     *
     * @param  Request $request
     * @param  string  $value
     * @return string
     */
    public function getOptionUrl(Request $request, string $value): string
    {
        $parameters = ($this->isSet($request) and $this->getValue($request) == $value)
            ? [$this->getUrlParameter() => '']
            : [$this->getUrlParameter() => $value];

        $urlParameters = array_merge($request->all(), $parameters);

        return $request->fullUrlWithQuery($urlParameters);
    }

    /**
     * Devuelve nombre del parámetro string query del filtro
     *
     * @return string
     */
    public function getUrlParameter(): string
    {
        return $this->parameterPrefix . $this->getName();
    }

    /**
     * Devuelve html para marcar la opcion de filtro activa
     *
     * @param  Request $request
     * @param  string  $value
     * @return string
     */
    public function getUrlMark(Request $request, string $value): string
    {
        if (is_null($this->getValue($request))) {
            return '';
        }

        return $this->getValue($request) == $value
            ? '<span class="fa fa-check"></span>'
            : '';
    }

    /**
     * Devuelve el valor del filtro en el stringquery
     *
     * @param  Request $request
     * @return mixed
     */
    public function getValue(Request $request): string
    {
        $value = $request->get($this->getUrlParameter());

        if (is_null($value) or $value === '') {
            return '';
        }

        return $value;
    }

    /**
     * Determina si un filtro esta activo
     *
     * @param  Request $request
     * @return boolean
     */
    public function isSet(Request $request): bool
    {
        return $request->has($this->getUrlParameter()) and $this->getValue($request) != '';
    }
}
