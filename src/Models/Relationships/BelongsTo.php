<?php

namespace MvcliteCore\Models\Relationships;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\Precepts\Naming;
use MvcliteCore\Models\Model;
use MvcliteCore\Models\Relationships\ModelRelationship;

class BelongsTo extends ModelRelationship
{
    /** Relationship child. */
    private ?Model $child;

    /** Foreign key column name. */
    private string $foreignKeyColumnName;

    public function __construct(Model $leftModel, string $rightModel, ?string $customColumnName = null)
    {
        parent::__construct($leftModel, $rightModel);

        $this->foreignKeyColumnName
            = $customColumnName ?? "id_" . Naming::camelToSnake(Naming::getClassName($rightModel));
    }

    /**
     * @return Model|null Relationship child
     */
    public function getChild(): ?Model
    {
        return $this->child;
    }

    /**
     * @return string Foreign key column name
     */
    public function getForeignKeyColumnName(): string
    {
        return $this->foreignKeyColumnName;
    }

    /**
     * Returns relationship child.
     *
     * @return Model|null
     */
    public function run(): ?Model
    {
        $related = (new (parent::getRightModel()))
            ->select()
            ->where("id", parent::getLeftModel()->getPublicAttributes()[$this->getForeignKeyColumnName()]);

        if ($this->hasQueryExtension())
        {
            $this->getQueryExtension()($related);
        }

        $related
            ->execute()
            ->asArray();

        return $this->child = $related[0] ?? null;
    }
}