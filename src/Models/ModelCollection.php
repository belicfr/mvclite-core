<?php

namespace MvcliteCore\Models;

use ArrayObject;

/**
 * Allows to better manage models instead of a simple array,
 * by using utilities methods.
 *
 * @author belicfr
 */
class ModelCollection
{
    /** Models array. */
    private array $models;

    public function __construct(array $models = [])
    {
        $this->models = $models;
    }

    /**
     * @return array Collection models
     */
    public function asArray(): array
    {
        return $this->models;
    }

    /**
     * Adds given model instance to collection.
     *
     * @param Model $model
     * @return $this Updated collection instance
     */
    public function add(Model $model): ModelCollection
    {
        $this->models[] = $model;

        return $this;
    }

    /**
     * @param Model $model Model instance to remove
     * @return ModelCollection Updated collection instance
     */
    public function remove(Model $model): ModelCollection
    {
        $modelIndex = array_search($model, $this->asArray());

        if ($modelIndex !== false)
        {
            unset($this->models[$modelIndex]);
        }

        return $this;
    }

    /**
     * @return array Array with only models public attributes
     */
    public function publish(): array
    {
        $public = [];

        foreach ($this->asArray() as $model)
        {
            $public[] = $model->getPublicAttributes();
        }

        return json_decode(json_encode($public));
    }

    /**
     * @param int $index Model index in collection
     * @return Model|null Model object if exists;
     *                    else NULL
     */
    public function get(int $index): ?Model
    {
        return $this->models[$index] ?? null;
    }
}