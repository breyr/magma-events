<?php
    // enable session for session variables
    session_start();
    // connect to db
    include('./connect.php');

    // get the values from post
    $rowId = $_POST['id']; 
    $action = $_POST['action'];

    // prepare the SQL statement
    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE VacationRequests SET request_status = 'Approved' WHERE id = ?");
    } else if ($action == 'deny') {
        $stmt = $conn->prepare("UPDATE VacationRequests SET request_status = 'Denied' WHERE id = ?");
    }

    $stmt->bind_param("i", $rowId);

    // execute the SQL statement
    if ($stmt->execute()) {
        echo "Vacation request updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }


    // close the statement and the connection
    $stmt->close();
    $conn->close();
?>