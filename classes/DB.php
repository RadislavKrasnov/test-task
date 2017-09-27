<?php

class DB
{
    private static $instance = null;
    private $pdo;
    private $query;
    private $error = false;
    private $results;
    private $count = 0;

  //Singlton pattern is using for database
    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=' . Config::get('mysql/host') .
                ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'),
                Config::get('mysql/password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new DB();
        }

        return self::$instance;
    }

//in this method is realizing query in db
    public function query($sqlCode, $parameters = [])
    {
        $this->error = false;
        if ($this->query = $this->pdo->prepare($sqlCode)) {
            $i = 1;
            if (count($parameters)) {
                foreach ($parameters as $parameter) {
                    $this->query->bindValue($i, $parameter);
                    $i++;
                }
            }
            if ($this->query->execute()) {
               $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
               $this->count = $this->query->rowCount();
            } else {
                $this->error = true;
            }
        }
        return $this;
    }

//Realise different queries in DB, action is determined in get, delete queries
    private function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = [
                '=',
                '>',
                '<',
                '<=',
                '>='
            ];
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM  {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

//create useful method for work with queries in DB
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

//delete from DB method
    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

//get error
    public function error()
    {
        return $this->error;
    }

//get count of result rows
    public function count()
    {
        return $this->count;
    }

    //get results
    public function result()
    {
        return $this->results;
    }

//return one record from DB
    public function first()
    {
        return $this->result()[0];
    }

//insert user into DB
    public function insert($table, $fields = [])
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = '';
            $i = 1;

            foreach ($fields as $field) {
                $values .= '?';
                if ($i < count($fields)) {
                    $values .= ', ';
                }
                $i++;
            }

            echo $sql = "INSERT INTO users (`". implode('`, `', $keys) ."`)
                    VALUES ({$values})";

            if(!$this->query($sql, $fields)->error()) {
                return true;
            }
        }

        return false;
    }

    //update method
    public function update($table, $id, $fields) {
        $set = '';
        $i = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";

            if ($i < count($fields)) {
                $set .= ', ';
            }
            $i++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE users.id = {$id}";

        if ($this->query($sql, $fields)) {
            return true;
        }

        return false;
    }
}