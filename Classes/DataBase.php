<?php


class DataBase
{
    private PDO $pdo;
    private static DataBase|null $connect = null;
    public array $params;

    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=' . Config::get('mysql.host') .';dbname=' .
                Config::get('mysql.dbname') .';charset=' . Config::get('mysql.charset'),
                Config::get('mysql.user'), '');
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * @return DataBase
     */
    public static function getConnect(): DataBase
    {
        if (!isset(self::$connect)) {
            self::$connect = new DataBase();
        }
        return self::$connect;
    }

    /**
     * @param $sql
     * @return bool|array
     */
    public function query($sql): object|array
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($this->params);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $table
     * @param array $params
     * @return object|bool|array
     */
    public function delete(string $table, array $params): object|bool|array
    {
        $var = array_key_first($params);
        $this->params = $params;
        $sql = "DELETE FROM `{$table}` WHERE `{$var}`=:{$var}";
        return $this->query($sql);
    }

    /**
     * @param string $table
     * @param array $params
     * @param $id
     * @return object|bool|array
     */
    public function update(string $table, array $params, $id): object|bool|array
    {
        $this->params = $params;
        $key = array_keys($params);
        $str = '';
        foreach ($key as $value) {
            $str .= "`{$value}`=:{$value}, ";
        }
        $str = rtrim($str, ', ');
        $sql = "UPDATE {$table} SET {$str} WHERE id={$id}";
        return $this->query($sql);
    }

    /**
     * @param string $table
     * @param array $params
     * @return object|bool|array
     */
    public function insert(string $table, array $params): object|bool|array
    {
        $this->params = $params;
        $key = array_keys($params);
        $str = '';
        $strKey = '';
        foreach ($key as $value) {
            $str .= "`{$value}`, ";
            $strKey .= ":{$value}, ";
        }
        $str = rtrim($str, ', ');
        $strKey = rtrim($strKey, ', ');
        $sql = "INSERT INTO {$table} ({$str}) VALUES ({$strKey})";

        return $this->query($sql);
    }

    /**
     * @param string $table
     * @param array $params
     * @return array|bool|object
     */
    public function get(string $table, array $params): object|bool|array
    {
        $this->params = $params;
        $key = array_keys($params);
        $sql = "SELECT * FROM `{$table}` WHERE `{$key[0]}`=:{$key[0]}";
        return $this->query($sql);
    }

    /**
     * @param string $table
     * @return array|bool|object
     */
    public function selectAll(string $table)
    {
        $sql = "SELECT * FROM `{$table}`";
        return $this->queryFunc($sql);
    }

    /**
     * @param $sql
     * @return bool|array
     */
    public function queryFunc($sql): object|array
    {
        $query = $this->pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
