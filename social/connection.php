<?php

$dsn  = "mysql:host=localhost;dbname=social";
$name = "root";
$pass = "";


try {
    $conn = new PDO($dsn, $name, $pass);
}
catch(PDOException $e){
    echo "Failed Connection " . $e->getMessage();
}