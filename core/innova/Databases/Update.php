<?php

namespace Innova\Databases;

class Update extends Permissions
{
    public function __construct(
        private readonly string $table,
        private array $by,
        private array $parameters,
        private readonly string $type = PlaceHolders::TYPE[3]
    )
    {
    }

    public function save(): bool
    {
        //get all parameters keys
        $parametersKeys = array_keys($this->parameters);

        //build placeholders
        $place = new PlaceHolders();
        $placeholder = $place->buildPlaceHolders($parametersKeys, $this->type);
        $placeholder2 = $place->buildPlaceHolders(array_keys($this->by),$this->type);

        //query
        $query = "UPDATE $this->table SET $placeholder WHERE $placeholder2";
        $all = array_merge($this->parameters, $this->by);
        $statement = $place->bindParams($query, $all);

        //check permission against controller
        if($this->checkQueryAllowed($statement))
        {
            return $statement->execute();
        }
        return false;
    }

    public static function update(string $table, array $params, array $by): bool
    {
        return (new static($table,$by,$params))->save();
    }
}