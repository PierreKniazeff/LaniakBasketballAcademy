<?php
class Program {
    private $id;
    private $name;
    private $description;
    private $startDate;
    private $endDate;
    
    public function __construct($name, $description, $startDate, $endDate) {
        $this->name = $name;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function getStartDate() {
        return $this->startDate;
    }
    
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }
    
    public function getEndDate() {
        return $this->endDate;
    }
    
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }
}
?>