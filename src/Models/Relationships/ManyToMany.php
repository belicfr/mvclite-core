<?php

namespace MvcliteCore\Models\Relationships;

use MvcliteCore\Database\Database;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\Precepts\Naming;
use MvcliteCore\Models\Model;
use MvcliteCore\Models\Relationships\ModelRelationship;

class ManyToMany extends ModelRelationship
{
    /** Relationship children. */
    private array $children;

    private string $relationshipTableName;

    private string $leftColumnName;

    private string $rightColumnName;

    public function __construct(Model $leftModel,
                                string $rightModel,
                                string $relationshipTableName,
                                ?string $customLeftColumnName = null,
                                ?string $customRightColumnName = null)
    {
        parent::__construct($leftModel, $rightModel);

        $rightModelTableName = (new $rightModel())::getTableName();

        $this->relationshipTableName = "rel__$relationshipTableName";
        $this->leftColumnName = $customLeftColumnName ?? "id_{$leftModel::getTableName()}";
        $this->rightColumnName = $customRightColumnName ?? "id_$rightModelTableName";
    }

    /**
     * @return array Relationship children
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return string Relationship table name
     */
    public function getRelationshipTableName(): string
    {
        return $this->relationshipTableName;
    }

    /**
     * Returns relationship children.
     *
     * @return array
     */
    public function run(): array
    {
        $leftModelTableName = (new ($this->getLeftModel())())::getTableName();
        $rightModelTableName = (new ($this->getRightModel())())::getTableName();

        $related = Database::query("SELECT r.*
                                             FROM {$leftModelTableName} l
                                             INNER JOIN {$this->relationshipTableName} rl
                                             ON rl.{$this->leftColumnName} = l.id
                                             INNER JOIN {$rightModelTableName} r
                                             ON rl.{$this->rightColumnName} = r.id
                                             WHERE l.id = ?",
                                   $this->getLeftModel()->publish()["id"]);

        return $this->children = $related->getAll();
    }
}