<?php 
    // Schedule view for non-admin users
    session_start();
    // connect to database
    include('./scripts/connect.php'); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magma Events - My Schedule</title>
    <?php include("./includes/head.php"); ?>
</head>

<body class="d-flex flex-column vh-100">
    <!-- import nav bar -->
    <?php include("./includes/nav.php"); ?>

    <main class="flex-grow-1 p-3">
        <div class="accordion" id="scheduleAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Your Scheduled Events
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                    data-bs-parent="#scheduleAccordion">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Event Name</th>
                                    <th scope="col">Venue Address</th>
                                    <th scope="col">Venue Phone</th>
                                    <th scope="col">Organizer Email</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Duration (Hours)</th>
                                    <th scope="col">Directions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $uname = $_SESSION['username'];
                                    $sql = "SELECT *
                                    FROM Events
                                    JOIN Event_Participants
                                    ON Events.event_name = Event_Participants.event_name
                                    WHERE Event_Participants.username = '$uname';";
                                    $result = $conn->query($sql);
                                    while($row=$result->fetch_assoc()){
                                ?>
                                <tr>
                                    <td><?php echo $row['event_name']; ?></td>
                                    <td><?php echo $row['venue_address']; ?></td>
                                    <td><?php echo $row['venue_phone']; ?></td>
                                    <td><?php echo $row['organizer_email']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['duration_hours']; ?></td>
                                    <td>
                                        <a href="https://www.google.com/maps/dir//<?php echo urlencode($row['venue_address']); ?>"
                                            target="_blank" class="text-decoration-none">
                                            <i class="fa-solid fa-map-pin"></i> Get Directions
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Request Vacation Time
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#scheduleAccordion">
                    <div class="accordion-body">
                        <div class="col-lg-6 mx-auto">
                            <div class="py-3 px-5">
                                <div class="mb-3">
                                    <label for="start-date" class="form-label">Start Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start-date" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end-date" class="form-label">End Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end-date" name="end_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3"
                                        required></textarea>
                                </div>
                                <button class="btn btn-primary" id="submit-vacation-request">Submit
                                    Request</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container">
    </div>

    <?php include('./includes/scriptImports.php'); ?>
</body>

</html>