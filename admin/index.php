<?php
$pageTitle = 'Dashboard – Admin';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
requireLogin();

$db = getDb();

$totalBooks = (int) $db->query('SELECT COUNT(*) FROM buecher')->fetchColumn();
$soldBooks = (int) $db->query('SELECT COUNT(*) FROM buecher WHERE verkauft = 1')->fetchColumn();
$availBooks = $totalBooks - $soldBooks;
$totalCustomers = (int) $db->query('SELECT COUNT(*) FROM kunden')->fetchColumn();

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="admin-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1>Dashboard</h1>
                <p>Willkommen, <?= htmlspecialchars(currentUser()['name'] ?? '') ?></p>
            </div>
        </div>

        <!-- Statistiken -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Anzahl Bücher</div>
                <div class="stat-value stat-primary"><?= $totalBooks ?></div>
                <div class="stat-sub">Gesamtbestand</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Anzahl Kunden</div>
                <div class="stat-value stat-primary"><?= $totalCustomers ?></div>
                <div class="stat-sub">Registrierte Kunden</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Verkaufte Bücher</div>
                <div class="stat-value stat-accent"><?= $soldBooks ?></div>
                <div class="stat-sub">Bereits verkauft</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Verfügbare Bücher</div>
                <div class="stat-value stat-green"><?= $availBooks ?></div>
                <div class="stat-sub">Noch verfügbar</div>
            </div>
        </div>

        <!-- Schnellzugriff -->
        <h2 class="mb-2" style="color:var(--primary);">Schnellzugriff</h2>
        <div class="quick-grid">
            <a href="<?= BASE_URL ?>/admin/books.php" class="quick-card">
                <div class="quick-icon"></div>
                <div>
                    <div class="quick-title">Bücher verwalten</div>
                    <div class="quick-desc">Bücher hinzufügen, bearbeiten oder löschen</div>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/admin/customers.php" class="quick-card">
                <div class="quick-icon"></div>
                <div>
                    <div class="quick-title">Kunden verwalten</div>
                    <div class="quick-desc">Kundendaten einsehen und bearbeiten</div>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/books.php" class="quick-card">
                <div class="quick-icon"></div>
                <div>
                    <div class="quick-title">Sammlung ansehen</div>
                    <div class="quick-desc">Öffentliche Bücherliste aufrufen</div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>