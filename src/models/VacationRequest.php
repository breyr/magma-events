<?php
    class VacationRequest {
        private $username;
        private $fname;
        private $lname;
        private $vacationDays;
        private $reason;
    
        public function __construct($username, $fname, $lname, $vacationDays, $reason) {
            $this->username = $username;
            $this->fname = $fname;
            $this->lname = $lname;
            $this->vacationDays = $vacationDays;
            $this->reason = $reason;
        }
    
        public function __get($property) {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        }
        
        public function __set($property, $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
            return $this;
        }
    }
?>