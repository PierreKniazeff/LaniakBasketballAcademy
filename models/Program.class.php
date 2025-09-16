<?php
/**
 * Modèle Program : représente un programme ou stage sportif
 */
class Program {
    private $id;
    private $name;
    private $description;
    private $startDate;
    private $endDate;

    /**
     * Constructeur
     * @param string $name
     * @param string $description
     * @param string $startDate (format : YYYY-MM-DD)
     * @param string $endDate (format : YYYY-MM-DD)
     * @param int|null $id (optionnel)
     */
    public function __construct($name, $description, $startDate, $endDate, $id = null) {
        $this->name = $name;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->id = $id;
    }

    // Accesseurs et mutateurs standards
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
