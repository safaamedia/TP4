<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion d’un catalogue de films (JSON)</title>

<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #F5E5E1;
    color: #174143;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #174143;
    margin-top: 30px;
}

.container {
    padding: 20px 50px;
}

/* Table */
table {
    border-collapse: collapse;
    width: 80%;
    margin: 20px auto;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #427A76;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #427A76;
    color: #F5E5E1;
    font-size: 16px;
}

tr:nth-child(even) {
    background-color: #F9B48722;
}
tr:hover {
    background-color: #F9B48755;
}

/* Stats box */
.stats {
    background: white;
    width: 70%;
    margin: 30px auto;
    border: 2px solid #F9B487;
    border-radius: 8px;
    padding: 20px;
    color: #174143;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    line-height: 1.8;
}

/* Success message */
.success {
    color: #174143;
    font-weight: bold;
    text-align: center;
    background-color: #F9B48755;
    border: 2px solid #F9B487;
    width: 60%;
    margin: 20px auto;
    padding: 10px;
    border-radius: 6px;
}
</style>
</head>
<body>

<?php include('menu.php'); ?>

<div class="container">
<h1>Gestion d’un catalogue de films (JSON)</h1>

<?php
$jsonFile = "films.json";

if (!file_exists($jsonFile)) {
    echo "<p style='color:red;text-align:center;'>Le fichier films.json est introuvable.</p>";
    exit;
}

$data = json_decode(file_get_contents($jsonFile), true);

if (!$data) {
    echo "<p style='color:red;text-align:center;'>Erreur de lecture du fichier JSON.</p>";
    exit;
}

echo "<table>";
echo "<tr><th>Titre</th><th>Année</th><th>Genre</th><th>Durée (min)</th></tr>";

$stats = [];
$films_scifi = [];

foreach ($data as $film) {
    echo "<tr>
            <td>{$film['titre']}</td>
            <td>{$film['annee']}</td>
            <td>{$film['genre']}</td>
            <td>{$film['duree']}</td>
          </tr>";

    $genre = $film['genre'];
    if (!isset($stats[$genre])) {
        $stats[$genre] = ['count' => 0, 'duree' => 0];
    }
    $stats[$genre]['count']++;
    $stats[$genre]['duree'] += $film['duree'];

    if (strtolower($genre) == "sci-fi") {
        $films_scifi[] = $film;
    }
}
echo "</table>";

// Export Sci-Fi films
$exportFile = "films_scifi.json";
if (!empty($films_scifi)) {
    file_put_contents($exportFile, json_encode($films_scifi, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $exportMsg = "Export Sci-Fi : <strong>$exportFile</strong> (créé si des films Sci-Fi existent).";
} else {
    $exportMsg = "Aucun film Sci-Fi à exporter.";
}

echo "<div class='stats'><h2>Statistiques</h2>";
foreach ($stats as $genre => $values) {
    echo "• <strong>$genre :</strong> {$values['count']} film(s), {$values['duree']} min au total<br>";
}
echo "</div>";

echo "<div class='success'>$exportMsg</div>";
?>
</div>

</body>
</html>
