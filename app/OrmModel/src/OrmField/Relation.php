<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Relation extends Field
{
    protected string $relatedResource = '';

    /** @var string[] */
    protected array $relationConditions = [];

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
     * @param  string[] $relationConditions
     * @return self
     */
    public function relationConditions(array $relationConditions = []): self
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
     * @return static
     */
    public static function make(string $name = '', string $field = '', string $relatedResource = ''): static
    {
        return new static($name, $field, $relatedResource);
    }

    /**
     * Genera una instancia nueva de un recurso relacionado
     *
     * @param  Model|null $model
     * @return Resource
     */
    public function makeRelatedResource($model = null): Resource
    {
        /** @var Resource */
        $newRelatedResource = new $this->relatedResource($model);

        return $newRelatedResource;
    }

    /**
     * Recupera elementos del recurso relacionado
     *
     * @param  Request   $request
     * @param  Resource  $resource
     * @param  string[]  $conditions
     * @return Collection<int|string, string>
     */
    public function getRelationOptions(Request $request, Resource $resource, array $conditions = []): Collection
    {
        return $this->getRelatedListModels($request, $resource, $conditions)
            ->mapWithKeys(fn($model) => [$model->getKey() => $this->makeRelatedResource($model)->title()]);
    }

    /**
     * Recupera objetos del recurso relacionado
     *
     * @param  Request   $request
     * @param  Resource  $resource
     * @param  string[]  $conditions
     * @return Collection<int, Model>
     */
    protected function getRelatedListModels(Request $request, Resource $resource, array $conditions = []): Collection
    {
        return $this->makeRelatedResource()
            ->applyOrderBy($request)
            ->getModelQueryBuilder()
            ->where($this->getRelationFilter($resource, $conditions))
            ->get();
    }

    /**
     * Devuelve arreglo con las condiciones de la relacion
     *
     * @param  Resource $resource
     * @param  string[] $conditions
     * @return string[]
     */
    protected function getRelationFilter(Resource $resource, array $conditions = []): array
    {
        return collect($conditions)
            ->filter(fn($condition) => strpos($condition, '@field_value:') !== false)
            ->map(fn($condition) => $resource->model()
                ->getAttribute(Str::between($condition, ':', ':')))
            ->all();
    }
}
