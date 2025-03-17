<?php
class DB {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "petition_bd";

            self::$conn = new mysqli($servername, $username, $password, $database);

            
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
?>
