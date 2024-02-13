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

// Récupérer les messages de la boîte de réception de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM message WHERE num_destinataire = ? ORDER BY date DESC");
$stmt->execute([$_SESSION["num"]]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Boîte de réception</title>
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
            echo '<li><a href="messagerie.php">Messagerie</a></li>';
            echo '<li><a href="liste_utilisateurs.php">Liste des utilisateurs</a></li>';
        } else {
            // Affiche le menu spécifique à l'élève
            echo '<li><a href="création_compte_rendu.php">Nouveau compte-rendu</a></li>';
            echo "<li><a href='boîte_reception.php'>Boîte de récéption</a></li>";
        }
        ?>
    </ul>

    <!-- Afficher les messages de la boîte de réception -->
   <div class="tab_mail-box">
    <h2>Boîte de réception</h2>
    <?php
    if (count($messages) > 0) {
        echo "<ul>";
        foreach ($messages as $message) {
            // Récupérer le nom et le prénom de l'auteur du message
            $auteurInfo = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE num = ?");
            $auteurInfo->execute([$message["num_auteur"]]);
            $auteur = $auteurInfo->fetch();

            echo "<li>";
            echo "De : " . $auteur["nom"] . " " . $auteur["prenom"] . "<br>";
            echo "Contenu : " . $message["message"] . "<br>";
            echo "Date d'envoi : " . $message["date"] . "<br>";
            echo "</li>";
            echo "<hr>";
        }
        echo "</ul>";
    } else {
        echo "Votre boîte de réception est vide.";
    }
    ?>
</div>
    <!-- Fermeture de la connexion à la base de données -->
    <?php $pdo = null; ?>

    <!-- Formulaire de déconnexion -->
    <br></br>
    <form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>
</body>
</html>
