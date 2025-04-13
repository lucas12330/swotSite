<?php 
/*
 * Created on Sat Apr 12 2025
 *
 * Copyright (c) 2025 Altear Tech
 */
include 'bddConnect.php';


$db = new Database('localhost', 'swot', 'root', '');
$dbco = $db->getConnection();

// Requête SQL
$sql = "SELECT DATE, HEURE, PROFONDEUR FROM data";
$stmt = $dbco->prepare($sql);
$stmt->execute();
$mesures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Headers CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mesures.csv');

$output = fopen('php://output', 'w');

// Titres de colonnes
fputcsv($output, ['Date', 'Heure', 'Profondeur (en m)'], ';');

// Données
foreach ($mesures as $row) {
    fputcsv($output, [
        $row['DATE'],
        substr($row['HEURE'], 0, 5),
        $row['PROFONDEUR']
    ], ';' );
}
fclose($output);
echo "<script>alert('Téléchargement terminé'); setTimeout(() => {}, 2000);</script>";
exit();



?>