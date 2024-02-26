<?php

namespace MvcliteCore\Database\ORM;

use MvcliteCore\Database\Database;
use MvcliteCore\Database\Exceptions\NegativeOrNullLimitException;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Models\Model;
use MvcliteCore\Models\ModelCollection;

/**
 * ORM deletion query class.
 *
 * Allows to delete an existing line using MVCLite ORM.
 *
 * @see ORMQuery
 * @author belicfr
 */
class ORMDeletion extends ORMQuery
{
    private const BASE_SQL_QUERY_TEMPLATE
        = "DELETE FROM %s";

    /** Given WHERE clauses. */
    private array $conditions;

    public function __construct(string $modelClass)
    {
        parent::__construct($modelClass);

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
     * Add a where condition clause to current query.
     *
     * @param string $column Column concerned by condition
     * @param string $operatorOrValue Condition operator if there are three arguments;
     *                                else condition value
     * @param string|null $value Condition value if there are three arguments;
     *                           else NULL
     * @return $this Current ORM query instance
     */
    public function where(string $column, string $operatorOrValue, ?string $value = null): ORMDeletion
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

    public function orWhere(string $column, mixed $operatorOrValue, mixed $value = null): ORMDeletion
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