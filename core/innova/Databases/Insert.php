<?php

namespace Innova\Databases;

class Insert extends Permissions
{
    public function __construct(
        private readonly string $table,
        private array $parameters = [],
        private readonly string $type = PlaceHolders::TYPE[2]
    )
    {
    }
    public function insertRow(): int|false
    {
        //get all parameters keys
        $parametersKeys = array_keys($this->parameters);

        //build placeholders
        $place = new PlaceHolders();
        $placeholder = $place->buildPlaceHolders($parametersKeys, $this->type);

        //query
        $query = "INSERT INTO $this->table $placeholder";

        //bind params to query
        $statement = $place->bindParams($query, $this->parameters);

        //check permission against controller
        if($this->checkQueryAllowed($statement))
        {
            $statement->execute();
            return $place->getPdoConnection()->lastInsertId() ?? false;
        }
        return false;
    }

    public static function insert(string $table, array $parameters, string $type = PlaceHolders::TYPE[2]): int|false
    {
        return (new static($table, $parameters,$type))->insertRow();
    }
}