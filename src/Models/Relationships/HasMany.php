<?php

namespace MvcliteCore\Models\Relationships;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\Precepts\Naming;
use MvcliteCore\Models\Model;
use MvcliteCore\Models\Relationships\ModelRelationship;

class HasMany extends ModelRelationship
{
    /** Relationship children. */
    private array $children;

    /** Foreign key column name. */
    private string $foreignKeyColumnName;

    public function __construct(Model $leftModel, string $rightModel, ?string $customColumnName = null)
    {
        parent::__construct($leftModel, $rightModel, $customColumnName);

        $this->foreignKeyColumnName
            = $customColumnName ?? "id_" . Naming::camelToSnake(Naming::getClassName($leftModel::class));
    }

    /**
     * @return array Relationship children
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return string Foreign key column name
     */
    public function getForeignKeyColumnName(): string
    {
        return $this->foreignKeyColumnName;
    }

    /**
     * Returns relationship children.
     *
     * @return array
     */
    public function run(): array
    {
        $related = (new (parent::getRightModel()))
            ->select()
            ->where($this->getForeignKeyColumnName(), parent::getLeftModel()->getPublicAttributes()["id"])
            ->execute()
            ->asArray();

        return $this->children = $related;
    }
}