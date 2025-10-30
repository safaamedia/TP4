<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Analyse d'un fichier de logs (TXT)</title>

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

.container {
    text-align: center;
    padding: 20px 50px;
}

table {
    border-collapse: collapse;
    width: 70%;
    margin: 20px auto;
    background: #1E293B;
    border-radius: 6px;
    overflow: hidden;
    border: 2px solid #3B82F6;
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

button {
    display: block;
    margin: 15px auto;
    padding: 10px 20px;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    border: none;
    color: #F1F5F9;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.report {
    width: 70%;
    margin: 20px auto;
    background: #1E293B;
    border: 2px solid #3B82F6;
    border-radius: 8px;
    padding: 15px;
    line-height: 1.6;
    color: #E2E8F0;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}
</style>
</head>
<body>

<?php include('menu.php'); ?>

<div class="container">
<h1>Analyse d'un fichier de logs (TXT)</h1>

<?php
$logFile = "access.log";

if (!file_exists($logFile)) {
    echo "<p style='color:#EF4444;text-align:center;'>Introuvable</p>";
    exit;
}

$lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$totalRequests = count($lines);
$requestsByPath = [];
$requestsByCode = [];
$errorCount = 0;

foreach ($lines as $line) {
    $parts = preg_split('/\s+/', trim($line));
    if (count($parts) < 3) continue;
    $path = $parts[1];
    $code = intval($parts[2]);

    if (!isset($requestsByPath[$path])) $requestsByPath[$path] = 0;
    $requestsByPath[$path]++;

    if (!isset($requestsByCode[$code])) $requestsByCode[$code] = 0;
    $requestsByCode[$code]++;

    if ($code >= 400) $errorCount++;
}

arsort($requestsByPath);
arsort($requestsByCode);

$mostUsedPath = array_key_first($requestsByPath);
$errorRate = $totalRequests > 0 ? round(($errorCount / $totalRequests) * 100, 2) : 0;

$rapport = "RAPPORT LOG\n";
$rapport .= "Total requêtes : $totalRequests\n";
$rapport .= "Taux d'erreur : $errorRate %\n\n";
$rapport .= "Hits par chemin :\n";
foreach ($requestsByPath as $path => $count) {
    $rapport .= "- $path : $count\n";
}
$rapport .= "\nRépartition par code :\n";
foreach ($requestsByCode as $code => $count) {
    $rapport .= "- $code : $count\n";
}

file_put_contents("rapport_log.txt", $rapport);
?>

<div class="report">
    <p><strong>Chemin le plus sollicité :</strong> <?php echo $mostUsedPath; ?></p>
    <p><strong>Taux d'erreur :</strong> <?php echo $errorRate; ?> %</p>

    <h3>Rapport généré</h3>
    <p><strong>Total requêtes :</strong> <?php echo $totalRequests; ?></p>
    <p><strong>Répartition par chemin :</strong></p>
    <table>
        <tr><th>Chemin</th><th>Requêtes</th></tr>
        <?php foreach ($requestsByPath as $path => $count) echo "<tr><td>$path</td><td>$count</td></tr>"; ?>
    </table>

    <p><strong>Répartition par code HTTP :</strong></p>
    <table>
        <tr><th>Code</th><th>Occurrences</th></tr>
        <?php foreach ($requestsByCode as $code => $count) echo "<tr><td>$code</td><td>$count</td></tr>"; ?>
    </table>

    <p><strong>Rapport sauvegardé dans :</strong> rapport_log.txt</p>
</div>
</div>

</body>
</html>
