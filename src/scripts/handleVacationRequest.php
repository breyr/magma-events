<?php
// enable session for session variables
session_start();
// connect to db
include "./connect.php";

// get the values from the form submission or from variables
$username = $_SESSION["username"];
$fname = $_SESSION["firstname"];
$lname = $_SESSION["lastname"];
$reason = $_POST["reason"];
$vacationDays = $_POST["vacationDays"];

// prepare the SQL statement
$stmt = $conn->prepare(
    "INSERT INTO VacationRequests (username, first_name, last_name, vacation_days, reason) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssss", $username, $fname, $lname, $vacationDays, $reason);

// execute the SQL statement
if ($stmt->execute()) {
    echo "Vacation request submitted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// close the statement and the connection
$stmt->close();
$conn->close();
?>
