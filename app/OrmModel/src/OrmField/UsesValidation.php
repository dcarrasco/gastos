<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;

trait UsesValidation
{
    protected $rules = [];

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
     * @return string
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
}
