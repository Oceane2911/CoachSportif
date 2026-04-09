<?php
// require "../includes/header.php";
// require "../includes/footer.php";
include "../config/config.php";
$requet_motif = $pdo->query("SELECT id, nom FROM motif");
$requet_coach = $pdo->query("SELECT id, nom, prenom FROM coach");
$donnees = $requet_motif->fetchAll();
$donnees_coach = $requet_coach->fetchAll();
var_dump($donnees_coach);

// Requete pour ajouter
$add = "INSERT INTO prestation(nom,prenom,email,tel,date,motif_id,coach_id) VALUES (:nom, :prenom, :email, :tel, :date, :motif_id, :coach_id)";
$sth = $pdo->prepare($add);


if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nom = htmlspecialchars(trim($_POST['lastname']));
    $prenom = htmlspecialchars(trim($_POST['firstname']));
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $date = $_POST['date'];
    $motif_id = $_POST['motif'];
    $coach_id = $_POST['coach'];
    $sth->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'tel' => $tel, 'date' => $date, 'motif_id' => $motif_id, 'coach_id' => $coach_id]);
};



// date du jour +24h
$ajd_date = date('Y-m-d\Th:i', strtotime("+1 day"));


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/formulaire.css">
    <title>FitZone</title>
</head>
<body>
    <header>
        <div class="logo">
            <p>FitZone</p>
        </div>
        <nav>
            <ul>
                <li><a href="../../index.php">Accueil</a></li>
                <li><a href="../pages/...">Document</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <article class="formulaire">
                <h1>Rendez-vous</h1>
                <form action="#" method="post">
                    <div class="input">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" placeholder="Prénom" required>
                    </div>
                    <div class="input">
                        <label for="flastname">Mom</label>
                        <input type="text" name="lastname" placeholder="Nom" required>
                    </div>
                    <div class="input">
                        <label for="tel">Téléphone</label>
                        <input type="tel" name="tel" placeholder="Téléphone" required>
                    </div>
                    <div class="input">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Email" required>
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
                        <input type="datetime-local" name="date" placeholder="Horaire" min="<?= $ajd_date ?>" required>
                    </div>
                    <button type="submit">Envoyer →</button>
                </form>
            </article>
        </section>
    </main>
</body>
</html>