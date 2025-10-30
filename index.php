<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des Réservations (CSV)</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #F5E5E1;
    color: #174143;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #174143;
}

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    border: 2px solid #427A76;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}

th {
    background: #427A76;
    color: #F5E5E1;
}

tr:nth-child(even) {
    background-color: #F9B48733;
}

tr:hover {
    background-color: #F9B48766;
}

/* Stats section */
.stats {
    width: 60%;
    margin: 30px auto;
    background: white;
    border: 2px solid #F9B487;
    border-radius: 8px;
    padding: 20px;
    color: #174143;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    text-align: left;
    line-height: 1.8;
}

.stats h2 {
    text-align: center;
    color: #174143;
}

.success {
    text-align: center;
    color: #174143;
    background: #F9B48755;
    border: 2px solid #F9B487;
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
    echo "<p style='color:red;text-align:center;'>Le fichier reservation.csv est introuvable.</p>";
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
    <p><strong>Chiffre d’affaires total :</strong> <?php echo number_format($totalCA, 2, ',', ' '); ?> DH</p>
    <p><strong>Destination la plus populaire :</strong> <?php echo $topDestination; ?></p>
</div>

<div class="success">
    Le fichier <strong>paris.csv</strong> a été créé avec succès.
</div>

</body>
</html>
