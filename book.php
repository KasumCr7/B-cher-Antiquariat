<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/config.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ' . BASE_URL . '/books.php'); exit; }

$db   = getDb();
$stmt = $db->prepare('
    SELECT b.*, k.kategorie AS kategorie_name
    FROM buecher b
    LEFT JOIN kategorien k ON k.id = b.kategorie
    WHERE b.id = ?
');
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) { header('Location: ' . BASE_URL . '/books.php'); exit; }

$pageTitle = htmlspecialchars($book['Title']) . ' – Antiquariat Kassius';
require_once __DIR__ . '/includes/header.php';
?>

<div class="book-detail">
    <div class="container">
        <a href="<?= BASE_URL ?>/books.php" class="back-link">&#8592; Zurück zur Sammlung</a>

        <div class="book-detail-grid">
            <div>
<<<<<<< HEAD
                <div class="book-detail-cover" style="overflow:hidden; padding:0;">
                    <img src="<?= BASE_URL ?>/Bilder/onwardDrakeCover.jpg" alt="<?= htmlspecialchars($book['Title']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
                </div>
=======
                <div class="book-detail-cover">📖</div>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                <?php if ($book['verkauft']): ?>
                    <span class="badge badge-sold" style="margin-top:1rem; display:block; text-align:center;">Verkauft</span>
                <?php else: ?>
                    <span class="badge badge-avail" style="margin-top:1rem; display:block; text-align:center;">Verfügbar</span>
                <?php endif; ?>
            </div>

            <div>
                <h1 class="book-detail-title"><?= htmlspecialchars($book['Title'] ?? '–') ?></h1>

                <ul class="book-meta-list">
                    <li><strong>Autor:</strong>           <?= htmlspecialchars($book['autor'] ?? '–') ?></li>
                    <li><strong>Kategorie:</strong>       <?= htmlspecialchars($book['kategorie_name'] ?? '–') ?></li>
                    <li><strong>Katalognummer:</strong>   <?= $book['katalog'] ?>-<?= $book['nummer'] ?></li>
                    <li><strong>Zustand:</strong>         <?= htmlspecialchars($book['zustand'] ?? '–') ?></li>
                    <li><strong>Status:</strong>          <?= $book['verkauft'] ? '<span class="text-accent fw-bold">Verkauft</span>' : '<span class="text-green fw-bold">Verfügbar</span>' ?></li>
                </ul>

                <?php if (!empty($book['Beschreibung'])): ?>
                    <div class="book-desc">
                        <h4>Beschreibung</h4>
                        <p><?= nl2br(htmlspecialchars($book['Beschreibung'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
