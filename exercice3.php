<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion d'un catalogue de films (JSON)</title>

<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #0F172A;
    color: #E2E8F0;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #60A5FA;
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
    background: #1E293B;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #3B82F6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

th, td {
    border: 1px solid #334155;
    padding: 10px;
    text-align: center;
}

th {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: #F1F5F9;
    font-size: 16px;
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

/* Stats box */
.stats {
    background: #1E293B;
    width: 70%;
    margin: 30px auto;
    border: 2px solid #60A5FA;
    border-radius: 8px;
    padding: 20px;
    color: #E2E8F0;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    line-height: 1.8;
}

/* Success message */
.success {
    color: #F1F5F9;
    font-weight: bold;
    text-align: center;
    background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
    border: 2px solid #60A5FA;
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
<h1>Gestion d'un catalogue de films (JSON)</h1>

<?php
$jsonFile = "films.json";

if (!file_exists($jsonFile)) {
    echo "<p style='color:#EF4444;text-align:center;'>Le fichier films.json est introuvable.</p>";
    exit;
}

$data = json_decode(file_get_contents($jsonFile), true);

if (!$data) {
    echo "<p style='color:#EF4444;text-align:center;'>Erreur de lecture du fichier JSON.</p>";
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
