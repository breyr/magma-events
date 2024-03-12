<?php
    $conn = new mysqli("<database service name>", "<user>", "<password>", "<database>");
    if(mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>