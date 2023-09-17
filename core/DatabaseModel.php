<?php

namespace Vendon\core;

use PDO;

abstract class DatabaseModel extends Model
{
    /**
     * @var array
     */
    private array $columnValueMapping = [];

    /**
     * @var string
     */
    private string $whereStatement = '';

    /**
     * @var string
     */
    private string $joinStatement = '';

    /**
     * @var string
     */
    private string $selectStatement = '';

    /**
     * @param $columns
     * @return bool|string
     */
    //vvv Retrieve all the rows from the table , in $columns argument it is possible to specify which columns to return
    public static function all($columns = ['*']): bool|string
    {
        $tableName = static::tableName();
        $columnNames = in_array('*', $columns) ? '*' : implode(",", $columns);

        $statement = self::prepare("SELECT $columnNames FROM $tableName");
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($result);
    }

    //vvv abstract function for setting the table name inside Model Class
    abstract public static function tableName(): string;

    /**
     * @param $sql
     * @return bool|\PDOStatement
     */
    //vvv Preparing sql statement for execution
    public static function prepare($sql): bool|\PDOStatement
    {
        return Application::$app->database->pdo->prepare($sql);
    }

    /**
     * @return array|null
     */
    //vvv Saving the current object's data to the corresponding database table.
    public function save(): ?array
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn($attributes) => ":$attributes", $attributes);

        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ")
        VALUES(" . implode(',', $params) . ")");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $success = $statement->execute();

        //vvv Handling the result of a successful database insertion operation.
        if ($success) {
            $lastInsertId = Application::$app->database->pdo->lastInsertId();
            $this->id = (int)$lastInsertId;

            $insertedData = [];

            foreach ($attributes as $attribute) {
                $insertedData[$attribute] = $this->{$attribute};
            }

            $insertedData['id'] = (int)$lastInsertId;

            return $insertedData;
        }

        return null;
    }

    //vvv abstract function for setting the attributes inside Model Class
    abstract public function attributes(): array;

    /**
     * @param $columns
     * @return $this
     */
    //vvv Constructing a SELECT query for the current database table with specified columns.
    //vvv returning this for method chaining
    public function select($columns = ['*']): static
    {
        $tableName = $this->tableName();
        $columnNames = in_array('*', $columns) ? '*' : implode(",", $columns);
        $this->selectStatement = ("SELECT $columnNames FROM $tableName");

        return $this;
    }

    /**
     * @param $column
     * @param $comparingSign
     * @param $value
     * @return $this
     */
    //vvv Constructing a WHERE part of the query for specified column, comparison and value
    //vvv returning this for method chaining
    public function where($column, $comparingSign, $value): static
    {
        $allowedComparingSigns = ['=', '<', '>', '<=', '>=', '!='];

        if (!in_array($comparingSign, $allowedComparingSigns)) {
            throw new InvalidArgumentException('Invalid comparison operator');
        }

        if ($this->whereStatement === '') {
            $this->whereStatement .= " WHERE $column $comparingSign :$column";
            $this->columnValueMapping[$column] = $value;
        } else {
            $this->whereStatement .= " AND $column $comparingSign :$column";
            $this->columnValueMapping[$column] = $value;
        }

        return $this;
    }

    /**
     * @param $joinType
     * @param $table1
     * @param $table2
     * @param $table1_column
     * @param $table2_column
     * @return $this
     */
    //vvv Constructing a JOIN part of the query for specified tables, columns and join Type
    //vvv returning this for method chaining
    public function join($joinType, $table1, $table2, $table1_column, $table2_column): static
    {
        $allowedJoinTypes = ['', 'INNER', 'LEFT', 'RIGHT', 'FULL', 'CROSS'];

        if (!in_array(strtoupper($joinType), $allowedJoinTypes)) {
            throw new InvalidArgumentException('Invalid JOIN type');
        }

        $this->joinStatement .= "$joinType JOIN $table1 ON $table1.$table1_column = $table2.$table2_column ";

        return $this;
    }

    /**
     * @return bool|array
     */
    //vvv Constructing whole query and executing it.
    public function getQuery(): bool|array
    {
        $statement = self::prepare($this->selectStatement . $this->joinStatement . $this->whereStatement);
        $statement->execute($this->columnValueMapping);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->selectStatement = '';
        $this->whereStatement = '';
        $this->columnValueMapping = [];

        return $result;
    }
}