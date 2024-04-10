<?php
// start session to get access to session variables
session_start();
// connect to database
include "./connect.php";
// get post data
$uname = $_POST["username"];
$pword = $_POST["password"];
// prepare statement
$stmt = $conn->prepare("SELECT * FROM Users WHERE username = ? and password = ?");
$stmt->bind_param("ss", $uname, $pword);
// execute
$stmt->execute();
// get result
$res = $stmt->get_result();
if ($res->num_rows == 1) {
    $row = $res->fetch_assoc();
    // set session variables
    $_SESSION["username"] = $row["username"];
    $_SESSION["firstname"] = $row["firstname"];
    $_SESSION["lastname"] = $row["lastname"];
    $_SESSION["role"] = $row["role"];
} else {
    http_response_code(400);
    echo "Login failed";
}
?>
