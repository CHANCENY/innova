<?php

namespace Innova\Databases;

use Innova\Configs\ConfigHandler;
use Innova\Exceptions\ExceptionDatabaseConnection;

/**
 * @file
 * File contain Table Class.
 */

/**
 * @class This class is for Tables functionality.
 */
class Tables
{
    /**
     * Runs system_storage configuration to install all table with install true.
     * @return bool
     * @throws ExceptionDatabaseConnection
     */
    public function initialization(): bool
    {
        $tablesSchema = ConfigHandler::config("system_storages");
        if(!empty($tablesSchema))
        {
            $allTables = "";
            foreach ($tablesSchema as $key=>$schema)
            {
                if(!empty($schema['install']) && $schema['install'] === true)
                {
                    $tableToCreate = $this->tableInterpreter($schema);
                    $allTables .= $tableToCreate. ";";
                }
            }
            $allTables = trim($allTables, ";");
            if(!empty($allTables))
            {
                return !empty(Database::database()->query($allTables));
            }
            return false;
        }
        return false;
    }

    /**
     * Create the Create query from schema.
     * @param array $tableSchema
     * @return string|null
     * String Create query made from schema.
     */
    public function tableInterpreter(array $tableSchema): string|null
    {
        $tableName = $tableSchema['table'] ?? null;
        $columns = $tableSchema['columns'];
        if(!empty($tableName) && !empty($columns))
        {
            $columnsLine = null;
            foreach ($columns as $key=>$column)
            {
                $attributeLine = "";
               foreach ($column as $k=>$item)
               {
                   $attributeLine .= $this->transformer($item). " ";
               }
                $columnsLine .= $key . " " . $attributeLine. ", ";
            }
            $columnsLine = trim($columnsLine);
            $columnsLine = trim($columnsLine, ",");
            $columnsLine = "$tableName ($columnsLine)";
            return "CREATE TABLE IF NOT EXISTS ". $columnsLine;
        }
        return null;
    }

    /**
     * Changing from user string ie Not Empty to NOT NULL for mysql.
     * @param $attribute
     * @return string
     * Returns Interpreted string of user simple string.
     */
    public function transformer($attribute): string
    {
        return match (strtolower($attribute))
        {
            "number" => "int(11)",
            "short text" => "varchar(50)",
            "long text" => "text",
            "short" => "boolean",
            "file large" => "longblob",
            "file medium" => "mediumblob",
            "file small" => "smallblob",
            "primary key" => "primary key",
            "not empty" => "not null",
            "empty" => "null",
            "time create", "time update" => "TIMESTAMP",
            "on create" => "DEFAULT CURRENT_TIMESTAMP",
            "on update" =>"DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            default => $attribute
        };
    }

    /**
     * collects table definitions for from Configuration file system_storage
     * @return bool
     * True if tables from schema installed.
     */
    public static function installTables(): bool
    {
        return (new static())->initialization();
    }

    /**
     * Creating tables given from schema.
     * @param array $tableSchemas
     * @return bool
     * @throws ExceptionDatabaseConnection
     */
    public function createTable(array $tableSchemas = []): bool
    {
        if(empty($tableSchemas))
        {
           return $this->initialization();
        }

        $allTables = "";
        foreach ($tableSchemas as $key=>$schema)
        {
            if(!empty($schema['install']) && $schema['install'] === true)
            {
                $tableToCreate = $this->tableInterpreter($schema);
                $allTables .= $tableToCreate. ";";
            }
        }
        $allTables = trim($allTables, ";");
        if(!empty($allTables))
        {
            return !empty(Database::database()->query($allTables));
        }
        return true;
    }

    /**
     * Delete tables in db
     * @param string $tableName Table name to delete
     * @throws ExceptionDatabaseConnection
     */
    public function deleteTable(string $tableName): bool
    {
        $tablesSchema = ConfigHandler::config("system_storages");
        if(!empty($tablesSchema))
        {
            foreach ($tablesSchema as $key=>$item)
            {
                if(!empty($item['table']) && $item['table'] === $tableName)
                {
                    $result = !empty(Database::database()->query("DROP TABLE $tableName"));
                    return $this->update(false, $tableName);
                }
            }
        }
        return false;
    }

    /**
     * Empty table give completely.
     * @param string $tableName Empty this table
     * @return bool
     * @throws ExceptionDatabaseConnection
     */
    public function emptyTable(string $tableName): bool
    {
        $tablesSchema = ConfigHandler::config("system_storages");
        if(!empty($tablesSchema))
        {
            foreach ($tablesSchema as $key=>$item)
            {
                if(!empty($item['table']) && $item['table'] === $tableName)
                {
                    return !empty(Database::database()->query("TRUNCATE TABLE $tableName"));
                }
            }
        }
        return false;
    }


    /**
     * To create table from schema definitions.
     * @param array $tableSchemas Array of table schema.
     * @return bool
     * True if table created.
     */
    public static function create(array $tableSchemas): bool
    {
        return (new static())->createTable($tableSchemas);
    }

    /**
     * Static for delete table.
     * @param string $tableName
     * @return bool
     * @throws ExceptionDatabaseConnection
     */
    public static function removeTable(string $tableName): bool
    {
        return (new static())->deleteTable($tableName);
    }

    /**
     * Static for Empty table.
     * @param string $tableName
     * @return bool
     * @throws ExceptionDatabaseConnection
     */
    public static function clearTable(string $tableName): bool
    {
        return (new static())->emptyTable($tableName);
    }

    public function update(bool $value, $table): bool
    {
        $configuration = ConfigHandler::config("system_storages");
        if(!empty($configuration))
        {
            foreach ($configuration as $key=>$item)
            {
                if(!empty($item['table']) && $item['table'] === $table)
                {
                    $configuration[$key]['install'] = $value;
                }
            }
        }
        ConfigHandler::configRemove("system_storages");
        return ConfigHandler::create("system_storages", $configuration);
    }
}