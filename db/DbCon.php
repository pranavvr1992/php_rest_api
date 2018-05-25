<?php

/**
 * Description of DbCon
 *
 * @author pvr-admin
 */
class DbCon {

    public $connection;

    // get the database connection
    public function getConnection() {
        $appProperties = parse_ini_file(parse_ini_file("application.ini")["propFile"]);
        $host = $appProperties["db_host"];
        $dbName = $appProperties["db_name"];
        $username = $appProperties["db_user"];
        $password = $appProperties["db_password"];
        $this->connection = null;
        try {
            $this->connection = new PDO("mysql:host=" . $host . ";dbname=" . $dbName, $username, $password);
            $this->connection->exec("set names utf8");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Database connection error: " . $exception->getMessage();
        }

        return $this->connection;
    }

}
