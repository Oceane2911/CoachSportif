<?php
include "../config/config.php";

// ===========================================
//  REQUÊTES BASE DE DONNÉES
// ===========================================
$requet_motif = $pdo->query("SELECT id, nom FROM motif");
$requet_coach = $pdo->query("SELECT id, nom, prenom FROM coach");
$donnees       = $requet_motif->fetchAll();
$donnees_coach = $requet_coach->fetchAll();

$add = "INSERT INTO prestation(nom,prenom,email,tel,date,motif_id,coach_id) 
        VALUES (:nom, :prenom, :email, :tel, :date, :motif_id, :coach_id)";
$sth = $pdo->prepare($add);

// ===========================================
//  FONCTION DE VALIDATION DE DATE
// ===========================================

/**
 * Vérifie si la date est valide :
 * - Entre 06h00 et 23h00
 * - Pas le dimanche
 * - Dans une tranche de 1 mois à partir de demain
 */
function estDateValide($dateSaisie) {
    $timestamp = strtotime($dateSaisie);
    if (!$timestamp) return false;

    $heure       = (int)date('H', $timestamp);
    $jourSemaine = (int)date('w', $timestamp);
    $dateObjet   = new DateTime($dateSaisie);
    $demain      = (new DateTime())->modify('+1 day')->setTime(0, 0);
    $dansUnMois  = (new DateTime())->modify('+30 days')->setTime(23, 59);

    if ($heure < 6 || $heure >= 23)              return "heure";
    if ($jourSemaine === 0)                       return "dimanche";
    if ($dateObjet < $demain || $dateObjet > $dansUnMois) return "plage";

    return true;
}

// ===========================================
//  INITIALISATION DES VARIABLES DU FORMULAIRE
// ===========================================
$nom      = '';
$prenom   = '';
$email    = '';
$tel      = '';
$date     = '';
$motif_id = '';
$coach_id = '';
$erreur   = null;

// ===========================================
//  TRAITEMENT DU FORMULAIRE (POST)
// ===========================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom      = htmlspecialchars(trim($_POST['lastname']));
    $prenom   = htmlspecialchars(trim($_POST['firstname']));
    $email    = $_POST['email'];
    $tel      = $_POST['tel'];
    $date     = $_POST['date'];
    $motif_id = $_POST['motif'];
    $coach_id = $_POST['coach'];

    $validation = estDateValide($date);

    if ($validation === true) {
        // Date valide > on insère et on redirige
        $sth->execute([
            'nom'      => $nom,
            'prenom'   => $prenom,
            'email'    => $email,
            'tel'      => $tel,
            'date'     => $date,
            'motif_id' => $motif_id,
            'coach_id' => $coach_id
        ]);
        header('Location: formulaire.php?succes=1');
        exit;
    } else {
        // Date invalide > on stocke l'erreur, pas de redirection
        $erreur = $validation;
    }
}

// ===========================================
//  CALCUL DES DATES MIN / MAX
// ===========================================
$min_date = date('Y-m-d\T06:00', strtotime("+1 day"));
$max_date = date('Y-m-d\T23:00', strtotime("+30 day"));
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/formulaire.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>FitZone</title>
</head>

<!-- ================ SCRIPT  ================ -->
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<body>

<!-- ================ HEADER  ================ -->
<header>
    <nav>
        <a href="index.php" class="logo">
            <img src="../assets/img/logo.svg" alt="FitZone Logo">
        </a>
        <ul>
            <li><a href="../index.php">accueil</a></li>
            <li><a href="no-access.php">documents</a></li>
            <li><a href="formulaire.php">contact</a></li>
        </ul>
    </nav>
</header>

<!-- ═================ MAIN  ================ -->
<main>
    <section>
        <article class="formulaire">
            <h1>Rendez-vous</h1>

            <!-- Message de succès -->
            <?php if (isset($_GET['succes']) && $_GET['succes'] == 1) : ?>
                <div class="message_succes">
                    <p>Votre demande a bien été envoyée.</p>
                </div>
            <?php endif ?>

            <form action="#" method="post">

                <!-- Prénom -->
                <div class="input">
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" placeholder="Prénom"
                           value="<?= $prenom ?>" required>
                </div>

                <!-- Nom -->
                <div class="input">
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" placeholder="Nom"
                           value="<?= $nom ?>" required>
                </div>

                <!-- Téléphone -->
                <div class="input">
                    <label for="tel">Téléphone</label>
                    <input type="tel" name="tel" placeholder="Téléphone"
                           pattern="[0-9]{10}" value="<?= $tel ?>" required>
                </div>

                <!-- Email -->
                <div class="input">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email"
                           value="<?= $email ?>" required>
                </div>

                <!-- Motif -->
                <div class="input">
                    <label for="motif">Motif</label>
                    <select name="motif" required>
                        <option value="">Sélectionner un motif</option>
                        <?php foreach ($donnees as $donnee) : ?>
                            <option value="<?= $donnee['id'] ?>"
                                <?= ($motif_id == $donnee['id']) ? 'selected' : '' ?>>
                                <?= $donnee['nom'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Coach -->
                <div class="input">
                    <label for="coach">Coach</label>
                    <select name="coach" required>
                        <option value="">Sélectionner un Coach</option>
                        <?php foreach ($donnees_coach as $donne) : ?>
                            <option value="<?= $donne['id'] ?>"
                                <?= ($coach_id == $donne['id']) ? 'selected' : '' ?>>
                                <?= $donne['nom'] . ' ' . $donne['prenom'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Date + message d'erreur -->
                <div class="input">
                    <label for="date">Date</label>
                    <input type="datetime-local" name="date" placeholder="Horaire"
                           min="<?= $min_date ?>" max="<?= $max_date ?>"
                           value="<?= $date ?>" step="900" required>

                    <?php if ($erreur) : ?>
                        <div class="message_erreur">
                            <?php switch ($erreur) {
                                case 'heure':    echo "Veuillez choisir un horaire entre 06:00 et 23:00."; break;
                                case 'dimanche': echo "Nous sommes fermés le dimanche."; break;
                                case 'plage':    echo "Le rendez-vous doit être pris entre demain et les 30 prochains jours."; break;
                                default:         echo "Date invalide.";
                            } ?>
                        </div>
                    <?php endif ?>
                </div>

                <button type="submit">Envoyer →</button>

            </form>
        </article>
    </section>
</main>
<?php 
include '../includes/footer.php';
?>
