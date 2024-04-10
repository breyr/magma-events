<?php
// admin view
session_start();
// Check if the session variable is set
if (!isset($_SESSION["username"])) {
    // Redirect to index page
    header("Location: index.php");
    exit();
}
// connect to database
include "./scripts/connect.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magma Events - Admin</title>
    <?php include "./includes/head.php"; ?>
</head>

<body class="d-flex flex-column vh-100">
    <!-- import nav bar -->
    <?php include "./includes/nav.php"; ?>

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
                                    $eventNames = [];
                                    while ($row = $result->fetch_assoc()) {
                                        $eName = $row["event_name"];
                                        array_push($eventNames, $eName);
                                        $eventNameParts = explode(" ", $eName);
                                        $first = $eventNameParts[0];
                                        echo '<th scope="col" class="text-center" data-event-name="' .
                                            $eName .
                                            '" id="' .
                                            $first .
                                            '">' .
                                            $first .
                                            "<br>" .
                                            $row["date"] .
                                            "</th>";
                                    }
                                    ?>
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
                                $eventCounts = array_fill_keys($eventNames, [
                                    "prep" => 0,
                                    "server" => 0,
                                    "total" => 0,
                                    "cost" => 0,
                                ]);
                                $previousRole = null;
                                while ($row = $result->fetch_assoc()) {

                                    if ($previousRole == "Preparer" && $row["role"] == "Server") {
                                        echo '<tr><td class="bg-primary-subtle fw-bold" colspan="3">Total Prep</td>';
                                        foreach ($eventCounts as $eventName => $counts) {
                                            $eventNameSplit = explode(" ", $eventName);
                                            $event = trim($eventNameSplit[0]);
                                            echo '<td id="' .
                                                $event .
                                                '-prepcount" class="text-center bg-primary-subtle">' .
                                                $counts["prep"] .
                                                "</td>";
                                        }
                                        echo "</tr>";
                                    }
                                    $previousRole = $row["role"];
                                    ?>
                                <tr>
                                    <td data-username="<?php echo $row["username"]; ?>">
                                        <?php echo $row["lastname"] . ", " . $row["firstname"]; ?>
                                    <td><?php echo $row["phone"]; ?></td>
                                    <td style="border-right: 1px solid #DFD8CA;"><?php echo $row["role"][0]; ?></td>
                                    <td style="display: none;"><?php echo $row["rate"]; ?></td>
                                    <?php
                                    $evtCount = 0;
                                    foreach ($eventNames as $eventName) {
                                        // Fetch the event date
                                        $sql = "SELECT date FROM Events WHERE event_name = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("s", $eventName);
                                        $stmt->execute();
                                        $result_event = $stmt->get_result();
                                        $event = $result_event->fetch_assoc();
                                        $event_date = DateTime::createFromFormat("Y-m-d", $event["date"]);
                                        $sql =
                                            "SELECT COUNT(*) as count FROM Event_Participants WHERE username = ? AND event_name = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("ss", $row["username"], $eventName);
                                        $stmt->execute();
                                        $result_inner = $stmt->get_result();
                                        $data = $result_inner->fetch_assoc();
                                        // check if there are even vacation_days, some may be null
                                        $vacation_days = $row["vacation_days"]
                                            ? explode(", ", $row["vacation_days"])
                                            : [];
                                        $on_vacation = false;
                                        foreach ($vacation_days as $vacation_day) {
                                            if (strpos($vacation_day, "-") !== false) {
                                                list($start, $end) = explode("-", $vacation_day);
                                                $start_date = DateTime::createFromFormat("m/d", $start);
                                                $end_date = DateTime::createFromFormat("m/d", $end);
                                                if ($start_date && $end_date) {
                                                    $start_date->setDate(
                                                        $event_date->format("Y"),
                                                        $start_date->format("m"),
                                                        $start_date->format("d")
                                                    );
                                                    $end_date->setDate(
                                                        $event_date->format("Y"),
                                                        $end_date->format("m"),
                                                        $end_date->format("d")
                                                    );
                                                    if ($event_date >= $start_date && $event_date <= $end_date) {
                                                        $on_vacation = true;
                                                        break;
                                                    }
                                                }
                                            } else {
                                                $vacation_date = DateTime::createFromFormat("m/d", $vacation_day);
                                                if ($vacation_date) {
                                                    $vacation_date->setDate(
                                                        $event_date->format("Y"),
                                                        $vacation_date->format("m"),
                                                        $vacation_date->format("d")
                                                    );
                                                    if ($event_date->format("m/d") == $vacation_date->format("m/d")) {
                                                        $on_vacation = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        }

                                        if ($data["count"] > 0 && !$on_vacation) {
                                            echo "<td class='bg-success interactive-cell $previousRole'></td>";
                                            $evtCount++;
                                            if ($row["role"] == "Preparer") {
                                                $eventCounts[$eventName]["prep"]++;
                                            } elseif ($row["role"] == "Server") {
                                                $eventCounts[$eventName]["server"]++;
                                            }
                                            $eventCounts[$eventName]["total"]++;
                                            $eventCounts[$eventName]["cost"] += $row["rate"];
                                        } else {
                                            if ($on_vacation) {
                                                echo "<td class='bg-dark blackout-cell $previousRole'></td>";
                                            } else {
                                                echo "<td class='bg-secondary-subtle interactive-cell $previousRole'></td>";
                                            }
                                        }
                                    }
                                    echo "<td class='text-center' id='" .
                                        $row["username"] .
                                        "-evtcount'>$evtCount</td>";
                                    ?>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td class="bg-primary-subtle fw-bold" colspan="3">Total Serve</td>
                                    <?php foreach ($eventCounts as $eventName => $counts) {
                                        $eventNameSplit = explode(" ", $eventName);
                                        $event = trim($eventNameSplit[0]);
                                        echo '<td id="' .
                                            $event .
                                            '-servecount" class="text-center bg-primary-subtle border-right border-left">' .
                                            $counts["server"] .
                                            "</td>";
                                    } ?>
                                </tr>
                                <tr>
                                    <td class="bg-primary fw-bold text-light" colspan="3">Total Staff</td>
                                    <?php foreach ($eventCounts as $eventName => $counts) {
                                        $eventNameSplit = explode(" ", $eventName);
                                        $event = trim($eventNameSplit[0]);
                                        echo '<td id="' .
                                            $event .
                                            '-staffcount" class="text-center bg-primary border-right border-left text-light fw-bold">' .
                                            $counts["total"] .
                                            "</td>";
                                    } ?>
                                </tr>
                                <tr>
                                    <td class="bg-primary fw-bold text-light" colspan="3">Total Cost</td>
                                    <?php foreach ($eventCounts as $eventName => $counts) {
                                        $eventNameSplit = explode(" ", $eventName);
                                        $event = trim($eventNameSplit[0]);
                                        echo '<td id="' .
                                            $event .
                                            '-cost" class="text-center bg-primary border-right border-left text-light fw-bold">$' .
                                            number_format($counts["cost"], 2) .
                                            "</td>";
                                    } ?>
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
                                    while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row["id"]; ?></td>
                                    <td><?php echo $row["username"]; ?></td>
                                    <td><?php echo $row["first_name"]; ?></td>
                                    <td><?php echo $row["last_name"]; ?></td>
                                    <td><?php echo $row["vacation_days"]; ?></td>
                                    <td><?php echo $row["reason"]; ?></td>
                                    <td><?php echo $row["request_status"]; ?></td>
                                    <td><?php echo $row["request_date"]; ?></td>
                                    <td>
                                        <button class="btn btn-outline-success btn-sm approve">Approve</button>
                                        <button class="btn btn-outline-danger btn-sm deny">Deny</button>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php
                                } else {
                                     ?>
                                <tr class="text-center fst-italic">
                                    <td colspan='10'>No requests</td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container">
    </div>

    <?php include "./includes/scriptImports.php"; ?>
    <script>
        $(document).ready(function () {
            // Add click event listener to each interactive cell
            $('.interactive-cell').each(function() {
                let cell = $(this);
                cell.click(function() {
                    // Toggle cell color
                    if (cell.hasClass('bg-success')) {
                        cell.removeClass('bg-success').addClass('bg-secondary-subtle');
                    } else {
                        cell.removeClass('bg-secondary-subtle').addClass('bg-success');
                    }

                    // Update counts
                    // pass in true if cell is green, false if grey
                    // also pass in reference to the cell that was clicked;
                    updateCounts(cell.hasClass('bg-success'), cell);

                    // Extract relevant data
                    const username = cell.parent().find('td:first').attr('data-username');
                    // get the name of the event from the header row
                    const eventName = cell.closest('table').find('thead th:nth-child(' + (cell.index() + 1) + ')').attr('data-event-name');

                    // Update database
                    const newRecord = cell.hasClass('bg-success') ? true : false;
                    const role = cell.hasClass('Preparer') ? 'Preparer' : 'Server';
                    updateDatabase(newRecord, username, eventName, role);
                });
            });

            // Function to update counts
            function updateCounts(isGreen, cell) {
                // if true, that means cell turned green, if false cell is grey

                // each time a cell gets clicked I need to update the total prep if the cell is above prep
                // total serve if cell is below prep, total staff and total cost, but also total events for each user
                // which is just the row that we clicked

                // for each row, count the number of bg-success cells for the row in which we clicked and update total events
                let row = $(cell).parent().get(0);
                const eventCells = $(row).find('.interactive-cell');
                let totalEvents = 0;
                eventCells.each(function() {
                    if ($(this).hasClass('bg-success')) {
                        totalEvents++;
                    }
                });
                // update total events for the user, last cell in row
                $(row.cells[row.cells.length - 1]).text(totalEvents);

                // get index of the current cell in the row
                const cellIndex = cell.index();
                // find whether this was a prep or serve cell
                const cellType = $(cell).hasClass('Preparer') ? 'prep' : 'serve';
                // find the event for the cell, the event is the the th in the same column as the cell
                // needs to be minus one because the fourth td is hidden which holds the amount the person is making
                // get the id
                const eventId = $(cell).parent().parent().parent().find('thead').find('tr').eq(0).find('th').eq(cellIndex - 1).attr('id').trim();
                // need to count how many cells are green for each event, update the total count for preparers and servers, but also update the total cost which can be calulated by getting the fourth column in the table for each row that is green
                let counts = {
                    prep: 0,
                    serve: 0,
                    staff: 0,
                    cost: 0
                };
                // get all the cells for the column cell.cellIndex + 1
                const cells = $(cell).parent().parent().find('td:nth-child(' + (cellIndex + 1) + ')');
                cells.each(function() {
                    if ($(this).hasClass('bg-success')) {
                        if ($(this).hasClass('Preparer')) {
                            counts.prep++;
                        } else {
                            counts.serve++;
                        }
                        counts.staff++;
                        // cost needs to be a floating point number to two decimal places
                        counts.cost += parseFloat($(this).parent().find('td').eq(3).text());
                    }
                });
                // update the counts for the event, event-prepcount, event-servecount, event-staffcount, event-cost
                $('#' + eventId + '-prepcount').text(counts.prep);
                $('#' + eventId + '-servecount').text(counts.serve);
                $('#' + eventId + '-staffcount').text(counts.staff);
                $('#' + eventId + '-cost').text('$' + counts.cost.toFixed(2));
            }

            // Function to update database
            function updateDatabase(newRecord, username, eventName, role) {
                // bool is true for green, false for grey
                // green means add a record to the database, grey is deletion
                $.ajax({
                    url: './scripts/updateDatabase.php',
                    type: 'POST',
                    data: {
                        username,
                        eventName,
                        role,
                        newRecord
                    },
                    success: function(response) {
                        console.log(response);
                    }
                })
            }
        });
    </script>
</body>

</html>
