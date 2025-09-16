<?php
// Empêche l'accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title data-translate="title">Laniak</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link href="public/css/.css" rel="stylesheet"> <!-- Adapter le nom du fichier CSS si besoin -->
</head>

<body>
    <div class="container-sm">
        <div class="row">
            <div class="col-lg-6">
                <br><br>
                <img src="./public/assets/images/ModNiaTitle.PNG" alt="Modibo Niakate" class="img-fluid" style="max-width: 100%; max-height: 30vh;">
                <p class="roboto-font text-justify" style="max-width: 100%;" data-translate="paragraph">
                    <br>Président et fondateur de Laniak Basketball Academy, Modibo Niakate est un coach reconnu pour sa
                    rigueur et son goût de l’effort.<br><br>
                    L'international et capitaine de l’équipe du Mali, avec laquelle il finira meilleur marqueur de la Coupe
                    d’Afrique des Nation en Angola, combine avec excellence le schéma européen et américain de part son
                    passage à l'université de Cleveland States, où il finira aussi meilleur marqueur.<br><br>
                    Sa carrière de basketteur professionnel aura été couronnée de succès notamment avec sa victoire de la
                    semaine des AS ainsi que son sacre de champion de France Pro A avec Roanne en 2007.<br><br>
                    Surnommé « LANIAK » il est une référence sur le circuit du basketball français.<br><br>
                </p>
            </div>
            <div class="col-lg-6">
                <img src="./public/assets/images/PhotoAccueil.PNG" alt="Photo de Modibo Niakate" class="img-fluid" style="max-width: 100%;">
            </div>
        </div>

        <footer>
            <div class="container-fluid">
                <?php include_once __DIR__ . '/common/footer.php'; ?>
            </div>
        </footer>
    </div>
</body>
</html>
