<?php 
    // admin view
    session_start();
    // Check if the session variable is set
    if (!isset($_SESSION['username'])) {
        // Redirect to index page
        header('Location: index.php');
        exit();
    }
    // connect to database
    include('./scripts/connect.php'); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magma Events - Admin</title>
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
                        Events
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                    data-bs-parent="#scheduleAccordion">
                    <div class="accordion-body">

                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Pending Vacation Requests
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#scheduleAccordion">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Vacation Days</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Request Status</th>
                                    <th scope="col">Request Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // only get pending requests
                                    $sql = 'SELECT * FROM VacationRequests WHERE request_status = "Pending";';
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()){
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['first_name']; ?></td>
                                    <td><?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['vacation_days']; ?></td>
                                    <td><?php echo $row['reason']; ?></td>
                                    <td><?php echo $row['request_status']; ?></td>
                                    <td><?php echo $row['request_date']; ?></td>
                                    <td>
                                        <button class="btn btn-outline-success btn-sm approve">Approve</button>
                                        <button class="btn btn-outline-danger btn-sm deny">Deny</button>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else {?>
                                <tr class="text-center fst-italic">
                                    <td colspan='10'>No requests</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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