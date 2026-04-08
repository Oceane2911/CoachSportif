<?php
// require "../includes/header.php";
// require "../includes/footer.php";
include "../config/config.php";
$reponse = $pdo->query("SELECT nom FROM motif");
$donnees = $reponse->fetchAll();


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
                        <input type="text" name="firstname" placeholder="Prénom">
                    </div>
                    <div class="input">
                        <label for="flastname">Mom</label>
                        <input type="text" name="lastname" placeholder="Nom">
                    </div>
                    <div class="input">
                        <label for="tel">Téléphone</label>
                        <input type="text" name="tel" placeholder="Téléphone">
                    </div>
                    <div class="input">
                        <label for="email">Email</label>
                        <input type="text" name="email" placeholder="Email">
                    </div>
                    
                    <div class="input">
                        <label for="motif">Motif</label>
                        <select name="pets" id="pet-select">
                            <option value="">Sélectionner un motif</option>
                            <?php foreach($donnees as $donnee) : ?>
                                <option value="<?= $donnee['nom'] ?>"><?= $donnee['nom'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="input">
                        <label for="date">date</label>
                        <input type="datetime-local" name="date" placeholder="Horaire">
                    </div>
                    <button type="submit">Envoyer →</button>
                </form>
            </article>
        </section>
    </main>
</body>
</html>