<?php
    // enable session for session variables
    session_start();
    // connect to db
    include('./connect.php');

    // get the values from post
    $rowId = $_POST['id']; 
    $action = $_POST['action'];
    $vacationDays = $_POST['vacationDays'];
    $uname = $_POST['username'];

    
    // prepare the SQL statement
    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE VacationRequests SET request_status = 'Approved' WHERE id = ?");

        // update vacation_days in table based on users role
        // get role of user who submitted request
        $sql = "SELECT role FROM Users WHERE username = \"$uname\";";
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
        // append s to role to get role table
        $roleTable = $row['role'].'s';

        // Fetch current vacation_days
        $stmtVacationDays = $conn->prepare("SELECT vacation_days FROM $roleTable WHERE username = ?");
        $stmtVacationDays->bind_param("s", $uname);
        $stmtVacationDays->execute();
        $result = $stmtVacationDays->get_result();
        $row = $result->fetch_assoc();
        $currentVacationDays = $row['vacation_days'];

        // Append new vacation days
        $updatedVacationDays = $currentVacationDays . ', ' . $vacationDays;

        // Update vacation_days
        $stmtUpdateVacationDays = $conn->prepare("UPDATE $roleTable SET vacation_days = ? WHERE username = ?");
        $stmtUpdateVacationDays->bind_param("ss", $updatedVacationDays, $uname);
        $stmtUpdateVacationDays->execute();

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