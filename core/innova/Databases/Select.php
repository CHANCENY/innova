<?php

namespace Innova\Databases;

class Select extends Permissions
{
    public function __construct(
        private readonly string $table,
        private array $parameters = [],
        private readonly string $type = PlaceHolders::TYPE[1],
        private readonly string $logic = PlaceHolders::TYPE[1]
    )
    {
    }

    public function selectBy(): array|false
    {
        //get all parameters keys
        $parametersKeys = array_keys($this->parameters);

        //build placeholders
        $place = new PlaceHolders();
        $placeholder = $place->buildPlaceHolders($parametersKeys, $this->type, $this->logic);

        //query
        $query = "SELECT * FROM $this->table $placeholder";

        //bind params to query
        $statement = $place->bindParams($query, $this->parameters);

        //check permission against controller
        if($this->checkQueryAllowed($statement))
        {
            $statement->execute();
            return $statement->fetchAll();
        }
        return false;
    }


    public static function find(string $table, array $parameters, string $type = PlaceHolders::TYPE[1], string $logic = PlaceHolders::GATELOGIC[1]): array|false
    {
       return (new static($table,$parameters,$type,$logic))->selectBy();
    }

    public function selectAll(): array
    {
        $params = "*";
        if(!empty($this->parameters))
        {
           $key = array_keys($this->parameters);
           if(!empty($this->parameters[$key[0]]))
           {
               $params = (new PlaceHolders())->buildColumnAlias($this->parameters);
           }else{
               $params = implode(", ", $this->parameters);
           }
        }
        $query = Database::database()->prepare("SELECT $params FROM $this->table");
        $query->execute();
        return $query->fetchAll() ?? [];
    }

    /**
     * @param string $table
     * @param array $columns columns can be just [uid, name, ...] if you dont want alias but if you need alias set
     * [uid=>alias, name=>alias, ...]
     * @return void
     */
    public static function records(string $table, array $columns = []): array
    {
        return (new static($table, $columns))->selectAll();
    }
}