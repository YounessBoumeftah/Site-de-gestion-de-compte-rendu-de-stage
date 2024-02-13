<!DOCTYPE html>
<html>
<head>
    <title>Page de Connexion</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: url('image/background_image_journal.jpg');">
    <div class="tab-connexion">
        <h1>Connexion</h1>
        <form class="connexion" method="post" action="index.php">
            Login :
            <input type="text" id="login" name="login" required/><br>
            Mot de passe :
            <input type="password" id="mdp" name="mdp" required/>
            <input type="submit" value="Se connecter" name="btn_connexion"/><br>
            <a href="oublier.php" class="contact-button">Mot de passe oublié</a>
        </form>
    </div>

    <?php
require('_conf.php');
session_start();

if (isset($_POST["btn_connexion"])) {
    // Formulaire soumis, procédez au traitement des données du formulaire
    $login = trim(htmlspecialchars($_POST["login"]));
    $mdp = trim(htmlspecialchars($_POST["mdp"]));

    // Vérifiez si le login existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE login = ?");
    $stmt->execute([$login]);

    $utilisateur = $stmt->fetch();

    // Si le login n'existe pas, affichez un message d'erreur
    if (!$utilisateur) {
        echo "<p class='error'>Erreur de connexion. Identifiant inconnu.</p>";
    } else {
        // Si le login existe, vérifiez si le mot de passe correspond
        if (!password_verify($mdp, $utilisateur['motdepasse'])) {
            echo "<p class='error'>Erreur de connexion. Mot de passe incorrect.</p>";
        } else {
            // Connexion réussie, définissez la session
            $_SESSION["login"] = $login;
            $_SESSION["type"] = $utilisateur['type']; 
            $_SESSION["num"] = $utilisateur['num'];
            $_SESSION["nom"] = $utilisateur['nom'];
            $_SESSION["prénom"] = $utilisateur['prénom'];

            // Redirigez vers la page d'accueil
            header("Location: accueil.php");
            exit();
        }
    }
}
// Formulaire non soumis, affichez un message
echo "Formulaire non soumis";
    // Fermez la connexion à la base de données après avoir utilisé les résultats
    $pdo = null;
    ?>
</body>
</html>
