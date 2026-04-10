<?php 
// Démarrage de la session
session_start();

require_once '../config/config.php';

// Récupération de l'UUID depuis l'URL
$uuid = $_GET['uuid'] ?? '';

// Si aucun UUID fourni, accès refusé
if (empty($uuid)) {
    header('Location: no-access.php');
    die;
}

// Vérification que le client a bien validé son code sur verification.php
if (!isset($_SESSION['uuid_valide']) || $_SESSION['uuid_valide'] !== $uuid) {
    header('Location: no-access.php');
    die;
}

// Récupération de tous les documents liés à la prestation du client
$request = $pdo->prepare("
    SELECT d.uuid, d.path, d.prestation_id
    FROM document d
    JOIN prestation p ON d.prestation_id = p.id
    WHERE d.uuid = :uuid
");
$request->execute([':uuid' => $uuid]);
$documents = $request->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/documents.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Fitzone - Documents</title>
</head>
<body>
    <header>
        <nav>
            <a href="../index.php" class="logo"><img src = "../assets/img/logo.svg" alt="FitZone Logo"></a>
            <ul>
                <li><a href="../index.php">accueil</a></li>
                <li><a href="no-access.php">documents</a></li>
                <li><a href="formulaire.php">contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="conteneur">
            <h1>VOS <span>DOCUMENTS</span></h1>
            <?php foreach ($documents as $document): ?>
            <div class="document">
                <i class="fa-regular fa-file document-icon"></i>
                <p><?= htmlspecialchars(basename($document['path'])) ?></p>
                <div class="document-actions">
                    <a href="view.php?uuid=<?= urlencode($document['uuid']) ?>"><i class="fa-solid fa-eye"></i></a>
                    <a href="download.php?uuid=<?= urlencode($document['uuid']) ?>"><i class="fa-solid fa-download"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <div class="footer-conteneur">
            <section>
                <img src="../assets/img/logo.svg" alt="Logo FitZone">
                <p>Votre partenaire fitness depuis 2014</p>
            </section>
            <section>
                <h3>Horaires</h3>
                <p>Lun - Sam : 6h - 23h</p>
                <p>Dim : Fermé</p>
            </section>
            <section>
                <h3>Contact</h3>
                <p>123 Rue du Sport, 75000 Paris</p>
                <p>01 23 45 67 89</p>
                <p>contact-fitzone@gmail.com</p>
            </section>
        </div>
        <p class="copyright">&copy; 2026 FitZone - Tous droits réservés</p>
    </footer>
</body>
</html>