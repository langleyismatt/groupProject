
<?php
    function dbConnect() {
        $host = "localhost";
        $username = "root";
        $password = "";


        $connection = new PDO("mysql:host=$host;dbname=my_products", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
}
?>