<?php
/**
 * Classe Inscription : représente une inscription d'un User à un Programme
 */
class Inscription
{
    private $id;
    private $programId;
    private $userId;
    private $enrollmentDate;

    /**
     * Constructeur
     * @param int $programId
     * @param int $userId
     * @param string $enrollmentDate (format : YYYY-MM-DD)
     * @param int|null $id (optionnel : fourni si hydratation depuis la BDD)
     */
    public function __construct($programId, $userId, $enrollmentDate, $id = null)
    {
        $this->programId = $programId;
        $this->userId = $userId;
        $this->enrollmentDate = $enrollmentDate;
        $this->id = $id;
    }

    // Accesseurs et mutateurs standards

    public function getId()
    {
        return $this->id;
    }

    public function getProgramId()
    {
        return $this->programId;
    }
    public function setProgramId($programId)
    {
        $this->programId = $programId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getEnrollmentDate()
    {
        return $this->enrollmentDate;
    }
    public function setEnrollmentDate($enrollmentDate)
    {
        $this->enrollmentDate = $enrollmentDate;
    }
}
?>
