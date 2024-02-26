<?php

namespace MvcliteCore\Database\ORM;

use MvcliteCore\Database\Database;
use MvcliteCore\Database\Exceptions\NegativeOrNullLimitException;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Models\Model;
use MvcliteCore\Models\ModelCollection;

/**
 * ORM updating query class.
 *
 * Allows to update an existing line using MVCLite ORM.
 *
 * @see ORMQuery
 * @author belicfr
 */
class ORMUpdate extends ORMQuery
{
    private const BASE_SQL_QUERY_TEMPLATE
        = "UPDATE %s";

    /** Updates to do. */
    private array $updates;

    /** Given WHERE clauses. */
    private array $conditions;

    public function __construct(string $modelClass)
    {
        parent::__construct($modelClass);

        $this->updates = [];
        $this->conditions = [];

        $sqlQueryBase = sprintf(self::BASE_SQL_QUERY_TEMPLATE,
            ($this->getModelClass())::getTableName());

        $this->addSql($sqlQueryBase);
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function hasConditions(): bool
    {
        return count($this->getConditions());
    }

    /**
     * @return array Updates to do
     */
    public function getUpdates(): array
    {
        return $this->updates;
    }

    /**
     * @return bool If there is an update
     */
    public function hasUpdate(): bool
    {
        return count($this->getUpdates());
    }

    /**
     * Appends an update to current ORM update query.
     *
     * @param string $column
     * @param mixed $value
     * @return $this Current ORM update query
     */
    public function addUpdate(string $column, mixed $value): ORMUpdate
    {
        $update = $this->hasUpdate()
            ? ", $column = ?"
            : "SET $column = ?";

        $this->addSql($update);
        $this->updates[] = $update;
        $this->addParameter($value);

        return $this;
    }

    /**
     * Add a where condition clause to current query.
     *
     * @param string $column Column concerned by condition
     * @param string $operatorOrValue Condition operator if there are three arguments;
     *                                else condition value
     * @param string|null $value Condition value if there are three arguments;
     *                           else NULL
     * @return $this Current ORM query instance
     */
    public function where(string $column, string $operatorOrValue, ?string $value = null): ORMUpdate
    {
        $isOperatorGiven = $value !== null;

        $whereExpression = $isOperatorGiven
            ? "$column $operatorOrValue ?"
            : "$column = ?";

        $sqlWhereClause = $this->hasConditions()
            ? "AND $whereExpression"
            : "WHERE $whereExpression";

        $this->addParameter($isOperatorGiven ? $value : $operatorOrValue);
        $this->addSql($sqlWhereClause);
        $this->conditions[] = $sqlWhereClause;

        return $this;
    }

    public function orWhere(string $column, mixed $operatorOrValue, mixed $value = null): ORMUpdate
    {
        $isOperatorGiven = $value !== null;

        $whereExpression = $isOperatorGiven
            ? "$column $operatorOrValue ?"
            : "$column = ?";

        $sqlWhereClause = $this->hasConditions()
            ? "OR $whereExpression"
            : "WHERE $whereExpression";

        $this->addParameter($isOperatorGiven ? $value : $operatorOrValue);
        $this->addSql($sqlWhereClause);
        $this->conditions[] = $sqlWhereClause;

        return $this;
    }

    /**
     * Send generated SQL query by using Database class.
     */
    public function execute(): void
    {
        Database::query($this->getSql(), ...$this->getParameters());
    }
}