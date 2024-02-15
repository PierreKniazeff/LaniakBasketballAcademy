<?php
abstract class Model
{
    private static $pdo;
    private static function setBdd()
    {
        self::$pdo = new pdo("mysql:host=localhost;dbname=mvc", "root", "");
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    protected function getBdd(){
        if(self::$pdo === null){
            self::setBdd();
        }
        return self::$pdo;
    }

}
?>