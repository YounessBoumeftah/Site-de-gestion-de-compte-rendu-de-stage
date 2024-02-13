<?php
// Démarrez la session
//toujour en premier pour maintenir la connecion a la base de donner
session_start();
  if (isset($_POST['deco']))
            {session_destroy();
                echo "Déconnectée ";
                  
            }
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION["login"])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: index.php");
    exit();
}


?>
<a href="index.php" class="contact-button">Page de connexion</a>