<?php

namespace MvcliteCore\Database;

use MvcliteCore\Database\Exceptions\FailedConnectionToDatabaseException;
use MvcliteCore\Database\Exceptions\FailedDatabaseQueryException;
use MvcliteCore\Database\Exceptions\NegativeOrNullStackException;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use PDOException;
use PDOStatement;

/**
 * Secondary database management class that represents
 * a database query by using Database::query() method.
 *
 * This class enriches querying using MVCLite database manager system.
 *
 * @see Database        Main database management class
 * @author belicfr
 */
class DatabaseQuery
{
    /** Current SQL query. */
    private string $sqlQuery;

    /** Current SQL query parameters. */
    private array $parameters;

    /** Current SQL query preparation. */
    private ?PDOStatement $preparation;

    /** Current SQL query execution state. */
    private ?bool $executionState;

    public function __construct(string $sqlQuery, array $parameters)
    {
        $this->sqlQuery = $sqlQuery;
        $this->parameters = $parameters;

        $this->preparation = null;
        $this->executionState = null;

        try
        {
            $this->prepareQuery();
            $this->executeQuery();
        }
        catch (PDOException $e)
        {
            $error = new FailedDatabaseQueryException($sqlQuery);
            $error->render();
        }
    }

    /**
     * Prepare the current SQL query.
     *
     * @return PDOStatement
     */
    private function prepareQuery(): PDOStatement
    {
        global $db;

        return $this->preparation = $db->prepare($this->sqlQuery);
    }

    /**
     * Execute the current SQL query.
     *
     * @return bool If the request sent did not result in any errors
     */
    private function executeQuery(): bool
    {
        return $this->executionState = $this->preparation
                                            ->execute($this->parameters);
    }

    /**
     * @return array SQL query results array
     */
    public function getAll(): array
    {
        return $this->preparation->fetchAll();
    }

    /**
     * @return array|false Current cursor line if exists;
     *                     else FALSE
     */
    public function get(): array|false
    {
        return $this->preparation->fetch();
    }

    /**
     * @return bool|null SQL query execution state if executed;
     *                   else NULL
     */
    public function getExecutionState(): ?bool
    {
        return $this->executionState;
    }

    /**
     * @return int Result lines count
     */
    public function count(): int
    {
        return count($this->getAll());
    }
}