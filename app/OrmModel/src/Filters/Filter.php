<?php

namespace App\OrmModel\src\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * @param  Request  $request
     * @param  Builder<Model>  $query
     * @param  mixed  $value
     * @return Builder<Model>
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
     * @param  Request  $request
     * @param  string  $value
     * @return string
     */
    public function getFilterUrl(Request $request, string $value): string
    {
        $urlParameters = array_merge($request->all(), ($this->isSet($request) and $this->getValue($request) == $value)
            ? [$this->getUrlParameterName() => '']
            : [$this->getUrlParameterName() => $value]);

        return $request->fullUrlWithQuery($urlParameters);
    }

    /**
     * Devuelve nombre del parámetro string query del filtro
     *
     * @return string
     */
    public function getUrlParameterName(): string
    {
        return $this->parameterPrefix.$this->getName();
    }

    /**
     * Indica si la opcion de filtro esta activa
     *
     * @param  Request  $request
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
     * @param  Request  $request
     * @return string
     */
    public function getValue(Request $request): string
    {
        return $request->get($this->getUrlParameterName()) ?? '';
    }

    /**
     * Determina si un filtro esta activo
     *
     * @param  Request  $request
     * @return bool
     */
    public function isSet(Request $request): bool
    {
        return $request->has($this->getUrlParameterName()) and $this->getValue($request) != '';
    }
}
