<?php

namespace MvcliteCore\Database\ORM\Migrator;

use MvcliteCore\Database\Database;
use MvcliteCore\Database\ORM\Migrator\Migration;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;

class CreationMigration extends Migration
{
    private const QUERY_TEMPLATE
        = "CREATE TABLE `%s` (%s)";

    private array $structure;

    public function __construct(string $tableName, array $structure)
    {
        parent::__construct($tableName);

        $this->structure = $structure;

        $this->run();
    }

    /**
     * @return array Table structure
     */
    private function prepareStructure(): array
    {
        $preparedStructure = [];

        foreach ($this->getStructure() as $column)
        {
            $preparedStructure[] = $column->getCreationSql();
        }

        return array_map("trim", $preparedStructure);
    }

    /**
     * @return string Table structure as SQL table structure expression
     */
    private function prepareStructureAsString(): string
    {
        return implode(", ", $this->prepareStructure());
    }

    /**
     * Run migration.
     */
    private function run(): void
    {
        Database::query($this->getSqlQuery());
    }

    /**
     * @return string SQL query
     */
    private function getSqlQuery(): string
    {
        return sprintf(self::QUERY_TEMPLATE,
                       $this->getTableName(),
                       $this->prepareStructureAsString());
    }

    /**
     * @return array Table structure
     */
    public function getStructure(): array
    {
        return $this->structure;
    }
}