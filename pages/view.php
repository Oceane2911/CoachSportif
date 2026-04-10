<?php 
require_once '../config/config.php';

// Récupération de l'UUID du document depuis l'URL
$uuid = $_GET['uuid'] ?? '';

// Si aucun UUID fourni, accès refusé
if (empty($uuid)) {
    header('Location: no-access.php');
    die;
}

// Récupération du chemin du fichier en BDD
$request = $pdo->prepare("
    SELECT d.path
    FROM document d
    JOIN prestation p ON d.prestation_id = p.id
    WHERE d.uuid = :uuid
    LIMIT 1
");
$request->execute([':uuid' => $uuid]);
$path = $request->fetch(PDO::FETCH_ASSOC);

// Si aucun document trouvé, accès refusé
if (!$path) {
    header('Location: no-access.php');
    die;
}

// Construction du chemin absolu vers le fichier
$filePath = '../documents/' . $path['path'];

// Vérification que le fichier existe bien sur le serveur
if (!file_exists($filePath)) {
    die('Fichier introuvable.');
}

// Envoi des headers pour afficher le PDF directement dans le navigateur
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
header('Content-Length: ' . filesize($filePath));

// Envoi du contenu du fichier au navigateur
readfile($filePath);
die;