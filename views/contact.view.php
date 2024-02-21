<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
</head>


<?php
class db
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $sb_name = 'contact form du 24/11/23';

    private $link;

    private function connect(){
        $this->link = new mysqli($this->host, $this->user, $this->pass, $this->sb_name);
    }
    public function __construct(){
        $this->connect();
    }
    public function insert($query){
        $result = $this->link->query($query);
        if ($result){
            echo "<h2>Formulaire envoyée avec succès</h2>";
        }else{
            echo "<h2>échec de l'envoi</h2>";
        }
    }
}

?>


<body>
    <h1>Contact Form</h1>
    <form action="" method="post">
    <p>
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" placeholder="Votre nom ici" required>
    </p>
    <p>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Votre Email ici" required>
    </p>
    <p>
        <label for="sujet">Sujet:</label>
        <input type="text" name="sujet" id="sujet" placeholder="Votre sujet ici">
    </p>
    <p>
        <label for="message"></label>
        <textarea name="message" id="message" cols="30" rows="10" placeholder="Votre message ici"></textarea>
    </p>
    <button type="submit" name="pierre">Envoyer</button>
    </form>

    <?php
    // include "config/db.php";
    $db = new db();
    // var_dump($_POST)
    if(isset($_POST['pierre'])){
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $sujet = $_POST['sujet'];
            $message = $_POST['message'];
            $query = "INSERT INTO form (nom,email,sujet,message) VALUES ('$nom','$email','$sujet','$message')";
            $db->insert($query);
    }
    
        ?>

</body>

</html>