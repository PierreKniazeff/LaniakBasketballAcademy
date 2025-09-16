<link href="public/css/.css" rel="stylesheet">

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Descriptifs des programmes</h1><br><br>
            </div>
            <?php
              $pdo = require 'controllers/pdo.php';
            // 1> Select IdType from the Programs: 
            // Initial query executed to retrieve all entries from the programmes table:
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
                            // 2> Prepare the query to get the label of Type:
                            // For each training retrieved, a query is prepared to obtain 
                            // the corresponding label from the type table based on the idType: 
                            $query = "SELECT libelle FROM type WHERE idType = :idType";
                            $stmt = $pdo->prepare($query);
                            // The $query operates as an implicit join between 
                            // the two tables on the idType field, even though the join is 
                            // not explicitly formulated as such in a single SQL query. 
                            // The join logic is handled by the PHP code by first extracting 
                            // all the information from programmes and then matching 
                            // each idType from programmes with idType from type via 
                            // separate SQL queries for each row.
                            $stmt->bindParam(':idType', $f['idType'], PDO::PARAM_INT);
                            // Binding Parameter (bindParam): The bindParam method is used to bind 
                            // the :idType parameter of the prepared query to the idType of the formation being processed. 
                            // This ensures that the value of idType from programmes 
                            // is used to filter results from the type table.
                            $stmt->execute();
                            $type = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <!-- Displaying the Result: The retrieved label ($type['libelle']) is 
                            then displayed in an HTML element: -->
                            <span class="badge text-bg-dark">
                                <?= $type['libelle'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <p class="color"><br>*Only during personalized coaching sessions</p>
    </div>
    <style>
        .color {
            color: orangered;
        }

        .container {
            padding: 0 15px;
            /* Remediation : force the margin to zero */
        }
    </style>

    <footer>
        <div class="container-fluid">
            <?php include_once "views/common/footer.php"; ?>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
