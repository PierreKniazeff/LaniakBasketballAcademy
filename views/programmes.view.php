<link href="public/css/.css" rel="stylesheet">

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Descriptif des Programmes</h1><br><br>
            </div>
            <?php
              $pdo = require 'controllers/pdo.php';
            // 1> Sélection de l'IdType dans les Programmes : 
            // requête initiale effectuée pour récupérer toutes les entrées de la table programmes :
            $sql = 'SELECT * FROM programmes';
            $stmt = $pdo->query($sql);
            $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
        </div>
        <div class="row justify-content-center">
            <?php
            $length = count($formations);
            foreach ($formations as $key => $f) :
                $borderClass = ($key === $length - 1) ? 'border-warning' : '';
            ?>
                <div class="col-md-4 my-3 text-center mx-auto">
                    <div class="card <?= $borderClass ?>" style="width: 18rem;">
                        <img src="public/assets/uploads/<?= $f['image'] ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <p class="card-text">
                                <?= nl2br($f['description']) ?>
                            </p>
                            <?php
                            // 2> Préparation de la requête pour obtenir le libellé de Type :
                            // Pour chaque formation récupérée, une requête est préparée pour obtenir 
                            // le libellé correspondant de la table type basé sur l'idType : 
                            $query = "SELECT libelle FROM type WHERE idType = :idType";
                            $stmt = $pdo->prepare($query);
                            // La requête $query fonctionne comme une jointure implicite entre 
                            // les deux tables sur le champ idType, bien que la jointure ne soit 
                            // pas explicitement formulée comme telle dans une seule requête SQL. 
                            // La logique de jointure est gérée par le code PHP en extrayant 
                            // d'abord toutes les informations de programmes puis en faisant 
                            // correspondre chaque idType de programmes avec idType de type via 
                            // des requêtes SQL séparées pour chaque ligne.
                            $stmt->bindParam(':idType', $f['idType'], PDO::PARAM_INT);
                            // Liaison de Paramètre (bindParam) : La méthode bindParam est utilisée pour lier 
                            // le paramètre :idType de la requête préparée au idType de la formation en cours 
                            // de traitement. Cela assure que la valeur de idType provenant de programmes 
                            // est utilisée pour filtrer les résultats de la table type.
                            $stmt->execute();
                            $type = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <!-- Affichage du Résultat : Le libellé récupéré ($type['libelle']) est 
                            ensuite affiché dans un élément HTML : -->
                            <span class="badge text-bg-dark">
                                <?= $type['libelle'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <p class="color"><br>*Uniquement lors des séances de coaching personalisés</p>
    </div>
    <style>
        .color {
            color: orangered;
        }

        .container {
            padding: 0 15px;
            /* Remediation : force la marge zéro */
        }
    </style>


    <footer>
        <div class="container-fluid">
            <?php include_once "views/common/footer.php"; ?>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>