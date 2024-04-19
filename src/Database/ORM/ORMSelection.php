<?php

namespace MvcliteCore\Database\ORM;

use MvcliteCore\Database\Database;
use MvcliteCore\Database\Exceptions\NegativeOrNullLimitException;
use MvcliteCore\Database\Exceptions\RelationshipDoesNotExistException;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Models\Model;
use MvcliteCore\Models\ModelCollection;

/**
 * ORM selection query class.
 *
 * Allows to create a new select query using MVCLite ORM.
 *
 * @see ORMQuery
 * @author belicfr
 */
class ORMSelection extends ORMQuery
{
    private const BASE_SQL_QUERY_TEMPLATE
        = "SELECT %s FROM %s";

    private const WHERE_CLAUSE_TEMPLATE
        = "WHERE %s";

    private const ORDER_BY_CLAUSE_TEMPLATE
        = "ORDER BY %s";

    /** Table columns used by query. */
    private array $columns;

    /** Linked relationships. */
    private array $relationships;

    /** Given WHERE clauses. */
    private array $conditions;

    /** Given ORDER BY clauses. */
    private array $ordering;

    /** Given LIMIT clause. */
    private ?int $limit;

    public function __construct(string $modelClass, array $columns)
    {
        parent::__construct($modelClass);

        $this->columns = $columns;
        $this->relationships = [];
        $this->conditions = [];
        $this->ordering = [];
        $this->limit = null;

        $sqlQueryBase = sprintf(self::BASE_SQL_QUERY_TEMPLATE,
            $this->getImplodedColumns(),
            ($this->getModelClass())::getTableName());

        $this->addSql($sqlQueryBase);

    }

    /**
     * @return array Table columns used by query
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return string Imploded table columns used by query
     */
    protected function getImplodedColumns(): string
    {
        return implode(', ', $this->getColumns());
    }

    /**
     * @return array Relationships to use
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }

    /**
     * Includes relationships to current query.
     *
     * @param string ...$relationships Relationships to use
     * @return $this
     */
    public function with(string ...$relationships): ORMSelection
    {
        $this->relationships = $relationships;

        return $this;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function hasConditions(): bool
    {
        return count($this->getConditions());
    }

    public function getOrdering(): array
    {
        return $this->ordering;
    }

    public function hasOrdering(): bool
    {
        return count($this->getOrdering());
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function hasLimit(): bool
    {
        return $this->limit !== null;
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
    public function where(string $column, string $operatorOrValue, ?string $value = null): ORMSelection
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

    public function orWhere(string $column, mixed $operatorOrValue, mixed $value = null): ORMSelection
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
     * Add an order by clause to current query.
     *
     * @param string $column
     * @param string $order
     * @return $this Current ORM query instance
     */
    public function orderBy(string $column, string $order = "ASC"): ORMSelection
    {
        $order = strtoupper($order);

        $orderingClause = $this->hasOrdering()
            ? ", $column $order"
            : "ORDER BY $column $order";

        $this->addSql($orderingClause);
        $this->ordering[] = $orderingClause;

        return $this;
    }

    /**
     * Add a limit clause to current query.
     *
     * @param int $limit Maximum lines to return
     * @return $this Current ORM query instance
     * @throws NegativeOrNullLimitException If given limit value is
     *                                      <= 0
     */
    public function limit(int $limit): ORMSelection
    {
        if ($limit <= 0)
        {
            throw new NegativeOrNullLimitException($this->getSqlQuery());
        }

        $this->limit = $limit;

        return $this;
    }

    /**
     * Send generated SQL query by using Database class.
     * It returns the SQL query results as a models collection object.
     *
     * @return ModelCollection Query execution result collection
     */
    public function execute(): ModelCollection
    {
        $query = Database::query($this->getSql(), ...$this->getParameters());
        $result = [];

        while ($line = $query->get())
        {
            $lineObject = new ($this->getModelClass());

            foreach ($line as $columnName => $columnValue)
            {
                if (!in_array($columnName, $lineObject->getHiddenAttributes()))
                {
                    $lineObject->addPublicAttribute($columnName, $columnValue);
                }
            }

            foreach ($this->getRelationships() as $relationship)
            {
                if (!method_exists($lineObject, $relationship))
                {
                    $error = new RelationshipDoesNotExistException($relationship);
                    $error->render();
                }

                $relationshipRunning = call_user_func([$lineObject, $relationship]);
                $lineObject->addPublicAttribute($relationship, $relationshipRunning);
            }

            $result[] = $lineObject;
        }

        return new ModelCollection($result);
    }
}