<?php
// Démarrage de la session
session_start();

require_once '../config/config.php';

// Récupération de l'UUID depuis l'URL (lien reçu par mail)
$uuid = $_GET['uuid'] ?? '';

// Si aucun UUID fourni, accès refusé
if (empty($uuid)) {
    header('Location: no-access.php');
    die;
}

// Vérification que l'UUID correspond à une prestation en BDD
$request = $pdo->prepare("
    SELECT p.nom, p.prenom, p.email
    FROM document d
    JOIN prestation p ON d.prestation_id = p.id
    WHERE d.uuid = :uuid
    LIMIT 1
");
$request->execute([':uuid' => $uuid]);
$prestation = $request->fetch(PDO::FETCH_ASSOC);

// Si l'UUID est inconnu, accès refusé
if (!$prestation) {
    header('Location: ../no-access.php');
    die;
}

// Traitement du formulaire de vérification du code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $uuidPost = $_POST['uuid'] ?? '';

    // Vérification que le code correspond à l'UUID en BDD
    $codeBdd = $pdo->prepare("
        SELECT id 
        FROM document 
        WHERE uuid = :uuid 
        AND code_acces = :code 
        LIMIT 1
    ");
    $codeBdd->execute([':uuid' => $uuidPost, ':code' => $code]);
    $documentValide = $codeBdd->fetch();

    if ($documentValide) {
        // Code valide → on stocke l'accès en session et on redirige
        $_SESSION['uuid_valide'] = $uuidPost;
        header('Location: documents.php?uuid=' . urlencode($uuidPost));
        die;
    } else {
        // Code incorrect → message d'erreur
        $error = 'Code incorrect. Veuillez réessayer.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone - Vérification</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/verification.css">
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav>
            <a href="../index.php" class="logo"><img src="../assets/img/logo.svg" alt="FitZone Logo"></a>
            <ul>
                <li><a href="../index.php">accueil</a></li>
                <li><a href="verification.php">documents</a></li>
                <li><a href="formulaire.php">contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="conteneur">
            <article class="documents">
                <h1>récupérez vos</h1>
                <h1>documents</h1>
                <p>Accédez à vos documents personnalisés en toute sécurité.<br>Entrez votre code secret reçu par email pour consulter les contenus envoyés par votre coach sportif.</p>
            </article>
            <div class="form-container">
                <form action="verification.php" method="post">
                    <div class="input">
                        <input type="hidden" name="uuid" value="<?= htmlspecialchars($uuid) ?>">
                        <label for="code">code</label>
                        <input type="password" name="code" placeholder="Entrez votre code secret..." required>
                    </div>
                    <?php if (!empty($error)): ?>
                    <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                    <button class="btn" type="submit">récupérer maintenant</button>
                </form>
            </div>
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
                <p>Lun - Ven : 6h - 22h</p>
                <p>Sam - Dim : 8h - 20h</p>
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