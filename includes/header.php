<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone - Votre Salle de Sport</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
    <?php if (isset($css_supplementaire)): ?>
        <link rel="stylesheet" href="<?= $css_supplementaire ?>">
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav>
            <a href="index.php" class="logo"><img src="<?= $baseUrl ?>/assets/img/logo.svg" alt="FitZone Logo"></a>
            <ul>
                <li><a href="index.php">accueil</a></li>
                <li><a href="pages/verification.php">documents</a></li>
                <li><a href="pages/formulaire.php">contact</a></li>
            </ul>
        </nav>
    </header>


<!-- A METTRE DANS UNE PAGE POUR RAJOUTER UN FICHIER CSS
$css_supplementaire = 'assets/css/nom_du_fichier.css';
include 'includes/header.php';
-->