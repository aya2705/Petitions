<?php
class DB {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            $servername = "localhost";
            $username = "root";
            $password = "nassimnassim12";
            $database = "petitions";

            // Create connection
            self::$conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
?>
