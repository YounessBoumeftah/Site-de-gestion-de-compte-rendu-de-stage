<?php
//toujour en premier pour maintenir la connecion a la base de donner
session_start();
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION["login"])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: index.php");
    exit();
}

// Inclusion du fichier de configuration '_conf.php' pour les paramètres de connexion à la base de données
require ('_conf.php');

?>
<!DOCTYPE html>
<html>
<head>
<title>Page d accueil</title>
    <link href="style.css" media="all" rel="stylesheet" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: url('image/background_image_journal.jpg');">
</head>
    <ul class="nav">
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="compte_rendu.php">Comptes rendus</a></li>
        <?php
        //verifie si le type est un prof ou eleve 
        //0 pour prof
        if ($_SESSION["type"] == 0) {
            // Affiche le menu spécifique au professeur
            echo '<li><a href="liste_utilisateurs.php">Liste des utilisateurs</a></li>';
            //si cest pas 0 cest un eleve
        } else {
            // Affiche le menu spécifique à lélève
            echo "<li><a href='création_compte_rendu.php'>Nouveau compte-rendu</a></li>";
            echo "<li><a href='boîte_reception.php'>Boîte de récéption</a></li>";
        }
        ?>
    </ul>
    <div class="accueil">Bienvenue sur le site du Lycée Paul Lapie</div>
    <div class="accueil">BTS SIO</div>

    <?php

    // Formulaire de déconnexion
    echo "<br></br>";
    echo "<form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>";
    ?>
    
        
</body>
</html>
