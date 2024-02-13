<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Modification de compte-rendu</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: url('image/background_image_journal.jpg');">
</head>
<?php
// Démarrer la session
session_start();
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
// Inclusion du fichier de configuration '_conf.php' pour les paramètres de connexion à la base de données
require ('_conf.php');



// Récupérer le numéro du compte rendu à modifier depuis les paramètres GET
if (isset($_GET['num'])) {
    $numToUpdate = $_GET['num'];

    // Récupérer les détails du compte rendu depuis la base de données
    // Effectuer la requête SQL pour récupérer les données du compte rendu avec le numéro spécifié
    $stmt = $pdo->prepare("SELECT * FROM cr WHERE num = ?");
    $stmt->execute([$numToUpdate]);
    $compte_rendu = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compte_rendu) {
        // Gérer le cas où le compte rendu n'est pas trouvé
        echo "Compte rendu non trouvé.";
        exit;
    }
} else {
    // Gérer le cas où le numéro du compte rendu n'est pas spécifié
    echo "Numéro du compte rendu non spécifié.";
    exit;
}

// Traitement du formulaire de modification
if (isset($_SESSION["type"]) && $_SESSION["type"] == 0 && isset($_POST['modifier_submit'])) {
    $nouvelleDate = $_POST['date'];
    $nouvelleDescription = $_POST['description'];

    // Requête SQL UPDATE
    $stmt = $pdo->prepare("UPDATE cr SET date = ?, description = ? WHERE num = ?");
    $stmt->execute([$nouvelleDate, $nouvelleDescription, $numToUpdate]);

    // Rediriger vers la page principale après la modification
    header("Location: compte_rendu.php");
    exit;
}
?>


<div class="tab_modifier-cr">
<h2>Modifier le compte rendu</h2>
        <form class="modifier-cr" method="post" action="">
         Nouvelle Date :
        <input type="date" name="date" value="<?php echo $compte_rendu['date']; ?>" required>

         Nouvelle Description :
        <textarea name="description" required><?php echo $compte_rendu['description']; ?></textarea>

         <!-- Ajoutez un champ caché pour le numéro du compte rendu -->
        <input type="hidden" name="num" value="<?php echo $compte_rendu['num']; ?>"><br>

        <button type="submit" name="modifier_submit">Enregistrer</button>

        <!-- Bouton de retour -->
        <a href="compte_rendu.php">Retour à la page des comptes rendus</a>
        </form>
</div>


<br></br>
 <form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>
</body>
</html>
