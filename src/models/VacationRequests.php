<?php
class VacationRequest
{
    private $id;
    private $username;
    private $firstName;
    private $lastName;
    private $vacationDays;
    private $reason;
    private $status;
    private $dateSubmitted;

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
        $this->id = $row["id"];
        $this->username = $row["username"];
        $this->firstName = $row["first_name"];
        $this->lastName = $row["last_name"];
        $this->vacationDays = $row["vacation_days"];
        $this->reason = $row["reason"];
        $this->status = $row["request_status"];
        $date = new DateTime($row["request_date"]);
        $this->dateSubmitted = $date->format("m/d/Y");
    }

    public function reqToHTMLRow()
    {
        $html = "<tr>";
        $html .= "<td class='d-none'>" . $this->id . "</td>";
        $html .= "<td>" . $this->username . "</td>";
        $html .= "<td>" . $this->firstName . "</td>";
        $html .= "<td>" . $this->lastName . "</td>";
        $html .= "<td>" . $this->vacationDays . "</td>";
        $html .= "<td>" . $this->reason . "</td>";
        $html .= "<td>" . $this->status . "</td>";
        $html .= "<td>" . $this->dateSubmitted . "</td>";
        $html .=
            "<td><button class='btn btn-success approve'>Approve</button> <button class='btn btn-danger deny'>Deny</button></td>";
        $html .= "</tr>";
        return $html;
    }
}

function getVacationRequests($dbConn)
{
    $sql = 'SELECT * FROM VacationRequests WHERE request_status = "Pending" ORDER BY request_date DESC';
    $result = $dbConn->query($sql);
    $requests = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $request = new VacationRequest();
            $request->popFromRow($row);
            array_push($requests, $request);
        }
    }
    return $requests;
}

?>
