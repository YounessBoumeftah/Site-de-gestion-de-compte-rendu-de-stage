<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Réinitialisation du mot de passe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: url('image/background_image_journal.jpg');">
    <div class="tab_oublier">
        <h1>Mot de passe oublié</h1>

        <form class="oublier" method="POST">
    Adresse mail :
    <input type="text" name="mail" id="mail" required>
    <input type="submit" value="OK" name="btn_reinitialiser"> <!-- Utilisation d'un nom différent ici -->
</form>

<form method='post' action='index.php'>
    <button type='submit' name='deco'>Retour</button>
</form>

    </div>

    <?php
    session_start();

    include '_conf.php';

     // Initialisation de la variable pour le nouveau mot de passe
        $nouveauMotDePasse = "";

        // Vérification de la soumission du formulaire
        if (isset($_POST["btn_reinitialiser"])) {
            // Récupération de la valeur du champ email
            $mail = $_POST['mail'];

            if (empty($mail)) {
                echo "<p>Veuillez entrer une adresse e-mail.</p>";
                exit();
            }

            // Valider l'email
            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                // Recherche de l'adresse mail dans la base de données
                $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE email = ?");
                $stmt->execute([$mail]);

                // Vérifier si l'adresse e-mail a été trouvée
                if ($stmt->rowCount() > 0) {
                    // L'adresse e-mail a été trouvée dans la base de données, générer un nouveau mot de passe aléatoire et l'envoyer par e-mail
                    $nouveauMotDePasse = bin2hex(random_bytes(8));

                    // Mettre à jour le mot de passe de l'utilisateur dans la base de données
                    $mdp_hash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE UTILISATEUR SET motdepasse=? WHERE email=?");
                    $stmt->execute([$mdp_hash, $mail]);

                    // Envoyer l'e-mail
                    $subject = "Réinitialisation de mot de passe";
                    $message = "Votre nouveau mot de passe est : $nouveauMotDePasse";
                    $to = $mail;

                    if (mail($to, $subject, $message)) {
                        // Afficher le message de confirmation et le nouveau mot de passe
                        echo "<p>Votre demande a bien été prise en compte. Vous allez recevoir un email pour réinitialiser votre mot de passe.</p>";
                        echo "<p>Votre nouveau mot de passe est : $nouveauMotDePasse</p>";
                    } else {
                        echo "<p>Votre nouveau mot de passe est : $nouveauMotDePasse</p>";
                    }
                } else {
                    // L'adresse e-mail n'a pas été trouvée dans la base de données
                    echo "<p>Cette adresse e-mail n'existe pas dans notre base de données.</p>";
                }
            } else {
                // L'adresse e-mail n'est pas valide
                echo "<p>L'adresse e-mail n'est pas valide.</p>";
            }
        }

        // Fermeture de la connexion à la base de données
        $pdo = null;
        ?>
        
        <form method='post' action='index.php'>
            <button type='submit' name='deco'>Retour</button>
        </form>
    </div>
</body>
</html>