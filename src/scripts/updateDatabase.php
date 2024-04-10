<?php
// start session to get access to session variables
session_start();
// connect to database
include "./connect.php";
// get post data
$uname = $_POST["username"];
$eventName = $_POST["eventName"];
$role = $_POST["role"];
$newRecord = $_POST["newRecord"]; // this is true or false
// echo data
echo "username: " . $uname . "<br>";
echo "event name: " . $eventName . "<br>";
echo "role: " . $role . "<br>";
echo "new record: " . $newRecord . "<br>";
// prepare statement based on newRecord
if ($newRecord == "true") {
    $stmt = $conn->prepare("INSERT INTO Event_Participants (event_name, username, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $eventName, $uname, $role);
} else {
    // delete from event_participants
    $stmt = $conn->prepare("DELETE FROM Event_Participants WHERE event_name = ? AND username = ?");
    $stmt->bind_param("ss", $eventName, $uname);
}
// execute
$stmt->execute();
// see if query was successful
if ($stmt->affected_rows > 0) {
    echo "success";
} else {
    echo "fail";
}
// close connection
$stmt->close();
$conn->close();
?>
