<?php

namespace App\OrmModel\src\Filters;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    /**
     * Prefijo a usar en la URL para el filtro
     *
     * @var string
     */
    protected string $parameterPrefix = 'filter_';


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
     * @return mixed[]
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
        return class_basename($this);
    }

    /**
     * Devuelve etiqueta o titulo del recurso
     *
     * @return string
     */
    public function getLabel(): string
    {
        return Str::of($this->getName())->snake()->replace('_', ' ');
    }

    /**
     * Devuelve link para activar el filtro
     *
     * @param  Request $request
     * @param  string  $value
     * @return string
     */
    public function getFilterUrl(Request $request, string $value): string
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
     * Indica si la opcion de filtro esta activa
     *
     * @param  Request $request
     * @param  string  $value
     * @return bool
     */
    public function isActive(Request $request, string $value): bool
    {
        return $this->getValue($request) == $value;
    }

    /**
     * Devuelve el valor del filtro en el stringquery
     *
     * @param  Request $request
     * @return string
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
