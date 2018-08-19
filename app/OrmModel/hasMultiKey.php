<?php

namespace App\OrmModel;

use DB;

trait hasMultiKey
{

    public function getKeyFields()
    {
        return $this->getModelFields()
            ->filter(function ($field) {
                return $field->getEsId();
            })
            ->keys();
    }

    public function hasMultiKey()
    {
        return $this->getKeyFields()->count() > 1;
    }

    public function getCompositeKeyFields()
    {
        return "CONCAT_WS('".static::KEY_SEPARATOR."', ".$this->getKeyFields()->implode(',').')';
    }

    public function getKey()
    {
        if ($this->hasMultiKey()) {
            return $this->getKeyFields()
                ->map(function ($key) {
                    return $this->getAttribute($key);
                })
                ->implode($this::KEY_SEPARATOR);
        }

        return $this->getAttribute($this->getKeyName());
    }

    public static function findMultiKey($modelID)
    {
        $objectModel = new static;

        if ($objectModel->hasMultiKey()) {
            $where = $objectModel->getKeyFields()
                ->combine(explode(static::KEY_SEPARATOR, $modelID))
                ->all();

            return new static((array) DB::table($objectModel->table)->where($where)->first());
        }

        return static::findOrNew($modelID);
    }

    public function updateMultiKey($arrValues)
    {
        $values = collect($arrValues);

        if ($this->hasMultiKey()) {
            $keys = $this->getModelFields()
                ->filter(function ($field) {
                    return $field->getEsId();
                })
                ->map(function ($field, $key) use ($values) {
                    return $values->get($key);
                });

            $update = $values->forget($keys->keys()->all())
                ->only($this->getModelFields()->keys()->all());

            return DB::table($this->table)->where($keys->all())->update($update->all());
        }

        return $this->update($arrValues);
    }

    public function belongsToManyMultiKey($related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null)
    {
        if (!is_array($foreignPivotKey) && !is_array($relatedPivotKey)) {
            return belongsToMany($related, $table, $foreignPivotKey, $relatedPivotKey);
        }

        $foreignPivotValues = $this->getKeyFields()
            ->map(function ($field) {
                return $this->getAttribute($field);
            });

        $foreignWhere = collect($foreignPivotKey)->combine($foreignPivotValues)->all();
        $pivotRecords = DB::table($table)->where($foreignWhere)->get();
        $relatedWhere = $pivotRecords->map(function ($pivotRow) use ($relatedPivotKey) {
            return collect($pivotRow)
                ->only(collect($relatedPivotKey))
                ->implode($this::KEY_SEPARATOR);
        });
        $relatedObject = new $related;

        return $relatedObject->whereIn(DB::raw($relatedObject->getCompositeKeyFields()), $relatedWhere->all())->get();
    }
}
