<?php 
$css_supplementaire = 'assets/css/index.css';
include 'includes/header.php';
?>

<main>
    <!-- Section Hero -->
    <section id="hero" class="hero">
        <h1>Transformez Votre Corps</h1>
        <p>Atteignez vos objectifs fitness avec nos programmes personnalisés</p>
        <a href="pages/formulaire.php" class="btn">Rejoignez-nous</a>
    </section>

    <!-- Section À Propos -->
    <section id="apropos">
        <div class="conteneur">
            <div class="galerie">
                <img src="assets/img/training.jpeg" alt="Cardio training">
                <img src="assets/img/musculation.jpeg" alt="Musculation">
                <img src="assets/img/coaching.jpeg" alt="Coaching">
            </div>
            <article>
                <h2><span>À</span> Propos</h2>
                <h3>De l'instant où vous franchissez notre porte, vous savez que FitZone est un lieu unique</h3>
                <p>Depuis plus de 10 ans, FitZone Gym est le leader de l'entraînement sportif dans la région. Notre mission est simple : vous aider à atteindre vos objectifs fitness dans un environnement motivant et professionnel.</p>
                <p class="description">Avec plus de 2000m² d'équipements dernière génération, une équipe de coachs diplômés et une atmosphère conviviale, nous vous offrons bien plus qu'une simple salle de sport. Nous créons une véritable communauté où chacun trouve sa place.</p>
            </article>
        </div>
    </section>

    <!-- Section Pourquoi Nous Choisir -->
    <section id="programmes">
        <div class="conteneur">
            <h2><span>Pourquoi</span> Nous Choisir</h2>
            <p class="intro">Développez force, endurance et confiance avec nous</p>
            <section class="raisons">
                <div class="aside">
                    <img src="assets/img/seance.jpeg" alt="Seance de training">
                    <img src="assets/img/equipement.jpeg" alt="equipement moderne">
                </div>
                <div class="liste">
                    <article class="raison">
                        <strong>1</strong>
                        <div>
                            <h3>Coachs Certifiés</h3>
                            <p>Entraînez-vous avec des professionnels qui guident chacun de vos mouvements pour assurer une forme correcte, la sécurité et des résultats plus rapides.</p>
                        </div>
                    </article>
                    <article>
                        <strong>2</strong>
                        <div>
                            <h3>Équipements Modernes</h3>
                            <p>Profitez de machines de pointe et d'outils d'entraînement fonctionnel qui rendent chaque séance efficace, amusante et stimulante.</p>
                        </div>
                    </article>
                    <article>
                        <strong>3</strong>
                        <div>
                            <h3>Environnement Motivant</h3>
                            <p>Rejoignez une communauté solidaire qui vous inspire à rester constant, repousser vos limites et célébrer chaque étape de votre parcours.</p>
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </section>
</main>

<?php 
include 'includes/footer.php';
?>