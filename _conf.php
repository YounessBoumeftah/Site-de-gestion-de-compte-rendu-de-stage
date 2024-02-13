<?php
// _conf.php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ap1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERREUR : Impossible de se connecter. " . $e->getMessage());
}

?>