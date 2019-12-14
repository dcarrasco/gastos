<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

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
    public function __construct(string $name = '', string $field = '', string $relatedResource = '')
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
    public function relationConditions(array $relationConditions = []): Field
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
     * Devuelve recursos relacionados con valores
     *
     * @param  Model  $model
     * @return Collection
     */
    public function getRelation(Model $model): Collection
    {
        return $model->{$this->attribute}->mapInto($this->relatedResource);
    }

    /**
     * Recupera objetos del recurso relacionado
     * 
     * @param  Request       $request
     * @param  Resource|null $resource
     * @param  string        $field
     * @param  array         $conditions
     * @return array
     */
    protected function getRelatedListModels(Request $request, Resource $resource = null, array $conditions = [])
    {
        return (new $this->relatedResource)
            ->resourceOrderBy($request)
            ->getModelQueryBuilder()
            ->where($this->getResourceFilter($resource, $conditions))
            ->get();
    }

    /**
     * Recupera elementos del recurso relacionado
     *
     * @param  Request       $request
     * @param  Resource|null $resource
     * @param  string        $field
     * @param  array         $conditions
     * @return array
     */
    public function getRelationOptions(Request $request, Resource $resource = null,
                                       array $conditions = []): Collection
    {
        return $this->getRelatedListModels($request, $resource, $conditions)
            ->mapWithKeys(function($model) {
                return [$model->getKey() => (new $this->relatedResource($model))->title()];
            });
    }

    /**
     * Devuelve arreglo con las condiciones de la relacion
     * 
     * @param  Resource $resource
     * @param  array    $conditions
     * @return array
     */
    protected function getResourceFilter(Resource $resource, array $conditions = []): array
    {
        return collect($conditions)
            ->filter(function($condition) {
                return strpos($condition, '@field_value:') !== false;
            })->map(function($condition) use ($resource) {
                list($label, $field, $defaul) = explode(':', $condition);
                return $resource->model()->{$field};
            })->all();
    }
}
