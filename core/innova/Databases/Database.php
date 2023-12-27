<?php

namespace Innova\Databases;

use Innova\Configs\ConfigHandler;
use Innova\Exceptions\ExceptionDatabaseConnection;
use Innova\modules\Files;
use PDO;
use PDOException;

/**
 *
 */
class Database
{
    /**
     * @var mixed|null
     */
    private mixed $dbName;

    /**
     * DB name.
     * @return mixed
     */
    public function getDbName(): mixed
    {
        return $this->dbName;
    }

    /**
     * DB host name
     * @return mixed
     */
    public function getDbHost(): mixed
    {
        return $this->dbHost;
    }

    /**
     * Db password.
     * @return mixed
     */
    public function getDbPassword(): mixed
    {
        return $this->dbPassword;
    }

    /**
     * @return mixed
     */
    public function getDbUser(): mixed
    {
        return $this->dbUser;
    }

    /**
     * @return mixed
     */
    public function getDbType(): mixed
    {
        return $this->dbType;
    }
    /**
     * @var mixed|string
     */
    private mixed $dbHost;
    /**
     * @var mixed|null
     */
    private mixed $dbPassword;
    /**
     * @var mixed|string
     */
    private mixed $dbUser;
    /**
     * @var mixed|string
     */
    private mixed $dbType;

    /**
     * Connection creation build up.
     */
    public function __construct()
    {
        $dbConfiguration = ConfigHandler::config("database");
        if(!empty($dbConfiguration))
        {
            $this->dbName = $dbConfiguration['dbname'] ?? null;
            $this->dbHost = $dbConfiguration['host'] ?? "localhost";
            $this->dbPassword = $dbConfiguration['dbPassword'] ?? null;
            $this->dbUser = $dbConfiguration['dbUser'] ?? "root";
            $this->dbType = $dbConfiguration['dbType'] ?? "root";
        }
    }

    /**
     * @return PDO
     * @throws ExceptionDatabaseConnection
     */
    public function createConnection(): PDO
    {
        global $connection;
        if(!empty($connection))
        {
            // Check the connection status
            $connectionStatus = $connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);
            if ($connectionStatus === 'Connected') {
                return $connection;
            }
        }
        try {
            // Create a PDO connection
            $connection = new PDO("$this->dbType:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPassword);

            // Set PDO error mode to exception
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Set the fetch mode to ASSOC
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $connection;
        }catch (\Throwable $exception)
        {
            throw new ExceptionDatabaseConnection("Failed to creation Database Connection");
        }
    }

    /**
     * @return void
     */
    public function killConnection(): void
    {
        global $connection;
        if(!empty($connection))
        {
            unset($connection);
        }
    }


    /**
     * @return PDO
     * @throws ExceptionDatabaseConnection
     */
    public static function database(): PDO
    {
        return (new static())->createConnection();
    }

    /**
     * @return bool
     */
    public function createDatabase(): bool
    {
        try {
            // Create a PDO connection without specifying the database name
            $pdo = new PDO("$this->dbType:host=$this->dbHost", $this->dbUser, $this->dbPassword);

            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Now, you can issue SQL commands to create a new database, e.g., for MySQL:
            return !empty($pdo->exec("CREATE DATABASE $this->dbName"));
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function create(): bool
    {
        return (new static())->createDatabase();
    }

    public function deleteDatabase(): bool
    {
        $result = false;
        switch ($this->dbType)
        {
            case "mysql":
            case "pgsql":
            case "sqlsrv":
                $query = "DROP DATABASE $this->dbName";
                $result = !empty(self::database()->query($query));
                break;
            case 'sqlite':
                $query = __DIR__."/$this->dbName.db";
                if(file_exists($query))
                {
                    $result = unlink($query);
                }else{
                    $query = __DIR__."/$this->dbName.sqlite";
                    if(file_exists($query))
                    {
                        $result = unlink($query);
                    }
                }
            default:
                $result = false;
        }
        return  $result;
    }

    public static function removeDatabase(): bool
    {
        return (new static())->deleteDatabase();
    }
}