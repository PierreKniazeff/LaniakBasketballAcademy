<?php
class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $confpassword;
    
    public function __construct($username, $email, $password, $confpassword) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->confpassword = $confpassword;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }

    public function setConfPassword($password) {
        $this->password = $password;
    }
    public function getConfPassword() {
        return $this->password;
    }
}
?>