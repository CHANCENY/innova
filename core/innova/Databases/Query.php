<?php

namespace Innova\Databases;

use Innova\Exceptions\ExceptionDatabaseConnection;

/**
 * Query Class to access database.
 */
class Query extends Permissions
{
    /**
     * @var \PDO
     */
    private \PDO $connection;
    /**
     * @var array
     */
    private array $message;

    /**
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return false|array
     */
    public function getResult(): false|array
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @var string|null
     */
    private ?string $error;
    /**
     * @var array|false
     */
    private array|false $result;

    /**
     * @throws ExceptionDatabaseConnection
     */
    function __construct(
        private readonly string $query,
        private readonly array $parameters = []
    )
    {
        $this->connection = Database::database();
        $this->error = false;
        $this->message = [];
    }

    /**
     * @return $this
     */
    public function queryRunner(): Query
    {
        $statement = $this->connection->prepare($this->query);
        if(!empty($this->parameters))
        {
            $parametersCopy = $this->parameters;

            $total = count($parametersCopy);
            $keys = array_keys($parametersCopy) ?? [];

            for ($i = 0; $i < $total; $i++)
            {
                $column = str_starts_with($keys[$i], ":")
                    ? $keys[$i] : ":{$keys[$i]}";
                $statement->bindParam($column, $parametersCopy[$keys[$i]]);
            }
        }
        if($this->checkQueryAllowed($statement))
        {
           if($statement->execute()){
               $this->result = $statement->fetchAll(\PDO::FETCH_ASSOC);
           }else{
               $this->error = $statement->errorCode();
               $this->message = $statement->errorInfo();
           }
        }
        return $this;
    }

    /**
     * @throws ExceptionDatabaseConnection
     */
    public static function query(string $query, $parameters = []): Query
    {
        return (new Query($query, $parameters))->queryRunner();
    }
}