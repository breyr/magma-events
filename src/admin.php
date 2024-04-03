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
                        <table class="table">
                            <thead style="font-size: 0.8rem">
                                <tr>
                                    <!-- event headers -->
                                    <th scope="col">Last, First</th>
                                    <th scope="col">Phone #</th>
                                    <th scope="col" style="border-right: 1px solid #DFD8CA;">Role</th>
                                    <?php
                                        $sql = "SELECT DISTINCT(event_name), date FROM Events ORDER BY date, event_name";
                                        $result = $conn->query($sql);
                                        $eventNames = array();
                                        while($row=$result-> fetch_assoc()) {
                                            $eventNames[] = $row['event_name'];
                                    ?>
                                    <th scope="col" class="text-center">
                                        <?php 
                                            $eventNameParts = explode('@', $row['event_name']);
                                            $firstPart = $eventNameParts[0];
                                            echo $firstPart.'<br>'.$row['date'];
                                        ?>
                                    </th>
                                    <?php } ?>
                                    <th style="border-left: 1px solid #DFD8CA;">Total Events</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $sql = "SELECT 
                                                Users.username,
                                                Users.firstname, 
                                                Users.lastname, 
                                                Users.role,
                                                UserRoles.phone,
                                                UserRoles.rate,
                                                UserRoles.vacation_days
                                            FROM 
                                                (
                                                    SELECT username, phone, rate, vacation_days FROM Servers
                                                    UNION ALL
                                                    SELECT username, phone, rate, vacation_days FROM Preparers
                                                ) AS UserRoles
                                            INNER JOIN 
                                                Users ON UserRoles.username = Users.username
                                            ORDER BY 
                                                Users.role DESC, 
                                                Users.lastname ASC;";
                                    $result = $conn->query($sql);
                                    $eventCounts = array_fill_keys($eventNames, ['prep' => 0, 'server' => 0, 'total' => 0, 'cost' => 0]);
                                    $previousRole = null;
                                    while($row = $result->fetch_assoc()) {
                                        if($previousRole == 'Preparer' && $row['role'] == 'Server') {
                                            echo '<tr><td class="bg-primary-subtle fw-bold" colspan="3">Total Prep</td>';
                                            foreach($eventCounts as $eventName => $counts) {
                                                echo '<td id="'.$eventName.'-prepcount" class="text-center bg-primary-subtle">'.$counts['prep'].'</td>';
                                            }
                                            echo '</tr>';
                                        }
                                        $previousRole = $row['role'];
                                ?>
                                <tr>
                                    <td><?php echo $row['lastname'].', '.$row['firstname']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td style="border-right: 1px solid #DFD8CA;"><?php echo $row['role'][0]; ?></td>
                                    <td style="display: none;"><?php echo $row['rate']; ?></td>
                                    <?php 
                                        $evtCount = 0;
                                        foreach($eventNames as $eventName) {
                                            // Fetch the event date
                                            $sql = "SELECT date FROM Events WHERE event_name = ?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("s", $eventName);
                                            $stmt->execute();
                                            $result_event = $stmt->get_result();
                                            $event = $result_event->fetch_assoc();
                                            $event_date = DateTime::createFromFormat('Y-m-d', $event['date']);
                                        
                                            $sql = "SELECT COUNT(*) as count FROM Event_Participants WHERE username = ? AND event_name = ?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("ss", $row['username'], $eventName);
                                            $stmt->execute();
                                            $result_inner = $stmt->get_result();
                                            $data = $result_inner->fetch_assoc();
                                            // check if there are even vacation_days, some may be null
                                            $vacation_days = $row['vacation_days'] ? explode(',', $row['vacation_days']) : [];
                                            $on_vacation = false;
                                            foreach($vacation_days as $vacation_day) {
                                                if(strpos($vacation_day, '-') !== false) {
                                                    list($start, $end) = explode('-', $vacation_day);
                                                    $start_date = DateTime::createFromFormat('m/d', $start);
                                                    $end_date = DateTime::createFromFormat('m/d', $end);
                                                    if($start_date && $end_date) {
                                                        $start_date->setDate($event_date->format('Y'), $start_date->format('m'), $start_date->format('d'));
                                                        $end_date->setDate($event_date->format('Y'), $end_date->format('m'), $end_date->format('d'));
                                                        if($event_date >= $start_date && $event_date <= $end_date) {
                                                            $on_vacation = true;
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    $vacation_date = DateTime::createFromFormat('m/d', $vacation_day);
                                                    if($vacation_date) {
                                                        $vacation_date->setDate($event_date->format('Y'), $vacation_date->format('m'), $vacation_date->format('d'));
                                                        if($event_date == $vacation_date) {
                                                            $on_vacation = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                        
                                            if($data['count'] > 0 && !$on_vacation) {
                                                echo '<td class="bg-success interactive-cell"></td>';
                                                $evtCount++;
                                                if($row['role'] == 'Preparer') {
                                                    $eventCounts[$eventName]['prep']++;
                                                } else if($row['role'] == 'Server') {
                                                    $eventCounts[$eventName]['server']++;
                                                }
                                                $eventCounts[$eventName]['total']++;
                                                $eventCounts[$eventName]['cost'] += $row['rate'];
                                            } else {
                                                if($on_vacation) {
                                                    echo '<td class="bg-dark interactive-cell"></td>';
                                                } else {
                                                    echo '<td class="bg-secondary-subtle interactive-cell"></td>';
                                                }
                                            }
                                        }
                                        echo "<td class='text-center' id='".$row['username']."-evtcount'>$evtCount</td>";
                                    ?>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="bg-primary-subtle fw-bold" colspan="3">Total Serve</td>
                                    <?php 
                                        foreach($eventCounts as $eventName => $counts) {
                                            echo '<td id="'.$eventName.'-servecount" class="text-center bg-primary-subtle border-right border-left">'.$counts['server'].'</td>';
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="bg-primary fw-bold text-light" colspan="3">Total Staff</td>
                                    <?php 
                                        foreach($eventCounts as $eventName => $counts) {
                                            echo '<td id="'.$eventName.'-staffcount" class="text-center bg-primary border-right border-left text-light fw-bold">'.$counts['total'].'</td>';
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="bg-primary fw-bold text-light" colspan="3">Total Cost</td>
                                    <?php 
                                        foreach($eventCounts as $eventName => $counts) {
                                            echo '<td id="'.$eventName.'-cost" class="text-center bg-primary border-right border-left text-light fw-bold">$'.number_format($counts['cost'], 2).'</td>';
                                        }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
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