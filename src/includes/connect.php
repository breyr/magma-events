<?php
    $DB_SERVICE = $_ENV['DB_SERVICE'];
    $DB_USER = $_ENV['MYSQL_USER'];
    $DB_PASS = $_ENV['MYSQL_PASSWORD'];
    $DB_NAME = $_ENV['MYSQL_DATABASE'];
    $conn = new mysqli($DB_SERVICE, $DB_USER, $DB_PASS, $DB_NAME);
    if(mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>