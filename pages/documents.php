<?php 
include 'config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone - Votre Salle de Sport</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/documents.css">
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav>
            <a href="../index.php" class="logo"><img src="../assets/img/logo.svg" alt="FitZone Logo"></a>
            <ul>
                <li><a href="../index.php">accueil</a></li>
                <li><a href="documents.php">documents</a></li>
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
                <form action="documents.php" method="post">
                    <div class="input">
                        <label for="code_secret">code</label>
                        <input type="password" name="code_secret" placeholder="Entrez votre code secret..." required>
                    </div>
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