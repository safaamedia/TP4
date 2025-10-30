<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des Réservations (CSV)</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #0F172A;
    color: #E2E8F0;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #60A5FA;
}

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background: #1E293B;
    border: 2px solid #3B82F6;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    border: 1px solid #334155;
    padding: 10px;
    text-align: center;
}

th {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: #F1F5F9;
}

tr:nth-child(even) {
    background-color: #1E293B;
}

tr:nth-child(odd) {
    background-color: #0F172A;
}

tr:hover {
    background-color: #334155;
}

/* Stats section */
.stats {
    width: 60%;
    margin: 30px auto;
    background: #1E293B;
    border: 2px solid #60A5FA;
    border-radius: 8px;
    padding: 20px;
    color: #E2E8F0;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    text-align: left;
    line-height: 1.8;
}

.stats h2 {
    text-align: center;
    color: #60A5FA;
}

.success {
    text-align: center;
    color: #F1F5F9;
    background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
    border: 2px solid #60A5FA;
    width: 60%;
    margin: 20px auto;
    padding: 10px;
    border-radius: 6px;
    font-weight: bold;
}
</style>
</head>
<body>

<?php include('menu.php'); ?>

<h1>Gestion des Réservations (CSV)</h1>

<?php
$rows = [];
$file = "reservation.csv";

if (!file_exists($file)) {
    echo "<p style='color:#EF4444;text-align:center;'>Le fichier reservation.csv est introuvable.</p>";
    exit;
}

$f = fopen($file, "r");
while (($data = fgetcsv($f, 1000, ",")) !== false) {
    $rows[] = $data;
}
fclose($f);

$header = array_shift($rows);
echo "<table><tr>";
foreach ($header as $h) echo "<th>$h</th>";
echo "</tr>";
foreach ($rows as $r) {
    echo "<tr>";
    foreach ($r as $v) echo "<td>$v</td>";
    echo "</tr>";
}
echo "</table>";

// Réservations confirmées
$confirmed = [];
foreach ($rows as $r) {
    if (strtolower($r[4]) == "confirmée") $confirmed[] = $r;
}

// Statistiques
$totalConfirmed = count($confirmed);
$totalCA = 0;
foreach ($confirmed as $r) $totalCA += floatval($r[3]);

// Destination la plus populaire
$destinations = [];
foreach ($confirmed as $r) {
    $dest = $r[2];
    if (!isset($destinations[$dest])) $destinations[$dest] = 0;
    $destinations[$dest]++;
}
arsort($destinations);
$topDestination = key($destinations);

// Export Paris confirmées
$exportFile = "paris.csv";
$fparis = fopen($exportFile, "w");
fputcsv($fparis, $header);
foreach ($confirmed as $r) {
    if (strtolower($r[2]) == "paris") fputcsv($fparis, $r);
}
fclose($fparis);
?>

<div class="stats">
    <h2>Statistiques</h2>
    <p><strong>Nombre de réservations confirmées :</strong> <?php echo $totalConfirmed; ?></p>
    <p><strong>Chiffre d'affaires total :</strong> <?php echo number_format($totalCA, 2, ',', ' '); ?> DH</p>
    <p><strong>Destination la plus populaire :</strong> <?php echo $topDestination; ?></p>
</div>

<div class="success">
    Le fichier <strong>paris.csv</strong> a été créé avec succès.
</div>

</body>
</html>
