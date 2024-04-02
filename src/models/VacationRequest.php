<?php
    class VacationRequest {
        private $username;
        private $startDate;
        private $endDate;
        private $reason;
    
        public function __construct($username, $startDate, $endDate, $reason) {
            $this->username = $username;
            $this->startDate = $startDate;
            $this->endDate = $endDate;
            $this->reason = $reason;
        }
    
        public function getUsername() {
            return $this->username;
        }
    
        public function getStartDate() {
            return $this->startDate;
        }
    
        public function getEndDate() {
            return $this->endDate;
        }
    
        public function getReason() {
            return $this->reason;
        }
    }
?>