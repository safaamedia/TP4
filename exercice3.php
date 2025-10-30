<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion d'un catalogue de films (JSON)</title>

<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #F8FAFC 0%, #E0E7FF 100%);
    color: #1E293B;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #6366F1;
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
    background: #FFFFFF;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #A5B4FC;
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
    font-size: 16px;
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

/* Stats box */
.stats {
    background: #FFFFFF;
    width: 70%;
    margin: 30px auto;
    border: 2px solid #A5B4FC;
    border-radius: 8px;
    padding: 20px;
    color: #1E293B;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
    line-height: 1.8;
}

/* Success message */
.success {
    color: #1E293B;
    font-weight: bold;
    text-align: center;
    background: linear-gradient(135deg, #FDE68A 0%, #FCD34D 100%);
    border: 2px solid #FCD34D;
    width: 60%;
    margin: 20px auto;
    padding: 10px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(252, 211, 77, 0.3);
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
