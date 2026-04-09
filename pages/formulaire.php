<?php
// require "../includes/header.php";
// include "../includes/header.php";
// require "../includes/footer.php";
include "../config/config.php";
$requet_motif = $pdo->query("SELECT id, nom FROM motif");
$requet_coach = $pdo->query("SELECT id, nom, prenom FROM coach");
$donnees = $requet_motif->fetchAll();
$donnees_coach = $requet_coach->fetchAll();

// Requete pour ajouter
$add = "INSERT INTO prestation(nom,prenom,email,tel,date,motif_id,coach_id) VALUES (:nom, :prenom, :email, :tel, :date, :motif_id, :coach_id)";
$sth = $pdo->prepare($add);

// fonction
/**
 * Vérifie si la date est valide :
 * - Entre 06h00 et 23h00
 * - Pas le dimanche 
 * - Dans une tranche de 1 mois à partir de demain
 */
function estDateValide($dateSaisie) {
    $timestamp = strtotime($dateSaisie);
    if (!$timestamp) return false;
    $heure = (int)date('H', $timestamp);
    $jourSemaine = (int)date('w', $timestamp); 
    $dateObjet = new DateTime($dateSaisie);
    $maintenant = new DateTime();
    $demain = (new DateTime())->modify('+1 day')->setTime(0, 0);
    $dansUnMois = (new DateTime())->modify('+30 days')->setTime(23, 59);
    if ($heure < 6 || $heure >= 23) {
        return "heure";
    }
    if ($jourSemaine === 0) {
        return "dimanche";
    }
    if ($dateObjet < $demain || $dateObjet > $dansUnMois) {
        return "plage";
    }
    return true;
}

$nom = '';
$prenom = '';
$email = '';
$tel = '';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nom = htmlspecialchars(trim($_POST['lastname']));
    $prenom = htmlspecialchars(trim($_POST['firstname']));
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $date = $_POST['date'];
    $motif_id = $_POST['motif'];
    $coach_id = $_POST['coach'];
    $heure = (int)date('H', strtotime($heur));
    $validation = estDateValide($date_saisie);
    if ($validation === true) {
        $sth->execute([
            'nom' => $nom, 
            'prenom' => $prenom, 
            'email' => $email, 
            'tel' => $tel, 
            'date' => $date_saisie, 
            'motif_id' => $motif_id, 
            'coach_id' => $coach_id
        ]);
        header('Location: formulaire.php?succes=1');
        exit;
    } else {
        header("Location: formulaire.php?erreur=$validation");
        exit;
    }
};

// message de succes



// date du jour +24h
$min_date = date('Y-m-d\T06:00', strtotime("+1 day"));
$max_date = date('Y-m-d\T23:00', strtotime("+30 day"));


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/formulaire.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>FitZone</title>
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo"><img src="../assets/img/logo.svg" alt="FitZone Logo"></a>
            <ul>
                <li><a href="../index.php">accueil</a></li>
                <li><a href="documents.php">documents</a></li>
                <li><a href="formulaire.php">contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <article class="formulaire">
                <h1>Rendez-vous</h1>
                    <?php if (isset($_GET['succes']) && $_GET['succes'] == 1) : ?>
                        <div class="message_succes">
                            <p>Votre demande à bien était envoyer</p>
                        </div>  
                    <?php endif ?>
                <form action="#" method="post">
                    <div class="input">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" placeholder="Prénom" value="<?= $prenom ?>" required>
                    </div>
                    <div class="input">
                        <label for="flastname">Nom</label>
                        <input type="text" name="lastname" placeholder="Nom" value="<?= $nom ?>" required>
                    </div>
                    <div class="input">
                        <label for="tel">Téléphone</label>
                        <input type="tel" name="tel" placeholder="Téléphone" pattern="[0-9]{10}" value="<?= $tel ?>" required>
                    </div>
                    <div class="input">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Email" value="<?= $email ?>" required>
                    </div>
                    <div class="input">
                        <label for="motif">Motif</label>
                        <select name="motif" required>
                            <option value="">Sélectionner un motif</option>
                            <?php foreach($donnees as $donnee) : ?>
                                <option value="<?= $donnee['id'] ?>"><?= $donnee['nom'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="input">
                        <label for="coach">Coach</label>
                        <select name="coach" required>
                            <option value="">Sélectionner un Coach</option>
                            <?php foreach($donnees_coach as $donne) : ?>
                                <option value="<?= $donne['id'] ?>"><?= $donne['nom'].' '.$donne['prenom'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="input">
                        <label for="date">date</label>
                        <input type="datetime-local" name="date" placeholder="Horaire" min="<?= $min_date ?>" max="<?= $max_date ?>" step="900" required>
                        <?php if (isset($_GET['erreur'])) : ?>
                            <div class="message_erreur" style="color: red; font-size: 0.8em;">
                                <?php 
                                    switch($_GET['erreur']) {
                                        case 'heure': echo "Veuillez choisir un horaire entre 06:00 et 23:00."; break;
                                        case 'dimanche': echo "Nous sommes fermés le dimanche."; break;
                                        case 'plage': echo "Le rendez-vous doit être pris entre demain et les 30 prochains jours."; break;
                                        default: echo "Date invalide.";
                                    }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit">Envoyer →</button>
                </form>
            </article>
        </section>
    </main>
</body>
</html>