<?php
//toujours en premier pour maintenir la connexion à la base de données
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
    <title>Page de Messagerie</title>
    <link href="style.css" media="all" rel="stylesheet" type="text/css"/>
</head>
<body>
    <!-- Code HTML pour les élèves -->
    <ul class="nav">
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="compte_rendu.php">Comptes rendus</a></li>
       <?php
        if ($_SESSION["type"] == 0) {
            // Affiche le menu spécifique au professeur
            echo '<li><a href="messagerie.php">Messagerie</a></li>';
            echo '<li><a href="liste_utilisateurs.php">Liste des utilisateurs</a></li>';
        } else {
            // Affiche le menu spécifique à l'élève
            echo '<li><a href="création_compte_rendu.php">Nouveau compte-rendu</a></li>';
            echo '<li><a href="messagerie.php">Messagerie</a></li>';
            echo "<li><a href='boîte_reception.php'>Boîte de récéption</a></li>";
        }
        ?>
    </ul>
</body>
</html>
<?php
// Formulaire de déconnexion
    echo"<br></br>";    
    echo "<form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>";
?>