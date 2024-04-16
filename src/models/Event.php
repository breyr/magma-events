<?php
class Event
{
    private $eventName;
    private $venueAddress;
    private $venuePhone;
    private $organizerEmail;
    private $date;
    private $durationHours;

    public function __construct()
    {
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    public function popFromRow($row)
    {
        $this->eventName = $row["event_name"];
        $this->venueAddress = $row["venue_address"];
        $this->venuePhone = $row["venue_phone"];
        $this->organizerEmail = $row["organizer_email"];
        // convert date to better format
        $date = new DateTime($row["date"]);
        $this->date = $date->format("m/d/Y");
        $this->durationHours = $row["duration_hours"];
    }

    public function getDirections()
    {
        return "https://www.google.com/maps/dir//" . urlencode($this->venueAddress);
    }

    public function eventToHTMLRow()
    {
        $html = "<tr>";
        $html .= "<td>" . $this->eventName . "</td>";
        $html .= "<td>" . $this->venueAddress . "</td>";
        $html .= "<td>" . $this->venuePhone . "</td>";
        $html .= "<td>" . $this->organizerEmail . "</td>";
        $html .= "<td>" . $this->date . "</td>";
        $html .= "<td>" . $this->durationHours . "</td>";
        $html .=
            "<td><a href='" .
            $this->getDirections() .
            "' target='_blank'><i class='bi bi-geo''></i>Get Directions</a></td>";
        $html .= "</tr>";
        return $html;
    }
}

function getEventsForUser($dbConn, $uname)
{
    $sql =
        "SELECT * FROM Events JOIN Event_Participants ON Events.event_name = Event_Participants.event_name WHERE Event_Participants.username = ? ORDER BY date ASC";
    $stmt = $dbConn->prepare($sql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $event = new Event();
            $event->popFromRow($row);
            array_push($events, $event);
        }
    }
    return $events;
}

?>
