<?php
    // enable session for session variables
    session_start();
    // connect to db
    include('./connect.php');
    // include VacationRequest class
    include('../models/VacationRequest.php');

    // get the values from the form submission or from variables
    $username = $_SESSION['username']; // assuming the username is stored in a session variable
    $startDate = $_POST['startDate']; // replace 'start_date' with the name of your form field
    $endDate = $_POST['endDate']; // replace 'end_date' with the name of your form field
    $reason = $_POST['reason']; // replace 'reason' with the name of your form field

    // create a new instance of the VacationRequest class
    $vacationRequest = new VacationRequest($username, $startDate, $endDate, $reason);

    // get the values from the VacationRequest object
    $username = $vacationRequest->getUsername();
    $startDate = $vacationRequest->getStartDate();
    $endDate = $vacationRequest->getEndDate();
    $reason = $vacationRequest->getReason();

    // prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO VacationRequests (username, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $startDate, $endDate, $reason);

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