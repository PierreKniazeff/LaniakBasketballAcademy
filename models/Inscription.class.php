<?php
class Inscription {
    private $id;
    private $programId;
    private $userId;
    private $enrollmentDate;
    
    public function __construct($programId, $userId, $enrollmentDate) {
        $this->programId = $programId;
        $this->userId = $userId;
        $this->enrollmentDate = $enrollmentDate;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getProgramId() {
        return $this->programId;
    }
    
    public function setProgramId($programId) {
        $this->programId = $programId;
    }
    
    public function getUserId() {
        return $this->userId;
    }
    
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    
    public function getEnrollmentDate() {
        return $this->enrollmentDate;
    }
    
    public function setEnrollmentDate($enrollmentDate) {
        $this->enrollmentDate = $enrollmentDate;
    }
}
?>