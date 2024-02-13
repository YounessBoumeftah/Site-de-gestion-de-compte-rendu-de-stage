<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Compte rendu</title>
    <link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: url('image/background_image_journal.jpg');">
</head>

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

// Vérification si le formulaire de création de compte rendu a été soumis
if (isset($_POST['update'])) {

  // Récupération des données du formulaire
    $date = $_POST['date'];
    $contenu = addslashes($_POST['description']);
    $id = $_SESSION["num"];
    
    $stmt=$pdo->prepare("SELECT type FROM utilisateur WHERE num = ?");
    $stmt->execute([$id]);
}
?>
<body>
    <!-- Code HTML pour les élèves -->
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
            echo "<li><a href='boîte_reception.php'>Boîte de récéption</a></li>";
        }
        ?>
    </ul>
    <?php
 

   $resultat = [];
$stmt = null;

if ($_SESSION["type"] == 0) {
    // Si c'est un professeur, affiche les comptes rendus de tous les élèves
    $stmt = $pdo->prepare("SELECT utilisateur.nom, utilisateur.prenom, cr.* FROM utilisateur, cr WHERE utilisateur.num = cr.num_utilisateur");
} elseif (isset($_SESSION["num"])) {
    // Sinon, affiche les comptes rendus de l'élève actuellement connecté
    $id = $_SESSION["num"];
    $stmt = $pdo->prepare("SELECT utilisateur.nom, utilisateur.prenom, cr.* FROM utilisateur, cr WHERE utilisateur.num = ? AND utilisateur.num = cr.num_utilisateur");
    $stmt->execute([$id]);
} else {
    echo "La session de l'élève n'a pas de 'num' défini.";
}

// Vérifiez si la requête SQL a été initialisée et exécutée
if ($stmt) {
    $stmt->execute();
    $resultat = $stmt->fetchAll();
} else {
    echo "Erreur dans la requête SQL.";
}
?>
<table class="compte-rendu">
    <caption><h2>Comptes rendus</h2></caption>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date</th>
            <th>Description</th>
            <?php
            // Affiche l'en-tête "Actions" seulement si l'utilisateur est un professeur
            if (isset($_SESSION["type"]) && $_SESSION["type"] == 0) {
                echo "<th>Actions</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        // Affichage des comptes rendus avec actions
        foreach ($resultat as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
            echo "<td>{$row['date']}</td>";
            echo "<td>{$row['description']}</td>";

            // Affiche les formulaires pour modifier et supprimer si le professeur est connecté
            if (isset($_SESSION["type"]) && $_SESSION["type"] == 0) {
                echo "<td>";
                echo "<a href='modifier_compte_rendu.php?num={$row['num']}'>Modifier</a>";

                echo "<form method='post'>";
                echo "<input type='hidden' name='num' value='{$row['num']}'>";
                echo "<button type='submit' name='supprimer'>Supprimer</button>";
                echo "</form>";
                echo "</td>";
            }

            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<?php
    // Traitement du formulaire de suppression
    if ($_SESSION["type"] == 0 && isset($_POST['supprimer'])) {
    $idToDelete = $_POST['num'];
    
    // Requête SQL DELETE
    $stmt = $pdo->prepare("DELETE FROM cr WHERE num = ?");
    $stmt->execute([$idToDelete]);
}

    // Formulaire de déconnexion
    echo "<br></br>";
    echo "<form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>";
    ?>
</body>
</html>





