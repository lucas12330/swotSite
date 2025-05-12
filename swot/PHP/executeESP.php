<?php
include 'bddConnect.php';

// Création de la connexion à la base de données
$db = new Database('localhost', 'swot', 'root', '');
$dbco = $db->getConnection();

//* Récupération des données reçus de l'ESP 32
if(isset($_POST['data'])){
    $data = $_POST['data'];
    $date = date('Y-m-d H:i:s');
    $stmt = $dbco->prepare("INSERT INTO data (DATE, HEURE, PROFONDEUR) VALUES (:date, :heure, :profondeur)");
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':heure', $data['heure']);
    $stmt->bindParam(':profondeur', $data['profondeur']);
    
    if($stmt->execute()){
        echo "Données insérées avec succès.";
    } else {
        echo "Erreur lors de l'insertion des données.";
    }
} else {
    echo "Aucune donnée reçue.";
}