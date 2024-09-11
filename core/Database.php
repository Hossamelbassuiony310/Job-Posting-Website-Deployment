<?php
class Database {
    private $dbName;
    private $host;
    private $user;
    private $password;

    private $connection = null;

    public function __construct()
    {
        // Initialize properties within the constructor
        $this->dbName   = getenv('DB_NAME');
        $this->host     = getenv('DB_HOST');
        $this->user     = getenv('DB_USER');
        $this->password = getenv('DB_PASSWORD');

        if (empty($this->host)) {
            die('DB_HOST is not set or is empty');
        }

        $dsn = "mysql:host=". $this->host .";dbname=". $this->dbName .";";

        try {
            $this->connection = new \PDO($dsn, $this->user, $this->password);
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }
    public function Query($query, $params = [])
    {
        $stmt = $this->connection->prepare($query);
        if(count($params) > 0) {
            for ($i=1; $i <= count($params); $i++) {
                $stmt->bindValue($i, $params[$i - 1]);
            }
        }
        $stmt->execute();

        return $stmt;
    }
    public function GetLastId()
    {
        return $this->connection->lastInsertId();
    }
    public function CloseConnection()
    {
        $this->connection = null;
    }
}

