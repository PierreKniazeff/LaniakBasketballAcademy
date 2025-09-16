<?php
// Protéger la vue contre l'accès direct
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
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
                    <br>President and founder of the Laniak Basketball Academy, Modibo Niakate is a coach known for his
                    rigor and passion for hard work.<br><br>
                    The international player and captain of the Mali team, with which he finished as the top scorer of the
                    Africa Cup of Nations in Angola, excellently combines European and American styles due to his
                    time at Cleveland State University, where he also became the team's top scorer.<br><br>
                    His professional basketball career has been crowned with success, particularly with his victory in the
                    AS Week and his title as champion of France Pro A with Roanne in 2007.<br><br>
                    Nicknamed "LANIAK", he is a reference in the French basketball circuit.<br><br>
                </p>
            </div>
            <div class="col-lg-6">
                <!-- Image to the right of the title -->
                <img src="./public/assets/images/PhotoAccueil.PNG" alt="Photo of Modibo Niakate" class="img-fluid" style="max-width: 100%;">
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
