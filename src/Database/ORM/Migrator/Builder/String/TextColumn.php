<?php

namespace MvcliteCore\Database\ORM\Migrator\Builder\String;

class TextColumn extends StringColumn
{
    private const DEFINITION_TEMPLATE = "`%s` TEXT %s";

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * @return string Column SQL creation script
     */
    public function getCreationSql(): string
    {
        return sprintf(self::DEFINITION_TEMPLATE,
                       $this->getName(),
                       $this->getConstraintsAsString());
    }

    /**
     * Creates new column for migration.
     *
     * @param string $name Column name
     * @return TextColumn New column
     */
    public static function make(string $name): TextColumn
    {
        return new TextColumn($name);
    }
}