
<?php
    function dbConnect() {
        $host = "127.0.0.1";
        $username = "langleyismatt";
        $password = "";


        $connection = new PDO("mysql:host=$host;dbname=my_products", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
}
?>