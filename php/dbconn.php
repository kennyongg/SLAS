<?php

require __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::create(__DIR__ . "/../");
$dotenv->load();

$servername = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$db_name = getenv('DATABASE');

// PDO
try {
    $link = new PDO(
        "mysql:host=$servername;dbname=$db_name", $username, $password);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $PDOException) {
    echo json_encode(array("PDO-error" => $PDOException->getMessage()));
}

?>
