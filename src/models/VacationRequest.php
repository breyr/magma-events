<?php
    class VacationRequest {
        private $username;
        private $fname;
        private $lname;
        private $startDate;
        private $endDate;
        private $reason;
    
        public function __construct($username, $fname, $lname, $startDate, $endDate, $reason) {
            $this->username = $username;
            $this->fname = $fname;
            $this->lname = $lname;
            $this->startDate = $startDate;
            $this->endDate = $endDate;
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