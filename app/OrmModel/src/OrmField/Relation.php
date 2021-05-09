<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use App\OrmModel\src\OrmField\Field;

class Relation extends Field
{
    protected $relatedResource = '';
    protected $relationConditions = [];

    /**
     * Constructor de la clase
     *
     * @param string $name            Nombre o label de la clase
     * @param string $field           Campo
     * @param string $relatedResource Nombre del recurso relacionado
     */
    public function __construct(string $name, string $field = '', string $relatedResource = '')
    {
        $field = empty($field) ? $name : $field;
        $this->relatedResource = empty($relatedResource) ? $field : $relatedResource;

        parent::__construct($name, $field);
    }

    /**
     * Fija las condiciones de la relacion
     *
     * @param  array $relationConditions
     * @return Relation
     */
    public function relationConditions(array $relationConditions = []): Relation
    {
        $this->relationConditions = $relationConditions;

        return $this;
    }

    /**
     * Genera una nueva instancia de la clase
     *
     * @param  string $name            Nombre o label de la clase
     * @param  string $field           Campo
     * @param  string $relatedResource Nombre del recurso relacionado
     * @return Field
     */
    public static function make(string $name = '', string $field = '', string $relatedResource = ''): Field
    {
        return new static($name, $field, $relatedResource);
    }

    /**
     * Recupera elementos del recurso relacionado
     *
     * @param  Request   $request
     * @param  Resource  $resource
     * @param  array     $conditions
     * @return Collection
     */
    public function getRelationOptions(Request $request, Resource $resource, array $conditions = []): Collection
    {
        return $this->getRelatedListModels($request, $resource, $conditions)
            ->mapWithKeys(fn($model) => [$model->getKey() => (new $this->relatedResource($model))->title()]);
    }

    /**
     * Recupera objetos del recurso relacionado
     *
     * @param  Request   $request
     * @param  Resource  $resource
     * @param  array     $conditions
     * @return array
     */
    protected function getRelatedListModels(Request $request, Resource $resource, array $conditions = []): Collection
    {
        return (new $this->relatedResource())
            ->applyOrderBy($request)
            ->getModelQueryBuilder()
            ->where($this->getRelationFilter($resource, $conditions))
            ->get();
    }

    /**
     * Devuelve arreglo con las condiciones de la relacion
     *
     * @param  Resource $resource
     * @param  array    $conditions
     * @return array
     */
    protected function getRelationFilter(Resource $resource, array $conditions = []): array
    {
        return collect($conditions)
            ->filter(fn($condition) => strpos($condition, '@field_value:') !== false)
            ->map(function ($condition) use ($resource) {
                list($label, $field, $defaul) = explode(':', $condition);
                return $resource->model()->{$field};
            })
            ->all();
    }
}
