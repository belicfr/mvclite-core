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

    private string $customLeftColumnName;

    private string $customRightColumnName;

    public function __construct(Model $leftModel,
                                string $rightModel,
                                string $relationshipTableName,
                                ?string $customLeftColumnName = null,
                                ?string $customRightColumnName = null)
    {
        parent::__construct($leftModel, $rightModel);

        $this->relationshipTableName = "rel__$relationshipTableName";
        $this->customLeftColumnName = $customLeftColumnName;
        $this->customRightColumnName = $customRightColumnName;
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
        $leftModelTableName = (new $this->getLeftModel())::getTableName();
        $rightModelTableName = (new $this->getRightModel())::getTableName();

        $related = Database::query("SELECT r.*
                                             FROM {$leftModelTableName} l
                                             INNER JOIN rel__{$this->relationshipTableName} rl
                                             ON rl.{$this->customLeftColumnName} = l.{$this->customLeftColumnName}
                                             INNER JOIN {$rightModelTableName} r
                                             ON rl.{$this->customRightColumnName} = r.{$this->customRightColumnName}");

        return $this->children = $related->getAll();
    }
}