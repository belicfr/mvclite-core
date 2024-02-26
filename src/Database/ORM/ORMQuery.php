<?php

namespace MvcliteCore\Database\ORM;

use MvcliteCore\Models\ModelCollection;

/**
 * Main MVCLite ORM class.
 *
 * Allows to create various ORM query types like selection,
 * insertion or deletion.
 *
 * @see ORMSelection
 * @author belicfr
 */
class ORMQuery
{
    /** Current model class. */
    private string $modelClass;

    /** Generated SQL query. */
    private string $sql;

    private array $parameters;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->sql = "";
        $this->parameters = [];
    }

    /**
     * @return object Current model class
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * @return string Generated SQL query
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function addParameter(mixed $value): void
    {
        $this->parameters[] = $value;
    }

    /**
     * Appends to SQL query the given line.
     *
     * @param string $line
     * @return string Updated SQL query
     */
    public function addSql(string $line): string
    {
        if (strlen($this->getSql()))
        {
            $this->sql .= ' ';
        }

        $this->sql .= $line;

        return $this->getSql();
    }
}