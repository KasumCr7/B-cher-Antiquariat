<?php
$pageTitle = 'Bücher – Antiquariat Kassius';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';

// ── Parameter ────────────────────────────────────────────────────────────────
$search        = trim($_GET['search']    ?? '');
$searchIn      = $_GET['in']            ?? ['titel'];
if (!is_array($searchIn)) $searchIn = [$searchIn];
$filterKat     = (int)($_GET['kategorie'] ?? 0);
$filterZustand = trim($_GET['zustand']   ?? '');
$sortBy        = $_GET['sort']           ?? 'titel';
$page          = max(1, (int)($_GET['page'] ?? 1));
$perPage       = 12;

// ── Kategorien für Dropdown ───────────────────────────────────────────────────
$db   = getDb();
$kats = $db->query('SELECT id, kategorie FROM kategorien ORDER BY id')->fetchAll();

// ── Bücher-Query ─────────────────────────────────────────────────────────────
$where  = ['1=1'];
$params = [];

if ($search !== '') {
    $conditions = [];
    if (in_array('titel',    $searchIn)) { $conditions[] = 'b.Title LIKE ?';       $params[] = "%$search%"; }
    if (in_array('autor',    $searchIn)) { $conditions[] = 'b.autor LIKE ?';       $params[] = "%$search%"; }
    if (in_array('katalog',  $searchIn)) { $conditions[] = "CONCAT(b.katalog,'-',b.nummer) LIKE ?"; $params[] = "%$search%"; }
    if (in_array('kategorie',$searchIn)) { $conditions[] = 'k.kategorie LIKE ?';   $params[] = "%$search%"; }
    if ($conditions) $where[] = '(' . implode(' OR ', $conditions) . ')';
}

if ($filterKat > 0) { $where[] = 'b.kategorie = ?'; $params[] = $filterKat; }
if ($filterZustand !== '') { $where[] = 'b.zustand = ?'; $params[] = $filterZustand; }

$orderMap = ['titel' => 'b.Title', 'autor' => 'b.autor', 'katalog' => 'b.katalog, b.nummer'];
$order    = $orderMap[$sortBy] ?? 'b.Title';

$whereSQL = implode(' AND ', $where);

// Total
$countStmt = $db->prepare("SELECT COUNT(*) FROM buecher b LEFT JOIN kategorien k ON k.id = b.kategorie WHERE $whereSQL");
$countStmt->execute($params);
$total      = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

// Books
$stmt = $db->prepare("
    SELECT b.id, b.katalog, b.nummer, b.Title AS titel, b.autor,
           b.Beschreibung AS beschreibung, b.verkauft, b.zustand, k.kategorie
    FROM buecher b
    LEFT JOIN kategorien k ON k.id = b.kategorie
    WHERE $whereSQL
    ORDER BY $order
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$books = $stmt->fetchAll();

// Pagination URL builder
function pageUrl(int $p): string {
    $q = $_GET;
    $q['page'] = $p;
    return '?' . http_build_query($q);
}
?>

<div class="page-header">
    <div class="container">
        <h1>Unsere Sammlung</h1>
    </div>
</div>

<div class="container">
    <!-- Filter -->
    <div class="filter-box">
        <form method="get" action="">
            <div class="filter-title">🔍 Suche &amp; Filter</div>

            <div style="display:grid; grid-template-columns:1fr auto; gap:1rem; align-items:end;">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Suchbegriff eingeben</label>
                    <input type="text" name="search" class="form-control" placeholder="Suchbegriff eingeben..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <button type="submit" class="btn btn-primary" style="height:42px;">Suchen</button>
            </div>

            <div class="filter-checkboxes">
                <label><input type="checkbox" name="in[]" value="titel"    <?= in_array('titel',    $searchIn)?'checked':'' ?>> Nach Titel suchen</label>
                <label><input type="checkbox" name="in[]" value="autor"    <?= in_array('autor',    $searchIn)?'checked':'' ?>> Nach Autor suchen</label>
                <label><input type="checkbox" name="in[]" value="katalog"  <?= in_array('katalog',  $searchIn)?'checked':'' ?>> Nach Katalognummer suchen</label>
                <label><input type="checkbox" name="in[]" value="kategorie"<?= in_array('kategorie',$searchIn)?'checked':'' ?>> Nach Kategorie suchen</label>
            </div>

            <div class="filter-extra">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Kategorie</label>
                    <select name="kategorie" class="form-control">
                        <option value="0">Alle Kategorien</option>
                        <?php foreach ($kats as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= $filterKat === (int)$k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['kategorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Zustand</label>
                    <select name="zustand" class="form-control">
                        <option value="">Alle Zustände</option>
                        <option value="S" <?= $filterZustand==='S'?'selected':'' ?>>Sehr gut</option>
                        <option value="G" <?= $filterZustand==='G'?'selected':'' ?>>Gut</option>
                        <option value="M" <?= $filterZustand==='M'?'selected':'' ?>>Mittel</option>
                        <option value="S-" <?= $filterZustand==='S-'?'selected':'' ?>>Schlecht</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Sortieren nach</label>
                    <select name="sort" class="form-control">
                        <option value="titel"   <?= $sortBy==='titel'  ?'selected':'' ?>>Titel</option>
                        <option value="autor"   <?= $sortBy==='autor'  ?'selected':'' ?>>Autor</option>
                        <option value="katalog" <?= $sortBy==='katalog'?'selected':'' ?>>Katalognummer</option>
                    </select>
                </div>
                <a href="books.php" class="btn btn-outline" style="height:42px; align-self:end;">Filter zurücksetzen</a>
            </div>
        </form>
    </div>

    <!-- Ergebnis-Info -->
    <p style="color:var(--text-muted); margin-bottom:1rem; font-size:0.9rem;">
        <?= $total ?> Bücher gefunden
        <?= $totalPages > 1 ? "– Seite $page von $totalPages" : '' ?>
    </p>

    <!-- Bücher-Grid -->
    <?php if (empty($books)): ?>
        <div class="no-results"><p>Keine Bücher gefunden.</p></div>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <div class="book-cover">
                        <div class="book-cover-icon">📖</div>
                        <div class="book-cover-label">Foto Buch</div>
                        <?php if ($book['verkauft']): ?>
                            <span class="badge badge-sold book-badge">Verkauft</span>
                        <?php endif; ?>
                    </div>
                    <div class="book-info">
                        <div class="book-title"><?= htmlspecialchars($book['titel'] ?? '–') ?></div>
                        <div class="book-autor"><?= htmlspecialchars($book['autor'] ?? '–') ?></div>
                        <div class="book-kategorie"><?= htmlspecialchars($book['kategorie'] ?? '–') ?></div>
                        <div class="book-meta">Zustand: <?= htmlspecialchars($book['zustand'] ?? '–') ?> &bull; <?= $book['katalog'] ?>-<?= $book['nummer'] ?></div>
                        <a href="<?= BASE_URL ?>/book.php?id=<?= $book['id'] ?>" class="btn btn-outline btn-sm">Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= pageUrl($page - 1) ?>">&laquo;</a>
                <?php endif; ?>
                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <?php if ($i === $page): ?>
                        <span class="current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= pageUrl($i) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="<?= pageUrl($page + 1) ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
