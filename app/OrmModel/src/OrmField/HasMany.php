<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

/** @method self relationConditions(array $relationConditions = []) */
class HasMany extends Relation
{
    /** @var mixed[] */
    protected array $relationFields = [];

    protected bool $hasRelationFields = false;

    protected string $deleteModelField = '__delete-model__';

    /**
     * Constructor de la clase
     *
     * @param  string  $name        Nombre o label de la clase
     * @param  string  $field       Campo
     * @param  string  $relatedOrm  Nombre del recurso relacionado
     */
    public function __construct(string $name, string $field = '', string $relatedOrm = '')
    {
        $this->showOnList = false;
        parent::__construct($name, $field, $relatedOrm);
    }

    /**
     * Resuelve el valor del campo a partir del modelo y del request
     *
     * @param  Model  $model
     * @param  Request  $request
     * @return HasMany
     */
    public function resolveValue(Model $model, Request $request): HasMany
    {
        $this->value = $this->getValue($model, $request);

        if ($this->hasRelationFields()) {
            $this->relationFields = $this->value
                ->mapWithKeys(fn ($model) => [$model->getKey() => collect($this->relationFields)
                    ->map(fn ($relation, $relationName) => $this->makeAttributeField($relationName, $relation, $model)),
                ])
                ->all();
        }

        return $this;
    }

    /**
     * Genera un elemento Field para almacenar el atributo de la relacion
     *
     * @param  string  $nombre
     * @param  mixed[]  $relacion
     * @param  Model  $model
     * @return Field
     */
    protected function makeAttributeField(string $nombre, array $relacion, Model $model): Field
    {
        return BooleanOptions::make('attribute')
            ->options($relacion['options'])
            ->setRelationName($nombre)
            ->setModelId($model->getKey())
            ->setValue($model->pivot->getAttribute($nombre));
    }

    /**
     * Devuelve colletion con los recursos asociados a la relacion
     *
     * @return Collection<array-key, resource>
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
     * @param  Collection<array-key, resource>  $relatedResources
     * @return HtmlString
     */
    protected function getSimpleFormattedValue(Collection $relatedResources): HtmlString
    {
        return new HtmlString('<ul><li>'.$relatedResources->map->title()->implode('</li><li>').'</li></ul>');
    }

    /**
     * Devuelve valor del campo formateado cuando la asociacion HasMany tiene atributos adicionales
     *
     * @param  Collection<array-key, resource>  $relatedResources
     * @return HtmlString
     */
    protected function getFormattedValueWithAttributes(Collection $relatedResources): HtmlString
    {
        return new HtmlString($this->getAttributesTable($relatedResources));
    }

    /**
     * Genera linea de opciones para un elemento HasMany con attributos
     *
     * @param  resource  $resource
     * @param  bool  $edit
     * @return string
     */
    protected function getAttributesTableRow(Resource $resource, bool $edit): string
    {
        return collect($this->relationFields)
            ->get($resource->model()->getKey())
            ->map(fn ($field) => $edit
                ? $field->getForm(request(), $resource)->toHtml()
                : $field->getFormattedValue()->toHtml())
            ->implode('');
    }

    /**
     * Genera columna de edición del atributo
     *
     * @param  resource  $resource
     * @param  bool  $edit
     * @return string
     */
    protected function getAttributsTableEditColumn(Resource $resource, bool $edit): string
    {
        return $edit
            ? '<td class="text-center">'
                .'<input type="checkbox"'
                ." name=\"{$this->getDeleteModelField()}[]\""
                ." value=\"{$resource->model()->getKey()}\">"
                ."<input type=\"hidden\" name=\"{$this->name}[]\" value=\"{$resource->model()->getKey()}\">"
                .'</td>'
            : '';
    }

    /**
     * Genera encabezado de tabla de atributos
     *
     * @param  bool  $edit
     * @return string
     */
    protected function getAttributesTableHeader(bool $edit): string
    {
        return '<tr class="border '.themeColor('thead_bg').' px-2">'
            ."<th class=\"text-left px-2 py-1\">{$this->name}</th>"
            .collect($this->relationFields)
                ->first()
                ->map(fn ($field) => '<th>'.collect($field->getOptions())->implode('</th><th>').'</th>')
                ->implode('')
            .($edit ? '<th>Desasociar</th>' : '')
            .'</tr>';
    }

    /**
     * Devuelve tabla para desplegar asociacion HasMany con atributos adicionales
     *
     * @param  Collection<array-key, resource>  $relatedResources
     * @param  bool  $edit
     * @return string
     */
    protected function getAttributesTable(Collection $relatedResources, bool $edit = false): string
    {
        $header = $this->getAttributesTableHeader($edit);

        $body = '<tbody>'.$relatedResources
            ->map(fn ($resource) => '<tr class="border">'
                ."<td class=\"px-2 py-1\">{$resource->title()}</td>"
                .$this->getAttributesTableRow($resource, $edit)
                .$this->getAttributsTableEditColumn($resource, $edit)
                .'</tr>')
            ->implode('')
            .'</body>';

        return "<table class=\"w-full border text-sm\">{$header}{$body}</table>";
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  resource  $resource
     * @param  string[]  $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        if ($this->hasRelationFields) {
            return $this->getAttributesForm($request, $resource, $extraParam);
        }

        return $this->renderForm([
            'type' => 'select',
            'name' => "{$this->name}[]",
            'id' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute)->modelKeys(),
            'options' => $this->getRelationOptions($request, $resource, $this->relationConditions),
            'multiple' => 'multiple',
            'size' => '7',
        ], $extraParam);
    }

    /**
     * Devuelve elemento de formulario para el campo cuando la relacion HasMany tiene atributos
     *
     * @param  Request  $request
     * @param  resource  $resource
     * @param  string[]  $extraParam
     * @return HtmlString
     */
    public function getAttributesForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $relatedResources = $this->getRelatedResources();

        $availableResources = $this->getRelationOptions($request, $resource, $this->relationConditions)
            ->except($relatedResources->map->model()->map->id);

        return new HtmlString(
            $this->getAttributesTable($relatedResources, true)
            .$this->availableResourcesForm($availableResources, $extraParam)
        );
    }

    /**
     * Genera select para agregar nuevos elementos a la relacion HasMany cuando tiene atributos
     *
     * @param  Collection<array-key, string>  $availableResources
     * @param  string[]  $extraParam
     * @return string
     */
    protected function availableResourcesForm(Collection $availableResources, array $extraParam = []): string
    {
        return '<div class="py-2 flex flex-between">'
            .'<span class="mr-2 p-2 whitespace-no-wrap">'
            .trans('orm.add_attribute_has_many').' '.$this->name
            .'</span>'
            .$this->renderForm([
                'type' => 'select',
                'name' => "{$this->name}[]",
                'value' => '',
                'id' => $this->attribute,
                'options' => $availableResources,
                'placeholder' => '&mdash;',
            ], $extraParam)->toHtml()
            .'</div>';
    }

    /**
     * Establece que el campo HasMany tiene atributos en la relacion
     *
     * @param  string  $field
     * @param  string  $type
     * @return HasMany
     */
    public function relationField(string $field, string $type): HasMany
    {
        $type = json_decode($type, true);
        $fieldType = array_key_first($type);

        $this->relationFields[$field] = [
            'type' => $fieldType,
            'options' => $type[$fieldType],
        ];

        $this->hasRelationFields = true;

        return $this;
    }

    /**
     * Indica si el campo relacion HasMany tiene atributos adicionales
     *
     * @return bool
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
     * @return string[]
     */
    public function getRelationFields(): array
    {
        return $this->relationFields;
    }
}
