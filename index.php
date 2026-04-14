<?php
$pageTitle = 'Antiquariat Kassius – Startseite';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<div class="hero">
    <div class="container grid-2" style="align-items:center; gap:3rem;">
        <div>
            <h1>Willkommen im<br>Antiquariat Kassius</h1>
            <p>Seit 1985 Ihr verlässlicher Partner für seltene und wertvolle Bücher. Entdecken Sie literarische Schätze
                aus vergangenen Jahrhunderten.</p>
            <div class="hero-buttons">
                <a href="<?= BASE_URL ?>/books.php" class="btn btn-lg btn-hero-secondary">Zur Büchersammlung</a>
                <a href="<?= BASE_URL ?>/login.php" class="btn btn-lg btn-hero-outline">Admin Login</a>
            </div>
        </div>
        <div class="hero-img">
            <img src="<?= BASE_URL ?>/Bilder/BuchShop.jpg" alt="Antiquariat Innenansicht">
        </div>
    </div>
</div>

<!-- Was wir bieten -->
<div class="section">
    <div class="container">
        <h2 class="section-title">Was wir bieten</h2>
        <p class="section-subtitle">Das Antiquariat Kassius ist Ihre erste Adresse für antiquarische Bücher,
            Erstausgaben und bibliophile Raritäten.</p>

        <div class="grid-3 mt-3">
            <div class="card card-body feature-card">
                <div class="feature-icon"></div>
                <h3>Umfangreiche Sammlung</h3>
                <p>Über 5.000 sorgfältig ausgewählte Bücher aus verschiedenen Epochen und Genres. Von Klassikern bis zu
                    seltenen Erstausgaben.</p>
            </div>
            <div class="card card-body feature-card">
                <div class="feature-icon"></div>
                <h3>Expertise &amp; Qualität</h3>
                <p>Jedes Buch wird von unserer Expertin Emmaluana Bielser geprüft und bewertet. Transparente
                    Zustandsbeschreibungen
                    garantieren höchste Qualität.</p>
            </div>
            <div class="card card-body feature-card">
                <div class="feature-icon"></div>
                <h3>Einfache Suche</h3>
                <p>Unsere intuitive Suchfunktion hilft Ihnen, schnell und einfach genau das Buch zu finden, das Sie
                    suchen.</p>
            </div>
        </div>

        <div class="grid-2 mt-3" style="align-items:center;">
            <div>
                <h3 style="color:var(--primary); font-size:1.8rem; margin-bottom:1rem;">Tradition trifft Moderne</h3>
                <p style="color:var(--text-muted); margin-bottom:1rem; line-height:1.8;">Seit über 35 Jahren sammeln,
                    bewahren und vermitteln wir wertvolle Bücher. Unser Antiquariat verbindet traditionelle Buchkunst
                    mit modernem Service.</p>
                <p style="color:var(--text-muted); margin-bottom:1.5rem; line-height:1.8;">Jedes Buch erzählt eine
                    Geschichte – nicht nur durch seinen Inhalt, sondern auch durch seine Herkunft und seinen Zustand.
                </p>
                <a href="<?= BASE_URL ?>/books.php" class="btn btn-primary btn-lg">Sammlung durchstöbern</a>
            </div>
            <div class="hero-img">
                <img src="<?= BASE_URL ?>/Bilder/Bücherregal.jpg" alt="Vintage Bücher">
            </div>
        </div>
    </div>
</div>

<!-- Unsere Leistungen -->
<div class="section-muted">
    <div class="container">
        <h2 class="section-title">Unsere Leistungen</h2>
        <div class="grid-3">
            <div class="leistung">
                <div class="leistung-icon"></div>
                <div>
                    <h4>Seit 1985</h4>
                    <p>Über 35 Jahre Erfahrung im antiquarischen Buchhandel</p>
                </div>
            </div>
            <div class="leistung">
                <div class="leistung-icon"></div>
                <div>
                    <h4>Vertrauenswürdig</h4>
                    <p>Authentizität und faire Preise garantiert</p>
                </div>
            </div>
            <div class="leistung">
                <div class="leistung-icon"></div>
                <div>
                    <h4>Persönlicher Service</h4>
                    <p>Individuelle Beratung für Sammler und Liebhaber</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Was Sie bei uns finden -->
<div class="section">
    <div class="container grid-2" style="align-items:center; gap:3rem;">
        <div class="hero-img">
            <img src="https://images.unsplash.com/photo-1762553338304-c4bfc4000488?w=800" alt="Alte Buchhandlung"
                style="height:400px; width:100%; object-fit:cover;">
        </div>
        <div>
            <h2 style="color:var(--primary); margin-bottom:1.5rem;">Was Sie bei uns finden</h2>
            <ul style="list-style:none; display:flex; flex-direction:column; gap:1rem;">
                <li style="display:flex; gap:0.75rem; align-items:flex-start;">
                    <span
                        style="width:24px; height:24px; border-radius:50%; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; font-size:0.8rem;">✓</span>
                    <div><strong>Erstausgaben &amp; Raritäten</strong><br><span style="color:var(--text-muted);">Seltene
                            Erstausgaben bedeutender Werke</span></div>
                </li>
                <li style="display:flex; gap:0.75rem; align-items:flex-start;">
                    <span
                        style="width:24px; height:24px; border-radius:50%; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; font-size:0.8rem;">✓</span>
                    <div><strong>Klassische Literatur</strong><br><span style="color:var(--text-muted);">Von Goethe bis
                            moderne Klassiker</span></div>
                </li>
                <li style="display:flex; gap:0.75rem; align-items:flex-start;">
                    <span
                        style="width:24px; height:24px; border-radius:50%; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; font-size:0.8rem;">✓</span>
                    <div><strong>Verschiedene Zustände</strong><br><span style="color:var(--text-muted);">Von sehr gut
                            erhaltenen bis zu gebrauchten Exemplaren</span></div>
                </li>
                <li style="display:flex; gap:0.75rem; align-items:flex-start;">
                    <span
                        style="width:24px; height:24px; border-radius:50%; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; font-size:0.8rem;">✓</span>
                    <div><strong>Katalogisiert &amp; Dokumentiert</strong><br><span
                            style="color:var(--text-muted);">Jedes Buch mit detaillierter Beschreibung</span></div>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>