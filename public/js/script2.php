

<script>
// CONCERNE LES LIENS DE LA PAGE A PROPOS
  
// Fonction pour afficher la page HTML
function showPage(pageUrl) {
    // Charger la page spécifiée
    fetch(pageUrl)
        .then(response => response.text())
        .then(html => {
            // Modifier le contenu de l'élément
            document.getElementById('fullscreenPage').innerHTML = html;
            // Afficher l'élément
            document.getElementById('fullscreenPage').style.display = 'block';
        })
        .catch(error => {
            console.error('Erreur de chargement de la page :', error);
        });
}

// Sélectionner tous les éléments h3 dans la classe "grid"
const headers = document.querySelectorAll('.grid h3');

// Fonction pour réinitialiser la classe "clicked-effect"
const resetClickedEffect = (element) => {
    setTimeout(() => {
        element.classList.remove('clicked-effect');
    }, 300);
};

// Fonction pour afficher la page correspondante en plein écran
const showPageBasedOnHeader = (headerText) => {
    switch (headerText) {
        case "Our Values":
            showPage('views/overplays/valeursEn.php');
            break;
        case "Our Coaching Approach":
            showPage('views/overplays/ApprocheCoachingEn.php');
            break;
            case "Our Team":
            showPage('views/overplays/Equipe.php');
            break;
            case "Coached Players":
            showPage('views/overplays/Joueurs.php');
            break;
            case "Partners":
            showPage('views/overplays/Partenaires.php');
            break;

        // Ajoutez d'autres cas pour d'autres titres de h3 si nécessaire
        default:
            console.log("Aucune action définie pour ce titre de h3.");
    }
};

// Parcourir tous les éléments h3
headers.forEach((header, index) => {
    // Ajouter un écouteur d'événements "click" pour afficher la page en plein écran correspondante
    header.addEventListener('click', () => {
        showPageBasedOnHeader(header.textContent);
        // Ajouter la classe "clicked-effect" pour l'effet d'animation bounce
        header.classList.add('clicked-effect');
        resetClickedEffect(header);
    });
});

// Ajouter un événement de clic sur la page en plein écran pour la masquer
document.getElementById('fullscreenPage').addEventListener('click', () => {
    // Masquer la page en plein écran
    document.getElementById('fullscreenPage').style.display = 'none';
});

    </script>
   



