<?php

namespace Innova\Databases;

class PlaceHolders
{
    const TYPE = [
        1 => "SELECT",
        2 => "INSERT",
        3 => "UPDATE",
        4 => "DELETE"
    ];

    const GATELOGIC = [
        1 => "AND",
        2 => "OR"
    ];
    private \PDO $pdoConnection;

    /**
     * @return \PDO
     */
    public function getPdoConnection(): \PDO
    {
        return $this->pdoConnection;
    }

    public function buildPlaceHolders(array $parameterKeys, string $type = self::TYPE[1], string $logic = self::GATELOGIC[1]): string
    {
        $placeholderLine = "";
        $columns = "";
        $values = "";
        foreach ($parameterKeys as $key=>$parameter)
        {
            if($type === "SELECT"){
                $placeholderLine .= "$parameter = :$parameter $logic ";
            }
            if($type === "INSERT")
            {
                $columns .= "$parameter,";
                $values .= ":$parameter,";
            }
            if($type === "UPDATE")
            {
                $placeholderLine .= "$parameter = :$parameter, ";
            }
            if($type === 'DELETE')
            {
                $placeholderLine .= "$parameter = :$parameter";
            }
        }
        if($type === "SELECT")
        {
            $placeholderLine = trim($placeholderLine);
            $placeholderLine = str_ends_with($placeholderLine, "AND") ?
                substr($placeholderLine,0, strlen($placeholderLine) - 3) :
                substr($placeholderLine,0, strlen($placeholderLine) - 2);
            $placeholderLine ="WHERE ". trim($placeholderLine);
        }
        if($type === "INSERT")
        {
            $columns = trim($columns, ",");
            $values = trim($values, ",");
            $placeholderLine = "($columns) VALUES ($values)";
        }
        if($type === "UPDATE")
        {
            $placeholderLine = trim($placeholderLine);
            $placeholderLine = trim($placeholderLine, ",");
        }
        return $placeholderLine;
    }

    public function bindParams($query, $data): \PDOStatement
    {
        $keys = array_keys($data);
        $total = count($keys);
        $this->pdoConnection = Database::database();
        $pdoStatement = $this->pdoConnection->prepare($query);
        for ($i = 0; $i < $total; $i++)
        {
            $pdoStatement->bindParam(":{$keys[$i]}", $data[$keys[$i]]);
        }
        return $pdoStatement;
    }

    public function buildColumnAlias(array $parameters): string
    {
        $line = "";
        foreach ($parameters as $key=>$value)
        {
            $line .= "$key AS $value, ";
        }
        $line = trim($line);
        return trim($line, ",");
    }
}