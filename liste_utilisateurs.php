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
    <title>Liste des utilisateurs</title>
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
            echo "<li><a href='boîte_reception.php'>Boîte de récéption</a></li>";
        }
        ?>
    </ul>

    <?php
    // Récupérer le paramètre 'destinataire' de l'URL
    $destinataire = isset($_GET['destinataire']) ? $_GET['destinataire'] : null;

    // Vérifier si le destinataire est défini
if ($destinataire) {
    // Requête SQL pour récupérer les informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(YEAR, UTILISATEUR.date_naissance, CURDATE()) AS age, utilisateur.nom as nom_eleve, utilisateur.prenom as prenom_eleve, STAGE.nom AS nom_STAGE, STAGE.adresse AS adresse_STAGE, STAGE.tel AS tel_STAGE, STAGE.libelleSTAGE AS libelle_STAGE, TUTEUR.nom AS nom_TUTEUR, TUTEUR.prenom AS prenom_TUTEUR, TUTEUR.tel AS tel_TUTEUR
        FROM UTILISATEUR, STAGE, TUTEUR, CR
        WHERE STAGE.num = UTILISATEUR.num_stage
        AND UTILISATEUR.num = CR.num_utilisateur
        AND TUTEUR.num = STAGE.num_tuteur
        AND UTILISATEUR.num = ?");
    $stmt->execute([$destinataire]);
    $row = $stmt->fetch();

    // Vérifier si l'utilisateur existe
   // Vérifier si l'utilisateur existe
if ($row) {
    echo "<table class='profil'>";
    echo "<thead>";
    echo "<tr><th colspan='2'><h2 class='info_users'>Profil de l'utilisateur</h2></th></tr>";
    echo "</thead>";
    echo "<tbody>";
    echo "<tr> <th> Information de l'élève </th></tr>";
    echo "<tr> <td>Élève : " . $row["nom_eleve"] . " " . $row["prenom_eleve"] ."</td><tr>";
    echo "<tr> <td> Agé de : ". $row["age"]." ans </td></tr>"; 
    echo "<th> <br> Information sur le stage de l'élève </br></th>";
    echo "<tr> <td> STAGE : " . $row["nom_STAGE"] . "</td></</tr>";
    echo "<tr> <td> Adresse du STAGE : " . $row["adresse_STAGE"] . "</td></</tr>";
    echo "<tr> <td> Téléphone du STAGE : " . $row["tel_STAGE"] . "</td></</tr>";
    echo "<tr> <td> Libellé du STAGE : " . $row["libelle_STAGE"] . "</td></</tr>";
    echo "<tr> <th><br>  Information sur le tuteur de l'élève </br></th></</tr>";
    echo "<tr> <td> TUTEUR : " . $row["nom_TUTEUR"] . " " . $row["prenom_TUTEUR"] . "</td></</tr>";
    echo "<tr> <td> Téléphone du TUTEUR : " . $row["tel_TUTEUR"] . "</td></</tr>";
    echo "</tbody>";
    echo "</table>";



        // Boutons pour voir les comptes rendus, le profil et envoyer un message
        echo "<br>";
         // Boutons pour voir les comptes rendus, le profil et envoyer un message
echo "<div class='buttons-container'>";
echo "<form class='voir_cr' method='post' action='voir_comptes_rendus.php'><input type='hidden' name='destinataire' value='" . $destinataire . "'><button type='submit' name='voir_cr'>Voir Comptes Rendus</button></form>";
echo "<form class='envoyer_message' method='post' action='envoyer_message.php'><input type='hidden' name='destinataire' value='" . $destinataire . "'><button type='submit' name='envoyer_message'>Envoyer Message</button></form>";
echo "</div>";



    echo "</tbody>";
    echo "</table>";
    } else {
        echo "Utilisateur non trouvé";
    }
    } else {
        // Requête SQL pour récupérer la liste des utilisateurs
        $stmt = $pdo->prepare("SELECT num, nom, prenom FROM utilisateur WHERE type = 1");
        $stmt->execute();
        $resultat = $stmt->fetchAll();
        ?>

       <table class="lst_users">
    <caption><h2 class="cr">Liste des élèves</h2></caption>
    <thead>
        <tr>
            <td><th>Nom</th></td>
           <td> <th>Prénom</th></td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($resultat as $row) {
            echo "<tr>";
            echo "<td><a href='liste_utilisateurs.php?destinataire=" . $row["num"] . "'>" . $row["nom"] . "</a></td>";
            echo "<td>" . $row["prenom"] . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>




        <?php
    }
    
    // Fermeture de la connexion à la base de données
    $pdo = null;

    // Formulaire de déconnexion
    echo "<br></br>";
    echo "<form method='post' action='index.php'><button type='submit' name='deco'>Déconnexion</button></form>";
    ?>
</body>
</html>
