<?php

/**
 * Description of DbCon
 *
 * @author pvr-admin
 */
class DbCon {

    private $host = "localhost";
    private $dbName = "php_rest_api";
    private $username = "root";
    private $password = "";
    public $connection;

    // get the database connection
    public function getConnection() {
        $this->connection = null;
        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName, $this->username, $this->password);
            $this->connection->exec("set names utf8");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Database connection error: " . $exception->getMessage();
        }

        return $this->connection;
    }

}
