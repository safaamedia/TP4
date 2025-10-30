<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
body {
    margin: 0;
    padding: 0;
    background-color: #F8FAFC;
    font-family: 'Segoe UI', Arial, sans-serif;
}

nav {
    background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 40px;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
}

nav h1 {
    font-size: 22px;
    color: #FDE68A;
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
    color: #FFFFFF;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    transition: 0.3s;
    padding: 8px 14px;
    border-radius: 5px;
}

ul.menu li a:hover {
    background-color: #FDE68A;
    color: #6366F1;
}

ul.menu li a.active {
    background-color: #FFFFFF;
    color: #6366F1;
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
