<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;
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
     * Resuelve el valor del campo a partir del modelo y del request
     *
     * @param  Model    $model
     * @param  Request  $request
     * @return Field
     */
    public function resolveValue(Model $model, Request $request): HasMany
    {
        $this->value = $this->getValue($model, $request);

        if ($this->hasRelationFields()) {
            $this->relationFields = $this->value
                ->mapWithKeys(function ($model) {
                    return [
                        $model->getKey() => collect($this->relationFields)
                            ->map(function ($relation, $relationName) use ($model) {
                                return $this->makeAttributeField($relationName, $relation, $model);
                            })
                    ];
                });
        }

        return $this;
    }

    /**
     * Genera un elemento Field para almacenar el atributo de la relacion
     * @param  string $nombre
     * @param  array  $relacion
     * @param  Model  $model
     * @return Field
     */
    protected function makeAttributeField(string $nombre, array $relacion, Model $model): Field
    {
        if ($relacion['type'] == 'booleanOptions') {
            return BooleanOptions::make('attribute')
                ->options($relacion['options'])
                ->setRelationName($nombre)
                ->setModelId($model->getKey())
                ->setValue($model->pivot->getAttribute($nombre));
        }
    }

    /**
     * Devuelve colletion con los recursos asociados a la relacion
     *
     * @return Collection
     */
    public function getRelatedResources(): Collection
    {
        return $this->value
            ->mapInto($this->relatedResource);
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        $relatedResources = $this->getRelatedResources();

        if ($relatedResources->count() === 0) {
            return new HtmlString('');
        }

        return $this->hasRelationFields()
            ? $this->getFormattedValueWithAttributes($relatedResources)
            : $this->getSimpleFormattedValue($relatedResources);
    }

    /**
     * Devuelve valor del campo formateado cuando las asociacion HasMany no tiene atributos adicionales
     *
     * @param  Collection $relatedResources
     * @return HtmlString
     */
    protected function getSimpleFormattedValue(Collection $relatedResources): HtmlString
    {
        return new HtmlString("<ul><li>" . $relatedResources->map->title()->implode('</li><li>') . "</li></ul>");
    }

    /**
     * Devuelve valor del campo formateado cuando la asociacion HasMany tiene atributos adicionales
     *
     * @param  Collection $relatedResources
     * @return HtmlString
     */
    protected function getFormattedValueWithAttributes(Collection $relatedResources): HtmlString
    {
        return new HtmlString($this->getAttributesTable($relatedResources));
    }

    /**
     * Genera linea de opciones para un elemento HasMany con attributos
     *
     * @param  Resource $resource
     * @param  bool     $edit
     * @return string
     */
    protected function getAttributesTableRow(Resource $resource, bool $edit): string
    {
        return collect($this->relationFields)
            ->get($resource->model()->getKey())
            ->map(function ($field) use ($edit, $resource) {
                return $edit
                    ? $field->getForm(request(), $resource)->toHtml()
                    : $field->getFormattedValue()->toHtml();
            })
            ->implode('');
    }

    /**
     * Genera columna de edición del atributo
     *
     * @param  Resource $resource
     * @param  bool     $edit
     * @return string
     */
    protected function getAttributsTableEditColumn(Resource $resource, bool $edit): string
    {
        return $edit
            ? "<td class=\"text-center\">"
                . "<input type=\"checkbox\""
                . " name=\"{$this->getDeleteModelField()}[]\""
                . " value=\"{$resource->model()->getKey()}\">"
                . "<input type=\"hidden\" name=\"{$this->name}[]\" value=\"{$resource->model()->getKey()}\">"
                . "</td>"
            : '';
    }

    /**
     * Genera encabezado de tabla de atributos
     * @param  bool   $edit
     * @return string
     */
    protected function getAttributesTableHeader(bool $edit): string
    {
        return '<tr class="border bg-gray-100 px-2">'
            . "<th class=\"text-left px-2 py-1\">{$this->name}</th>"
            . collect($this->relationFields)
                ->first()
                ->map(fn($field) => '<th>' . collect($field->getOptions())->implode('</th><th>') . '</th>')
                ->implode('')
            . ($edit ? '<th>Desasociar</th>' : '')
            . '</tr>';
    }

    /**
     * Devuelve tabla para desplegar asociacion HasMany con atributos adicionales
     *
     * @param  Collection   $relatedResources
     * @param  bool|boolean $edit
     * @return string
     */
    protected function getAttributesTable(Collection $relatedResources, bool $edit = false): string
    {
        $header = $this->getAttributesTableHeader($edit);

        $body = '<tbody>'
            . $relatedResources->map(function ($resource) use ($edit) {
                return '<tr class="border">'
                    . "<td class=\"px-2 py-1\">{$resource->title()}</td>"
                    . $this->getAttributesTableRow($resource, $edit)
                    . $this->getAttributsTableEditColumn($resource, $edit)
                    . '</tr>';
            })
            ->implode('')
            . '</body>';

        return "<table class=\"w-full border text-sm\">{$header}{$body}</table>";
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        if ($this->hasRelationFields) {
            return $this->getAttributesForm($request, $resource, $extraParam);
        }

        return new HtmlString(view('orm.form-input', [
            'type' => 'select',
            'name' => "{$this->name}[]",
            'id' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute)->modelKeys(),
            'options' => $this->getRelationOptions($request, $resource, $this->relationConditions),
            'multiple' => 'multiple',
            'size' => '7'
        ])->render());
    }

    /**
     * Devuelve elemento de formulario para el campo cuando la relacion HasMany tiene atributos
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getAttributesForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $relatedResources = $this->getRelatedResources();

        $availableResources = $this->getRelationOptions($request, $resource, $this->relationConditions)
            ->except($relatedResources->map->model()->map->id);

        return new HtmlString(
            $this->getAttributesTable($relatedResources, true)
            . $this->availableResourcesForm($availableResources, $extraParam)
        );
    }

    /**
     * Genera select para agregar nuevos elementos a la relacion HasMany cuando tiene atributos
     *
     * @param  Collection $availableResources
     * @param  array      $extraParam
     * @return string
     */
    protected function availableResourcesForm(Collection $availableResources, array $extraParam = []): string
    {
        return '<div class="py-2 flex flex-between">'
            . '<span class="mr-2 p-2 whitespace-no-wrap">'
            . trans('orm.add_attribute_has_many') . ' ' . $this->name
            . "</span>"
            . view('orm.form-input', [
                'type' => 'select',
                'name' => "{$this->name}[]",
                'value' => '',
                'id' => $this->attribute,
                'options' => $availableResources,
                'placeholder' => '&mdash;',
            ])->render()
            . '</div>';
    }

    /**
     * Establece que el campo HasMany tiene atributos en la relacion
     *
     * @param  string $field
     * @param  string $type
     * @return Field
     */
    public function relationField(string $field, string $type): HasMany
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
     *
     * @return boolean
     */
    public function hasRelationFields(): bool
    {
        return $this->hasRelationFields;
    }

    /**
     * Recupera el nombre del campo a utilizar en el formulario para eliminar relaciones
     *
     * @return string
     */
    public function getDeleteModelField(): string
    {
        return $this->deleteModelField;
    }

    /**
     * Recupera las caracteristicas de la relacion cuando tiene atributos
     *
     * @return array
     */
    public function getRelationFields(): array
    {
        return $this->relationFields;
    }
}
