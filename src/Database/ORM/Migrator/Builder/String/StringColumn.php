<?php

namespace MvcliteCore\Database\ORM\Migrator\Builder\String;

use MvcliteCore\Database\ORM\Migrator\Builder\Column;
use MvcliteCore\Database\ORM\Migrator\Builder\ColumnType;

class StringColumn extends Column implements ColumnType
{
    private $type;

    public function __construct(string $name)
    {
        parent::__construct($name);

        //
    }

    public function asVarchar(): VarcharColumn
    {
        return new VarcharColumn($this->getName());
    }

    public function asText(): TextColumn
    {
        return new TextColumn($this->getName());
    }

    /**
     * Creates new column for migration.
     *
     * @param string $name Column name
     * @return StringColumn New column
     */
    public static function make(string $name): StringColumn
    {
        return new StringColumn($name);
    }
}