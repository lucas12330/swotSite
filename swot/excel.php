<?php 
/*
 * Created on Sat Apr 12 2025
 *
 * Copyright (c) 2025 ALtear Tech
 */
// ? include de la classe bddConnect
include './PHP/bddConnect.php';

if(isset($_POST['back'])){
    header("Location: accueil.php"); // Redirection vers login.php après la déconnexion
    exit();
}

// ? Quand le bouton prendre la mesure est appuyé cela ouvre excel.php et envoi des donné a la bdd
if (isset($_POST['data'])) {
    // Récupérer les valeurs du formulaire
    $date = $_POST['date'];
    $time = $_POST['heure'];
    $depth = $_POST['profondeur'];

    // Connexion à la base de données
    $db = new Database('localhost', 'swot', 'root', '');
    $pdo = $db->getConnection();

    try {
        // Préparer une requête pour insérer les données
        $stmt = $pdo->prepare("INSERT INTO data (DATE, HEURE, PROFONDEUR) VALUES (:date, :time, :depth)");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':depth', $depth);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Mesure enregistrée avec succès !";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}



?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation  de prise de mesure</title>
    <link rel="stylesheet" href="./CSS/excel.css">
</head>
<body>
    <header>
        <div>
            <form method="post">
                <button type="submit" name="back">Retourner a l'accueil</button>
            </form>
        </div>
    </header>
    <form method="post">
        <div class="container">
            <h2>Simulation de prise de mesure</h2>
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Heure :</label>
            <input type="time" id="heure" name="heure" required>

            <label for="depth">Profondeur (en m) :</label>
            <input type="number" id="profondeur" name="profondeur" required>

            <button type="submit" name="data">Prendre la mesure</button>
        </div>
    </form>

    
</body>
</html>

