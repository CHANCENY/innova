<?php

namespace Innova\Databases;

class Delete extends Permissions
{
    public function __construct(
        private readonly string $table,
        private readonly array  $by,
        private readonly string $type = PlaceHolders::TYPE[4]
    )
    {
    }

    public function deletion(): bool
    {
        $place = new PlaceHolders();
        $placeholders = $place->buildPlaceHolders(array_keys($this->by), $this->type);
        $query = "DELETE FROM $this->table WHERE $placeholders";
        $statement = $place->bindParams($query, $this->by);

        //check permission against controller
        if($this->checkQueryAllowed($statement))
        {
            return $statement->execute();
        }
        return false;
    }

    public static function delete(string $table, array $by): bool
    {
        return (new static($table, $by))->deletion();
    }
}