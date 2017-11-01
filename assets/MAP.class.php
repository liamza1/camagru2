<?php

namespace ass;


class MAP
{
    private $PDOInstance = null;
    private static $instance = null;
    private $sqlDB;

    private function __construct()
    {
        try {
            require_once('../config/database.php');
            $this->sqlDB = $DB_BASE;
            $this->PDOInstance = new \PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->PDOInstance->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e)
        {
            echo 'connection failed'. $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if(is_null(self::$instance)){
            self::$instance = new MAP();
        }
        return self::$instance;

    }
    public function findAll($table, $where, $order = null, $limit = null)
    {
        $request = "SELECT * FROM " . $table . " WHERE 1 = 1";
        foreach ($where as $k => $v)
            $request .= " AND " . $k . " = :" . $k;
        if (!empty($order))
            $request .= " ORDER BY ".$order[0]." ".$order[1];
        if (!empty($limit))
            $request .= " LIMIT ".$limit[0].",".$limit[1];
        $statement = $this->PDOInstance->prepare($request);
        foreach ($where as $k => $v)
            $statement->bindValue(':' . $k, $v);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findOne ($table, $where)
    {
        $request = "SELECT * FROM " . $table . "WHERE 1 = 1";
        foreach ($where as $k => $v)
            $request .= " AND " . $k . " = :" . $k;
        $statement = $this->PDOInstance->prepare($request);
        foreach ($where as $k => $v)
            $statement->bindValue(':' . $k, $v);
        $statement->setFetchMode(\PDO::FETCH_ASSOC, 'ass\mech\\' . ucfirst($table));
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_CLASS);
    }

    public function count($table, $where)
    {
        $request = "SELECT count(*) FROM " .$table . "WHERE 1 = 1";
        foreach ($where as $k => $v)
            $request .= " AND " . $k . " = :" .$k;
        $statement = $this->PDOInstance->prepare($request);
        foreach ($where as $k =>$v)
            $statement->bindValue(':' .$k,$v);
        $statement->execute();
        $tmp = $statement->fetch();
        return $tmp[0];
    }

    private function update($table, $fields, $value)
    {
        $request_fields = '';
        foreach ($value as $k => $v)
        {
            if (in_array($k, $fields))
            {
                $request_fields .= '`'.$k.'`=:'.$k.', ';
            }
        }
        $request = 'UPDATE '.$table.' SET '.rtrim($request_fields, ', ').' WHERE id = :id';
        $statement = $this->PDOInstance->prepare($request);
        foreach ($value as $k => $v)
        {
            if (in_array($k, $fields))
            {
                $statement->bindValue(':' . $k, $v);
            }
        }
        $statement->bindValue(':id', $value['id']);
        $statement->execute();
        return (true);
    }

    private function insert($table, $fields, $value)
    {
    $request_fields = '';
    $request_value = '';
    unset($value['id']);
    foreach ($value as $k => $v) {
        if (in_array($k, $fields)) {
            $request_fields .= '`' . $k . '`, ';
            $request_value .= ':' . $k . ', ';
        }
        $request = 'INSERT INTO ' . $table . ' (' . rtrim($request_fields, ', ') . ') VALUES (' . rtrim($request_value, ', ') . ')';
        $statement = $this->PDOInstance->prepare($request);
        foreach ($value as $k => $v) {
            if (in_array($k, $fields)) {
                $statement->bindValue(':' . $k, $v);

            }
        }
        try {
            $statement->execute();
        } catch (\Exception $e) {
            echo "<pre>";
            echo $request;
            print_r($value);
            echo $e->getMessage();
            exit();
        }
        return ($this->PDOInstance->lastInsertId());
    }
    }

    private function getFields($table)
    {
        $statement = $this->PDOInstance->prepare("SELECT column_name FROM information_schema.columns WHERE table_schema = :base AND table_name = :table");
        $statement->bindValue(':table', $table);
        $statement->bindValue(':base', $this->sqlDB);
        $statement->execute();
        return ($statement->fetchAll(\PDO::FETCH_COLUMN));
    }

    public function store($table, $value)
{
    $fields = $this->getFields($table);
    if ($value['id'] == NULL)
        return ($this->insert($table, $fields, $value));
    else
        return ($this->update($table, $fields, $value));
}

    public function delete($table, $id)
{
    $request = 'DELETE FROM '.$table.' WHERE id = :id';
    $statement = $this->PDOInstance->prepare($request);
    $statement->bindValue(':id', $id);
    $statement->execute();
}

}