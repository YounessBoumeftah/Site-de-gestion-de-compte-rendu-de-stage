<?php


// Démarrage de la session PHP
session_start();

// Inclusion du fichier de configuration '_conf.php' pour les paramètres de la base de données
require('_conf.php');


// Vérifie si le formulaire a été soumis
if (isset($_POST['update'])) {
    // Récupération des données du formulaire
    $date = $_POST['date'];
    $description = htmlspecialchars($_POST['contenu']);

    // Vérifie si la clé 'num' existe dans $_SESSION
    if (isset($_SESSION['num'])) {
        // Préparation de la requête d'insertion SQL
        $stmt = $pdo->prepare("INSERT INTO CR (date, description, num_utilisateur) VALUES (?,?,?)");
        $stmt->execute([$date, $description, $_SESSION['num']]);
        // Exécution de la requête SQL
    } else {
        echo "La clé 'num' n'est pas définie dans la session.";
    }

    

    // Fermeture de la connexion à la base de données
    $pdo = null;
}
?>


<!-- Définition de la structure de la page HTML -->
<html>
<head>
    <title>Page de Création de Compte Rendu</title>
    <!-- Inclusion de la feuille de style CSS -->
    <link href="style.css" media="all" rel="stylesheet" type="text/css"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: url('image/background_image_journal.jpg');">
</head>

<!-- Création d'un menu de navigation -->
<ul class="nav">
  <li><a href="accueil.php">Accueil</a></li>
  <li><a href="compte_rendu.php">Comptes rendus</a></li>
  <li><a href="création_compte_rendu.php">Nouveau compte-rendu</a></li>
  <li><a href='boîte_reception.php'>Boîte de récéption</a></li>
</ul>

<div class="tab_new-cr">
<!-- Formulaire pour créer un nouveau compte rendu -->
<FORM method="post" action="création_compte_rendu.php">
    <div>
        <!-- Titre de la page -->
        <font size=20 align="center"> Créer un compte rendu  </font>
    </div>
    <br> 
    <div>
        <!-- Champs pour entrer la date -->
        Date <input type="date" name="date" required>
    </div>
    <div>
        <!-- Champ pour entrer le contenu du compte rendu -->
        Description <textarea name="contenu" rows=10 cols=40></textarea>
    </div>
    <br>
    <div>
        <!-- Bouton de soumission du formulaire -->
        <button type="submit" name="update"> Confirmer </button>
    </div>
    
</FORM>
</div>
 <?php

    // Formulaire de déconnexion
    echo "<br></br>";
    echo "<form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>";
    ?>
</html>


