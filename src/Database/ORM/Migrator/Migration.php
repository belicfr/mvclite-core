<?php

namespace MvcliteCore\Database\ORM\Migrator;

class Migration
{
    private string $tableName;

    private array $structure;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->structure = [];
    }

    /**
     * @return string Table name
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return array Table structure
     */
    public function getStructure(): array
    {
        return $this->structure;
    }

    /**
     * Create a table.
     *
     * @param string $tableName
     * @param array $structure
     * @return CreationMigration
     */
    public static function create(string $tableName, array $structure): CreationMigration
    {
        return new CreationMigration($tableName, $structure);
    }
}