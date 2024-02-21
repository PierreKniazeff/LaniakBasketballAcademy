<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Descriptif des Programmes</h1><br><br>
            </div>
            <?php
            $pdo = require 'config/pdo.php';
            $sql = 'SELECT * FROM programmes';
            $stmt = $pdo->query($sql);
            $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
        </div>
        <div class="row justify-content-center">
            <?php
            $length = count($formations);
            foreach ($formations as $key => $f):
                $borderClass = ($key === $length - 1) ? 'border-warning' : '';
            ?>
                <div class="col-md-4 my-3 text-center mx-auto">
                    <div class="card <?=$borderClass?>" style="width: 18rem;">
                        <img src="public/assets/uploads/<?= $f['image'] ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <p class="card-text">
                                <?= nl2br($f['description']) ?>
                            </p>
                            <?php
                            $query = "SELECT libelle FROM type WHERE idType = :idType";
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':idType', $f['idType'], PDO::PARAM_INT);
                            $stmt->execute();
                            $type = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
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
            .color{
                color: orangered;
            }
            .container {
            padding: 0 15px; /* Remediation : force la marge zéro */
        }
        </style>
    

    <footer>
        <div class="container-fluid">
            <?php include_once("views/common/footer.php"); ?>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>
