<?php
// Inclusion du modèle de base avec chemin robuste (toujours relatif au modèle)
require_once __DIR__ . '/Model.class.php';

class MainManager extends Model
{
    /**
     * Récupère toutes les lignes de la table mydata
     * @return array
     */
    public function getDatas()
    {
        $req = $this->getBdd()->prepare("SELECT * FROM mydata");
        $req->execute();
        $datas = $req->fetchAll(PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $datas;
    }
}
