<?php

class Database
{
    private static $dbName = 'tlsb';
    private static $dbHost = 'localhost';
    private static $dbUsername = 'rwk_db';
    private static $dbUserPassword = '#n#eghW7kJ';

    private static $conn = null;

    public function __construct()
    {
        exit('Init function is not allowed');
    }

    public static function connect()
    {
        // One connection through whole application
        if (null == self::$conn) {
            try {
                self::$conn = new PDO("mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName .";CharSet=utf8;", self::$dbUsername, self::$dbUserPassword);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$conn;
    }

    public static function disconnect()
    {
        self::$conn = null;
    }
}

?>
