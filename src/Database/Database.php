<?php

namespace MvcliteCore\Database;

use Exception;
use PDO;

/**
 * Main database system class.
 *
 * This class offers many utilities methods to use
 * connected database securely.
 *
 * @see DatabaseQuery       Query representation
 * @author belicfr
 */
class Database
{
    /** Database credentials. */
    private array $credentials;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Try to connect to database with given credentials.
     *
     * @return bool|PDO PDO object if attempt is successful;
     *                  else FALSE
     */
    public function attemptConnection(): bool|PDO
    {
        $database = null;

        try
        {
            $databaseInformation = $this->credentials["dbms"]
                                 . ":host="
                                 . $this->credentials["host"]
                                 . ";port="
                                 . $this->credentials["port"]
                                 . ";dbname="
                                 . $this->credentials["name"]
                                 . ";charset="
                                 . $this->credentials["charset"];

            $options = [																				 
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $database = new PDO($databaseInformation,
                                $this->credentials["user"],
                                $this->credentials["password"],
                                $options,
                            );

            $state = true;
        }
        catch (Exception $e)
        {
            $state = false;
        }

        return $state
            ? $database
            : false;
    }

    /**
     * Returns a new DatabaseQuery instance with given SQL query.
     *
     * @param string $sqlQuery
     * @return DatabaseQuery
     */
    public static function query(string $sqlQuery, mixed ...$parameters): DatabaseQuery
    {
        return new DatabaseQuery($sqlQuery, $parameters);
    }
}