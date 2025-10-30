<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des Réservations (CSV)</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #F8FAFC 0%, #E0E7FF 100%);
    color: #1E293B;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #6366F1;
}

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background: #FFFFFF;
    border: 2px solid #A5B4FC;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
}

th, td {
    border: 1px solid #E0E7FF;
    padding: 10px;
    text-align: center;
}

th {
    background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    color: #FFFFFF;
}

tr:nth-child(even) {
    background-color: #F1F5F9;
}

tr:nth-child(odd) {
    background-color: #FFFFFF;
}

tr:hover {
    background-color: #E0E7FF;
}

/* Stats section */
.stats {
    width: 60%;
    margin: 30px auto;
    background: #FFFFFF;
    border: 2px solid #A5B4FC;
    border-radius: 8px;
    padding: 20px;
    color: #1E293B;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
    text-align: left;
    line-height: 1.8;
}

.stats h2 {
    text-align: center;
    color: #6366F1;
}

.success {
    text-align: center;
    color: #1E293B;
    background: linear-gradient(135deg, #FDE68A 0%, #FCD34D 100%);
    border: 2px solid #FCD34D;
    width: 60%;
    margin: 20px auto;
    padding: 10px;
    border-radius: 6px;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(252, 211, 77, 0.3);
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
