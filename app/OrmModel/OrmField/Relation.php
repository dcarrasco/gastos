<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Relation extends Field
{
    protected $relatedResource = '';
    protected $relationConditions = [];

    /**
     * Constructor de la clase
     * @param string $name            Nombre o label de la clase
     * @param string $field           Campo
     * @param string $relatedResource Nombre del recurso relacionado
     */
    public function __construct($name = '', $field = '', $relatedResource = '')
    {
        $field = empty($field) ? $name : $field;
        $this->relatedResource = empty($relatedResource) ? $field : $relatedResource;

        parent::__construct($name, $field);
    }

    /**
     * Fija las condiciones de la relacion
     * @param  array $relationConditions
     * @return Relation
     */
    public function relationConditions($relationConditions = [])
    {
        $this->relationConditions = $relationConditions;

        return $this;
    }

    /**
     * Genera una nueva instancia de la clase
     * @param  string $name            Nombre o label de la clase
     * @param  string $field           Campo
     * @param  string $relatedResource Nombre del recurso relacionado
     * @return Field
     */
    public static function make($name = '', $field = '', $relatedResource = '')
    {
        return new static($name, $field, $relatedResource);
    }

    /**
     * Devuelve recurso relacionado con valores
     * @param  Model  $model
     * @return Resource
     */
    public function getRelation(Model $model)
    {
        $modelList = $model->{$this->getField()};

        return (new $this->relatedResource)->injectModelList($modelList);
    }

    /**
     * Recupera objetos del recurso relacionado
     * @param  Request       $request
     * @param  Resource|null $resource
     * @param  string        $field
     * @param  array         $conditions
     * @return array
     */
    protected function getRelatedListModels(Request $request, Resource $resource = null, $conditions = [])
    {
        return (new $this->relatedResource)
            ->resourceOrderBy($request)
            ->model()
            ->where($this->getResourceFilter($resource, $conditions))
            ->get();
    }

    /**
     * Recupera elementos del recurso relacionado
     * @param  Request       $request
     * @param  Resource|null $resource
     * @param  string        $field
     * @param  array         $conditions
     * @return array
     */
    public function getRelationOptions(Request $request, Resource $resource = null, $field = '', $conditions = [])
    {
        $relation = $this->getRelatedListModels($request, $resource, $conditions);

        return $relation->mapWithKeys(function($model) use ($request) {
            return [$model->getKey() =>
                (new $this->relatedResource)->injectModel($model)->title($request)
            ];
        })->all();
    }

    /**
     * Devuelve arreglo con las condiciones de la relacion
     * @param  Resource $resource
     * @param  array    $conditions
     * @return array
     */
    protected function getResourceFilter(Resource $resource, $conditions = [])
    {
        return collect($conditions)->map(function($condition) use ($resource) {
            if (strpos($condition, '@field_value:') !== false) {
                list($label, $field, $defaul) = explode(':', $condition);
                return $resource->model()->{$field};
            }
        })
        ->all();
    }
}
