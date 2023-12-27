<?php

namespace Innova\Databases;

use Innova\Configs\ConfigHandler;

class Permissions
{
    public function checkQueryAllowed(\PDOStatement $statement): bool
    {
        $thisRoute = active_controller();
        $controller = $thisRoute->getController();

        $query = $statement->queryString;
        // Use a regular expression to extract table names
        $matches = [];
        $pattern = '/(?:FROM|JOIN|INTO|UPDATE|DELETE|DROP|CREATE)\s+([a-zA-Z_]+)(?:\s+AS\s+[a-zA-Z_]+)?\s+/i';
        preg_match_all($pattern, $query, $matches);
        $tableNames = array_unique($matches[1]);

        $databaseConnect = $controller['database']['database_connect'] ?? 0;
        $databaseTableAllowed = $controller['database']['database_tables_allowed'] ?? [];
        $operationAllowed = $controller['database']['allowed_query'];

        // Find elements in array2 that are also in array1
        $commonElements = array_intersect($tableNames, $databaseTableAllowed);

        if(!empty($databaseConnect))
        {
            if(!empty($commonElements))
            {
                // Define regular expressions for different SQL operations
                $toCheckFor = [];
                foreach ($operationAllowed as $key=>$value)
                {
                    $operator = strtoupper($value);
                    $toCheckFor[$operator] = '/\b'. $operator . '\b/i';
                }
                // Check for each SQL operation

                $foundOperations = [];
                foreach ($toCheckFor as $operation => $pattern) {
                    if (preg_match($pattern, $query)) {
                        $foundOperations[] = strtolower($operation);
                    }
                }
                return !empty($foundOperations);
            }else{
                if(str_starts_with($query, "DELETE FROM"))
                {
                    $listing = explode(" ", $query);
                    $table = $listing[2] ?? null;
                    if(in_array($table, $databaseTableAllowed))
                    {
                        return true;
                    }
                }
                if(in_array("all", $databaseTableAllowed))
                {
                    return true;
                }
            }
        }
        return false;
    }
}