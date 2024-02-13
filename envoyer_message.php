<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION["login"])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: index.php");
    exit();
}

// Inclusion du fichier de configuration '_conf.php' pour les paramètres de connexion à la base de données
require('_conf.php');

$success_message = ""; // Variable pour stocker le message de succès

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $destinataire = isset($_POST['destinataire']) ? $_POST['destinataire'] : null;
    $message = isset($_POST['message']) ? $_POST['message'] : null;

    // Vérifier si le destinataire et le message sont définis
    if ($destinataire && $message) {
        // Définir la variable $date_envoi avec la date actuelle
        $date_message = date('Y-m-d H:i:s');

        // Utilisateur qui envoie le message (à remplacer par la logique appropriée)
        $num_auteur = 1; // Exemple : remplacez 1 par la valeur appropriée

        // Les utilisateurs existent, procéder à l'insertion
        $stmt = $pdo->prepare("INSERT INTO message (num_destinataire, num_auteur, date, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$destinataire, $num_auteur, $date_message, $message]);

        // Vérifier le succès de l'opération
        if ($stmt->rowCount() > 0) {
            $success_message = "Le message a bien été envoyé !";
        } else {
            $success_message = "Erreur lors de l'envoi du message (rowCount = 0).";
        }
    }
}






// Fermeture de la connexion à la base de données
$pdo = null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Envoyer un message</title>
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
            echo '<li><a href="messagerie.php">Messagerie</a></li>';
            echo "<li><a href='boîte_reception.php'>Boîte de réception</a></li>";
        }
        ?>
    </ul>
   
    
    <table class="envoyer-message">
    <caption><h2>Envoyer un message</h2></caption>
    <thead></thead>
    <tbody>
        <form method="post" action="envoyer_message.php">
            <input type="hidden" name="destinataire" value="<?php echo $destinataire; ?>">
            
            <tr>
                <td>Message:</td>
                <td><textarea name="message" id="message" required></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                    // Afficher le message de succès
                    if (!empty($success_message)) {
                        echo "<p>$success_message</p>";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit" name="envoyer_message">Envoyer Message</button></td>
            </tr>
        </form>
    </tbody>
</table>



    <?php
    // Formulaire de retour et déconnexion
    echo "<div class='buttons-container'>";
    echo "<form method='post' action='liste_utilisateurs.php'><button type='submit' name='lst_utilisateurs'>Retour à la liste des utilisateurs</button></form>";
    echo "<form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>";
    echo "</div>";
    ?>
</body>
</html>
