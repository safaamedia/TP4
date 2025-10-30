<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
body {
    margin: 0;
    padding: 0;
    background-color: #0F172A;
    font-family: 'Segoe UI', Arial, sans-serif;
}

nav {
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

nav h1 {
    font-size: 22px;
    color: #60A5FA;
    margin: 0;
    letter-spacing: 1px;
}

ul.menu {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}

ul.menu li {
    margin-left: 25px;
}

ul.menu li a {
    color: #E2E8F0;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    transition: 0.3s;
    padding: 8px 14px;
    border-radius: 5px;
}

ul.menu li a:hover {
    background-color: #60A5FA;
    color: #0F172A;
}

ul.menu li a.active {
    background-color: #3B82F6;
    color: #F1F5F9;
}
</style>
</head>
<body>

<nav>
    <h1>Gestion des Réservations</h1>
    <ul class="menu">
        <li><a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'active'; ?>">Exercice 1</a></li>
        <li><a href="exercice2.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='exercice2.php') echo 'active'; ?>">Exercice 2</a></li>
        <li><a href="exercice3.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='exercice3.php') echo 'active'; ?>">Exercice 3</a></li>
    </ul>
</nav>

</body>
</html>
