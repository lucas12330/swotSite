<?php
$servname = 'localhost';
$dbname = 'swot';
$user = 'root';
$pass = '';

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

try {
    // Connexion à la base de données
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname;charset=utf8", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête préparée sécurisée
    $sql = "INSERT INTO user (NOM, PRENOM, MAIL, ROLE, PASSWORD) VALUES (:nom, :prenom, :email, :role, :password)";
    $stmt = $dbco->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':role' => $role,
        ':password' => password_hash($password, PASSWORD_DEFAULT) // Sécurisation du mot de passe
    ]);

    echo 'Entrées ajoutées dans la table. Redirection en cours...';
    echo '<script>
            setTimeout(function() {
                window.location.href = "../accueil.html";
            }, 2000);
          </script>';
    
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
