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

    // Vérifiez si l'utilisateur est un élève (type 1)
    if ($_SESSION["type"] == 1) {
        $eleve_id = $_SESSION["num"]; // Utilisez $_SESSION["num"] au lieu de $_SESSION["type"]

        // Requête SQL pour récupérer le STAGE et le TUTEUR de l'élève
        $stmt=$pdo->prepare ("SELECT TIMESTAMPDIFF(YEAR, UTILISATEUR.date_naissance, CURDATE()) AS age, utilisateur.nom as nom_eleve, utilisateur.prenom as prenom_eleve, STAGE.nom AS nom_STAGE, STAGE.adresse AS adresse_STAGE, STAGE.tel AS tel_STAGE, STAGE.libelleSTAGE AS libelle_STAGE, TUTEUR.nom AS nom_TUTEUR, TUTEUR.prenom AS prenom_TUTEUR, TUTEUR.tel AS tel_TUTEUR
        FROM UTILISATEUR, STAGE, TUTEUR, CR
        WHERE STAGE.num = UTILISATEUR.num_stage
        AND UTILISATEUR.num = CR.num_utilisateur
        AND TUTEUR.num = STAGE.num_tuteur
        AND UTILISATEUR.num = ?");
        $stmt->execute([$eleve_id]);
        ?>

<!DOCTYPE html>
<html>
<head>
    <title>Page de Profil</title>
    <link href="style.css" media="all" rel="stylesheet" type="text/css"/>
</head>
<body>
    <!-- Code HTML pour les élèves -->
    <ul class="nav">
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="Profil.php">Profil</a></li>
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
        }
        ?>
    </ul>
</body>
</html>

        <h2 class="stage">Stage & Tuteur</h2>
<table class="stage-tuteur">
    <thead>
    </thead>
    <tbody>
        <?php
    if ($stmt) {
            $row = $stmt->fetch();

            // Affiche les informations du STAGE et du TUTEUR
            echo "<tr>";
            echo "<th> Information de l'élève </th>";
            echo "<tr> <td>Élève : " . $row["nom_eleve"] . " " . $row["prenom_eleve"] ."</td></tr>";
            echo" <tr> <td> Agé de : ". $row["age"]."</td></tr>"; 
            echo "<th><br> Information sur le stage de l'élève </br></th>";
            echo "<tr> <td> STAGE : " . $row["nom_STAGE"] . "</td></</tr>";
            echo "<tr> <td> Adresse du STAGE : " . $row["adresse_STAGE"] . "</td></</tr>";
            echo "<tr> <td> Téléphone du STAGE : " . $row["tel_STAGE"] . "</td></</tr>";
            echo "<tr> <td> Libellé du STAGE : " . $row["libelle_STAGE"] . "</td></</tr>";
            echo "<tr> <th><br>  Information sur le tuteur de l'élève </br></th></</tr>";
            echo "<tr> <td> TUTEUR : " . $row["nom_TUTEUR"] . " " . $row["prenom_TUTEUR"] . "</td></</tr>";
            echo "<tr> <td> Téléphone du TUTEUR : " . $row["tel_TUTEUR"] . "</td></</tr>";
            
        } else {
            echo "Erreur lors de la requête : " . mysqli_error($connexion);
        }
        ?>
</table>
    </tbody>
</table>
        <?php
    }


    if ($_SESSION["type"] == 0) {

         // Si l'utilisateur est un professeur, exécutez une requête SQL pour récupérer les éléments de STAGE et de TUTEUR de tous les élèves
         $stmt=$pdo->prepare ("SELECT TIMESTAMPDIFF(YEAR, UTILISATEUR.date_naissance, CURDATE()) AS age, utilisateur.nom as nom_eleve, utilisateur.prenom as prenom_eleve, STAGE.nom AS nom_STAGE, STAGE.adresse AS adresse_STAGE, STAGE.tel AS tel_STAGE, STAGE.libelleSTAGE AS libelle_STAGE, TUTEUR.nom AS nom_TUTEUR, TUTEUR.prenom AS prenom_TUTEUR, TUTEUR.tel AS tel_TUTEUR
        FROM UTILISATEUR, STAGE, TUTEUR, CR
        WHERE STAGE.num = UTILISATEUR.num_stage
        AND UTILISATEUR.num = CR.num_utilisateur
        AND TUTEUR.num = STAGE.num_tuteur");
         $stmt->execute();
 
         if ($stmt) {
            
             ?>
         

<!DOCTYPE html>
    <html>
    <head>
         <title>Page de Profil</title>  
        <link href="style.css" media="all" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <ul class="nav">
            <li><a href="accueil.php">Accueil</a></li>
            <li><a href="Profil.php">Profil</a></li>
            <li><a href="compte_rendu.php">Comptes rendus</a></li>
            <li><a href="messagerie.php">Messagerie</a></li>
            <li><a href="liste_utilisateurs.php">Liste des utilisateurs</a></li>
        </ul>
    </body>
    </html>

<?php
         }

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             // Affiche les informations du STAGE et du TUTEUR
            echo "<tr>";
            echo "<th> Information de l'élève </th>";
            echo "<tr> <td>Élève : " . $row["nom_eleve"] . " " . $row["prenom_eleve"] ."</td></tr>";
            echo" <tr> <td> Agé de : ". $row["age"]."</td></tr>"; 
            echo "<th><br> Information sur le stage de l'élève </br></th>";
            echo "<tr> <td> STAGE : " . $row["nom_STAGE"] . "</td></</tr>";
            echo "<tr> <td> Adresse du STAGE : " . $row["adresse_STAGE"] . "</td></</tr>";
            echo "<tr> <td> Téléphone du STAGE : " . $row["tel_STAGE"] . "</td></</tr>";
            echo "<tr> <td> Libellé du STAGE : " . $row["libelle_STAGE"] . "</td></</tr>";
            echo "<tr> <th><br>  Information sur le tuteur de l'élève </br></th></</tr>";
            echo "<tr> <td> TUTEUR : " . $row["nom_TUTEUR"] . " " . $row["prenom_TUTEUR"] . "</td></</tr>";
            echo "<tr> <td> Téléphone du TUTEUR : " . $row["tel_TUTEUR"] . "</td></</tr>";
        }
    } else {
        echo "Aucun élève trouvé.";
    }
}
    
    // Formulaire de déconnexion
    echo"<br></br>";
    echo "<form method='post' action='deconnexion.php'><button type='submit' name='deco'>Déconnexion</button></form>";
    ?>
