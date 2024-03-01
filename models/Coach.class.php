<?php
class Coach {
    private $id;
    private $name;
    private $bio;
    private $image;
    
    public function __construct($name, $bio, $image) {
        $this->name = $name;
        $this->bio = $bio;
        $this->image = $image;
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
    
    public function getBio() {
        return $this->bio;
    }
    
    public function setBio($bio) {
        $this->bio = $bio;
    }
    
    public function getImage() {
        return $this->image;
    }
    
    public function setImage($image) {
        $this->image = $image;
    }
    
    public function viewMemberProfiles() {
        // Voir les profils des membres en tant qu'entraîneur
    }
}
?>