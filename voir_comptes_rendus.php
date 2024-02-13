<?php
// Toujours en premier pour maintenir la connexion à la base de données
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION["login"])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: index.php");
    exit();
}

// Inclusion du fichier de configuration '_conf.php' pour les paramètres de connexion à la base de données
require('_conf.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voir Comptes Rendus</title>
    <link href="style.css" media="all" rel="stylesheet" type="text/css"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: url('image/background_image_journal.jpg');">
</head>
    <!-- Menu de navigation -->
    <ul class="nav">
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="compte_rendu.php">Comptes rendus</a></li>
        <?php
        if ($_SESSION["type"] == 0) {
            // Affiche le menu spécifique au professeur
            echo '<li><a href="liste_utilisateurs.php">Liste des utilisateurs</a></li>';
        } else {
            // Affiche le menu spécifique à l'élève
            echo '<li><a href="création_compte_rendu.php">Nouveau compte-rendu</a></li>';
        }
        ?>
    </ul>

    <?php
// Récupérer le paramètre 'destinataire' de l'URL
$destinataire = isset($_POST['destinataire']) ? $_POST['destinataire'] : null;

try {
    // Requête SQL pour récupérer les comptes rendus de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM CR WHERE num_utilisateur = :destinataire");
    $stmt->execute(['destinataire' => $destinataire]);
    
    $resultat = $stmt->fetchAll();

   if ($resultat) {
    echo "<div class='comptes-rendus-container'>";
    echo "<table class='comptes-rendus-table'>";
    echo "<thead>";
    echo "<tr><th colspan='2'><h2 class='cr'>Comptes Rendus de l'utilisateur</h2></th></tr>";
    echo "<tr><th>Date</th><th>Contenu</th></tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($resultat as $row) {
        echo "<tr>";
        echo "<td>" . $row['date']. "</td>";
        echo "<td>" . $row['description']. "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "Aucun compte rendu trouvé pour cet utilisateur.";
}





    
    
} catch (PDOException $e) {
    // Afficher les détails de l'erreur SQL en cas d'échec
    echo "Erreur SQL : " . $e->getMessage();
}




    // Formulaire de retour et déconnexion
echo "<div class='buttons-container'>";
echo "<form method='post' action='liste_utilisateurs.php'><button type='submit' name='lst_utilisateurs'>Retour à la liste des utilisateurs</button></form>";
echo "<form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>";
echo "</div>";

// Fermeture de la connexion à la base de données
$pdo = null;
    ?>
</body>
</html>
