<?php
    // enable session for session variables
    session_start();
    // connect to db
    include('./connect.php');
    // include VacationRequest class
    include('../models/VacationRequest.php');

    // get the values from the form submission or from variables
    $username = $_SESSION['username']; 
    $fname = $_SESSION['firstname'];
    $lname = $_SESSION['lastname'];
    $startDate = $_POST['startDate']; 
    $endDate = $_POST['endDate']; 
    $reason = $_POST['reason']; 

    // create a new instance of the VacationRequest class
    $vacationRequest = new VacationRequest($username, $fname, $lname, $startDate, $endDate, $reason);

    // get the values from the VacationRequest object
    $username = $vacationRequest->username;
    $fname = $vacationRequest->fname;
    $lname = $vacationRequest->lname;
    $startDate = $vacationRequest->startDate;
    $endDate = $vacationRequest->endDate;
    $reason = $vacationRequest->reason;

    // prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO VacationRequests (username, first_name, last_name, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $fname, $lname, $startDate, $endDate, $reason);

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