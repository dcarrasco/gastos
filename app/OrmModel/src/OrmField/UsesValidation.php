<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;

trait UsesValidation
{
    /** @var string[] */
    protected array $rules = [];

    /**
     * Fija las reglas de validacion del campo
     *
     * @param  mixed $rules
     * @return Field
     */
    public function rules(...$rules): Field
    {
        $this->rules = is_array($rules[0]) ? $rules[0] : $rules;

        return $this;
    }
    /**
     * Devuelve validación del campo
     *
     * @param  Resource $resource
     * @return string[]
     */
    public function getValidation(Resource $resource): array
    {
        return collect($this->rules)
            ->map(fn($rule) => ($rule === 'unique')
                ? 'unique:' . $this->getUniqueRuleParameters($resource)
                : $rule)
            ->all();
    }

    /**
     * Recupera los parametros de regla validacion unique
     *
     * @param  Resource $resource
     * @return string
     */
    protected function getUniqueRuleParameters(Resource $resource): string
    {
        return implode(',', [
            $resource->model()->getTable(),
            $this->attribute,
            $resource->model()->getKey(),
            $resource->model()->getKeyName()
        ]);
    }

    /**
     * Indica si el campo tiene error en bolsa de errores
     *
     * @param mixed    $errors
     * @param Resource $resource
     * @return bool
     */
    public function hasErrors($errors, Resource $resource): bool
    {
        return $errors->has($this->getModelAttribute($resource));
    }

    /**
     * Recupera el texto de error del campo desde la bolsa de errores
     *
     * @param mixed    $errors
     * @param Resource $resource
     * @return string
     */
    public function getErrors($errors, Resource $resource): string
    {
        return $errors->first($this->getModelAttribute($resource));
    }
}
