<?php

namespace MvcliteCore\Models;

use JsonSerializable;
use MvcliteCore\Database\ORM\ORMDeletion;
use MvcliteCore\Database\ORM\ORMInsertion;
use MvcliteCore\Database\ORM\ORMSelection;
use MvcliteCore\Database\ORM\ORMUpdate;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\Precepts\Naming;
use MvcliteCore\Models\Relationships\BelongsTo;
use MvcliteCore\Models\Relationships\HasMany;
use MvcliteCore\Models\Relationships\HasOne;

use Symfony\Component\String\Inflector\EnglishInflector;

class Model implements JsonSerializable
{
    /**
     * Public attributes that will be encoded
     * to JSON for View usage.
     */
    private array $publicAttributes;

    /**
     * Attributes to hide during model publishing.
     */
    private array $hidden = [];

    public function __construct(array $hidden = [])
    {
        $this->publicAttributes = [];
        $this->hidden = $hidden;
    }

    /**
     * @return array Current public attributes
     * @deprecated Use {@see Model::publish()} instead
     */
    public function getPublicAttributes(): array
    {
        return $this->publish();
    }

    /**
     * @return array Current public attributes
     */
    public function publish(): array
    {
        return $this->publicAttributes;
    }

    /**
     * Adds a new public attribute.
     *
     * @param string $key
     * @param mixed $value
     * @return array Updated public attributes array
     */
    public function addPublicAttribute(string $key, mixed $value): array
    {
        $this->publicAttributes[$key] = $value;

        return $this->getPublicAttributes();
    }

    /**
     * @return string Model table name
     */
    public static function getTableName(): string
    {
        $inflector = new EnglishInflector();

        return $inflector->pluralize(Naming::camelToSnake(Naming::getClassName(static::class)))[0];
    }

    /**
     * @return array Attributes to hide
     */
    public function getHiddenAttributes(): array
    {
        return $this->hidden;
    }

    /*
     * ******  MVCLite MODELS RELATIONSHIPS  ******
     */

    public function belongsTo(string $model, ?string $customColumnName = null): ?Model
    {
        return (new BelongsTo($this, $model, $customColumnName))
            ->run();
    }

    public function hasOne(string $model, ?string $customColumnName = null): ?Model
    {
        return (new HasOne($this, $model, $customColumnName))
            ->run();
    }

    public function hasMany(string $model, ?string $customColumnName = null): array
    {
        return (new HasMany($this, $model, $customColumnName))
            ->run();
    }

    /*
     * ************  MVCLite ORM PART  ************
     */

    public static function create(array $values): Model
    {
        $insertion = new ORMInsertion(static::class, $values);

        return $insertion->execute();
    }

    public static function select(string ...$columns): ORMSelection
    {
        if (!count($columns))
        {
            $columns = ['*'];
        }

        return new ORMSelection(static::class, $columns);
    }

    public static function getById(int $id, string ...$columns): ORMSelection
    {
        if (!count($columns))
        {
            $columns = ['*'];
        }

        $ormClause = new ORMSelection(static::class, $columns);
        $ormClause->where("id", $id);

        return $ormClause;
    }

    public function update(array $updates): Model
    {
        $updateQuery = new ORMUpdate(static::class);

        foreach ($updates as $column => $value)
        {
            $updateQuery->addUpdate($column, $value);
            $this->publicAttributes[$column] = $value;
        }

        $updateQuery->where("id", $this->getPublicAttributes()["id"])
                    ->execute();

        return $this;
    }

    public function delete(): void
    {
        $deleteQuery = new ORMDeletion(static::class);
        $deleteQuery->where("id", $this->getPublicAttributes()["id"])
                    ->execute();
    }

    /*
     * ************ JSON SERIALIZATION ************
     */

    public function jsonSerialize(): mixed
    {
        return $this->getPublicAttributes();
    }
}