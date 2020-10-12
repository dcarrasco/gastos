<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Relation;
use Illuminate\Database\Eloquent\Model;

class HasMany extends Relation
{
    protected $relationFields = [];
    protected $hasRelationFields = false;
    protected $deleteModelField = '__delete-model__';


    /**
     * Constructor de la clase
     *
     * @param string $name            Nombre o label de la clase
     * @param string $field           Campo
     * @param string $relatedResource Nombre del recurso relacionado
     */
    public function __construct(string $name = '', string $field = '', string $relatedOrm = '')
    {
        $this->showOnList = false;
        parent::__construct($name, $field, $relatedOrm);
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request)
    {
        $relatedResources = $model->{$this->attribute}->mapInto($this->relatedResource);

        if ($relatedResources->count() === 0) {
            return new HtmlString('');
        }

        return $this->hasRelationFields()
            ? $this->getFormattedValueWithAttributes($relatedResources)
            : $this->getSimpleFormattedValue($relatedResources);
    }

    /**
     * Devuelve valor del campo formateado cuando las asociacion HasMany no tiene atributos adicionales
     * @param  Collection $relatedResources
     * @return HtmlString
     */
    public function getSimpleFormattedValue(Collection $relatedResources): HtmlString
    {
        return new HtmlString("<ul><li>" . $relatedResources->map->title()->implode('</li><li>') . "</li></ul>");
    }

    /**
     * Devuelve valor del campo formateado cuando la asociacion HasMany tiene atributos adicionales
     * @param  Collection $relatedResources
     * @return HtmlString
     */
    public function getFormattedValueWithAttributes(Collection $relatedResources): HtmlString
    {
        return new HtmlString($this->getAttributesTable($relatedResources));
    }

    /**
     * Devuelve tabla para desplegar asociacion HasMany con atributos adicionales
     * @param  Collection   $relatedResources
     * @param  bool|boolean $edit
     * @return string
     */
    protected function getAttributesTable(Collection $relatedResources, bool $edit = false): string
    {
        $header = '<tr class="border bg-gray-100 px-2"><th class="text-left px-2">'.$this->name.'</th>'.
            collect($this->relationFields)->map(function ($field) {
                return '<th>'.collect($field['options'])->implode('</th><th>').'</th>';
            })->implode('')
            .($edit ? '<th>Desasociar</th>' : '')
            .'</tr>';

        $body = '<tbody>'.
            $relatedResources->map(function ($resource) use ($edit) {
                return '<tr class="border"><td class="px-2">'.$resource->title().'</td>'
                    .collect($this->relationFields)->map(function ($relationDef, $relationName) use ($resource, $edit) {
                        return collect($relationDef['options'])->map(function ($option) use ($resource, $relationName, $edit) {
                            $selected = collect(json_decode($resource->model()->pivot->{$relationName}))->contains($option)
                                ? 'checked'
                                : '';
                            $editable = $edit ? '' : 'disabled';
                            $id = $resource->model()->getKey();

                            return "<td class=\"text-center\"><input type=\"checkbox\" name=\"attributes:{$relationName}:{$id}[]\" value=\"{$option}\" {$selected} {$editable}></td>";
                        })->implode('');
                    })->implode('')
                    .($edit ? "<td class=\"text-center\"><input type=\"checkbox\" name=\"{$this->getDeleteModelField()}[]\" value=\"{$resource->model()->getKey()}\"><input type=\"hidden\" name=\"{$this->name}[]\" value=\"{$resource->model()->getKey()}\"></td>" : '')
                    .'</tr>';
            })->implode('')
            .'</body>';

        return '<table class="w-full border">'.$header.$body.'</table>';
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, $extraParam = []): HtmlString
    {
        if ($this->hasRelationFields) {
            return $this->getAttributesForm($request, $resource, $extraParam);
        }

        $extraParam['id'] = $this->attribute;
        $extraParam['class'] = ($extraParam['class'] ?? '') . $this->defaultClass;

        return Form::select(
            "{$this->name}[]",
            $this->getRelationOptions($request, $resource, $this->relationConditions),
            $resource->model()->{$this->attribute}->modelKeys(),
            array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam)
        );
    }

    /**
     * Devuelve elemento de formulario para el campo cuando la relacion HasMany tiene atributos
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getAttributesForm(Request $request, Resource $resource, $extraParam = []): HtmlString
    {
        $relatedResources = $resource->model()->{$this->attribute}->mapInto($this->relatedResource);
        $availableResources = collect($this->getRelationOptions($request, $resource, $this->relationConditions)->all())
            ->except($relatedResources->map->model()->map->id);

        return new HtmlString(
            $this->getAttributesTable($relatedResources, true)
            .$this->availableResourcesForm($availableResources, $extraParam)
        );
    }

    /**
     * Genera select para agregar nuevos elementos a la relacion HasMany cuando tiene atributos
     * @param  Collection $availableResources
     * @param  array      $extraParam
     * @return string
     */
    protected function availableResourcesForm(Collection $availableResources, array $extraParam = []): string
    {
        if ($availableResources->count() == 0) {
            return '';
        }

        $optionsAttributes = ['' => ['disabled']];
        $extraParam['class'] = ($extraParam['class'] ?? '') . $this->defaultClass;

        $optionsIni = collect(['' => new HtmlString('&mdash;')]);
        $availableResources = collect($optionsIni)->union($availableResources);

        return '<div class="py-2 flex flex-between">'
            .'<span class="mr-2 p-2">Agregar</span>'
            .Form::select("{$this->name}[]", $availableResources, null, $extraParam, $optionsAttributes)
            .'</div>';
    }


    /**
     * Establece que el campo HasMany tiene atributos en la relacion
     * @param  string $field
     * @param  string $type
     * @return $this
     */
    public function relationField(string $field, string $type)
    {
        $type = json_decode($type, true);
        $fieldType = array_key_first($type);
        $this->relationFields[$field] = [
            'type' => $fieldType,
            'options' => $type[$fieldType]
        ];

        $this->hasRelationFields = true;

        return $this;
    }

    /**
     * Indica si el campo relacion HasMany tiene atributos adicionales
     * @return boolean
     */
    public function hasRelationFields(): bool
    {
        return $this->hasRelationFields;
    }

    /**
     * Recupera el nombre del campo a utilizar en el formulario para eliminar relaciones
     * @return [type]
     */
    public function getDeleteModelField(): string
    {
        return $this->deleteModelField;
    }

    /**
     * Recupera las caracteristicas de la relacion cuando tiene atributos
     * @return array
     */
    public function getRelationFields(): array
    {
        return $this->relationFields;
    }
}
