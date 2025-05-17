<?php
date_default_timezone_set('Europe/Paris');
include "./bddConnect.php";
/*
 *  Créé le  Sat May 17 2025 19:54:53
 *  Par Lucas Bezanilla Bou
 *  Fichier : esp32.php
 *  Description : 
 * 
 *  Copyright (c) 2025 Altear Tech
 */
function getCurrentDate() {
    return date('Y-m-d');
}

function getCurrentTime() {
    return date('H:i:s');
}

try {
    $db = new Database('localhost', 'swot', 'root', '');
    $dbco = $db->getConnection();
    
    if(isset($_POST['esp'])){
        $stmt = $dbco->prepare("INSERT INTO data (DATE, HEURE, PROFONDEUR, timestamp) VALUES (:date, :heure, :profondeur, :timestamp)");
        $stmt->execute([
            ':date' => getCurrentDate(),
            ':heure' => getCurrentTime(),
            ':profondeur' => $_POST['profondeur'],
            ':timestamp' => $_POST['timestamp']
        ]);
    }

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit();
}