<?php

namespace MvcliteCore\Database\ORM\Migrator\Builder\String;

use MvcliteCore\Database\ORM\Migrator\Builder\Column;

class Varchar extends Column
{
    private const DEFINITION_TEMPLATE = "`%s` VARCHAR(%s) %s";

    private const DEFAULT_MAX_LENGTH = 1;

    private int $maxLength;

    public function __construct(string $name, int $maxLength = self::DEFAULT_MAX_LENGTH)
    {
        parent::__construct($name);

        $this->maxLength = $maxLength;
    }

    /**
     * @return string Column SQL creation script
     */
    public function getCreationSql(): string
    {
        return sprintf(self::DEFINITION_TEMPLATE,
                       $this->getName(),
                       $this->getMaxLength(),
                       $this->getConstraintsAsString());
    }

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    public function maxLength(int $length): Varchar
    {
        if ($length > 0)
        {
            $this->maxLength = $length;
        }

        return $this;
    }

    /**
     * Creates new column for migration.
     *
     * @param string $name Column name
     * @return Varchar New column
     */
    public static function make(string $name): Varchar
    {
        return new Varchar($name);
    }
}