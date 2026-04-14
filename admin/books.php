<?php
$pageTitle = 'Bücher verwalten – Admin';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
requireLogin();

$db = getDb();
$action = $_GET['action'] ?? 'list';
$bookId = (int) ($_GET['id'] ?? 0);
$success = '';
$error = '';

// ── POST: Speichern (neu/bearbeiten) ─────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $katalog = (int) $_POST['katalog'];
    $nummer = (int) $_POST['nummer'];
    $titel = trim($_POST['titel']);
    $kategorie = (int) $_POST['kategorie'];
    $autor = trim($_POST['autor']);
    $beschreibung = trim($_POST['beschreibung']);
    $zustand = trim($_POST['zustand']);
    $verkauft = isset($_POST['verkauft']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $db->prepare('UPDATE buecher SET katalog=?, nummer=?, Title=?, kategorie=?, autor=?, Beschreibung=?, zustand=?, verkauft=? WHERE id=?');
        $stmt->execute([$katalog, $nummer, $titel, $kategorie, $autor, $beschreibung, $zustand, $verkauft, $id]);
        $success = 'Buch erfolgreich aktualisiert.';
    } else {
        $stmt = $db->prepare('INSERT INTO buecher (katalog, nummer, Title, kategorie, autor, Beschreibung, zustand, verkauft, foto) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([$katalog, $nummer, $titel, $kategorie, $autor, $beschreibung, $zustand, $verkauft, 'book.jpg']);
        $success = 'Buch erfolgreich erstellt.';
    }
    header('Location: books.php?success=' . urlencode($success));
    exit;
}

// ── POST: Löschen ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int) $_POST['id'];
    $db->prepare('DELETE FROM buecher WHERE id = ?')->execute([$id]);
    header('Location: books.php?success=' . urlencode('Buch gelöscht.'));
    exit;
}

if (isset($_GET['success']))
    $success = $_GET['success'];

// ── Kategorien ────────────────────────────────────────────────────────────────
$kats = $db->query('SELECT id, kategorie FROM kategorien ORDER BY id')->fetchAll();

// ── Buch laden (für Bearbeiten) ───────────────────────────────────────────────
$editBook = null;
if ($action === 'edit' && $bookId > 0) {
    $stmt = $db->prepare('SELECT * FROM buecher WHERE id = ?');
    $stmt->execute([$bookId]);
    $editBook = $stmt->fetch();
}

// ── Bücherliste ───────────────────────────────────────────────────────────────
$search = trim($_GET['search'] ?? '');
$filterKat = (int) ($_GET['kategorie'] ?? 0);
$where = ['1=1'];
$params = [];
if ($search) {
    $where[] = '(b.Title LIKE ? OR b.autor LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($filterKat) {
    $where[] = 'b.kategorie = ?';
    $params[] = $filterKat;
}
$whereSQL = implode(' AND ', $where);

$perPage = 20;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$countStmt = $db->prepare("SELECT COUNT(*) FROM buecher b WHERE $whereSQL");
$countStmt->execute($params);
$totalBooks = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalBooks / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $db->prepare("SELECT b.id, b.katalog, b.nummer, b.Title AS titel, b.autor, b.zustand, b.verkauft, k.kategorie FROM buecher b LEFT JOIN kategorien k ON k.id = b.kategorie WHERE $whereSQL ORDER BY b.Title LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$books = $stmt->fetchAll();

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="admin-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="admin-content">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($action === 'add' || $action === 'edit'): ?>
            <!-- ── Formular (Hinzufügen / Bearbeiten) ── -->
            <div class="admin-header">
                <div>
                    <h1><?= $action === 'edit' ? 'Buch bearbeiten' : 'Neues Buch hinzufügen' ?></h1>
                    <p><?= $action === 'edit' ? 'Daten des Buches anpassen' : 'Neues Buch zum Bestand hinzufügen' ?></p>
                </div>
                <a href="books.php" class="btn btn-outline">Abbrechen</a>
            </div>

            <div class="card card-body">
                <form method="post" action="books.php">
                    <input type="hidden" name="id" value="<?= $editBook['id'] ?? 0 ?>">

                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label>Katalognummer *</label>
                            <input type="number" name="katalog" class="form-control"
                                value="<?= htmlspecialchars($editBook['katalog'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nummer *</label>
                            <input type="number" name="nummer" class="form-control"
                                value="<?= htmlspecialchars($editBook['nummer'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Titel *</label>
                        <input type="text" name="titel" class="form-control"
                            value="<?= htmlspecialchars($editBook['Title'] ?? '') ?>" required>
                    </div>

                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label>Autor *</label>
                            <input type="text" name="autor" class="form-control"
                                value="<?= htmlspecialchars($editBook['autor'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Kategorie *</label>
                            <select name="kategorie" class="form-control" required>
                                <option value="">– Bitte wählen –</option>
                                <?php foreach ($kats as $k): ?>
                                    <option value="<?= $k['id'] ?>" <?= ($editBook['kategorie'] ?? '') == $k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['kategorie']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label>Zustand</label>
                            <select name="zustand" class="form-control">
                                <?php foreach (['S' => 'Sehr gut', 'G' => 'Gut', 'M' => 'Mittel', 'S-' => 'Schlecht'] as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= ($editBook['zustand'] ?? '') === $val ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="display:flex;align-items:flex-end;">
                            <div class="checkbox-group">
                                <input type="checkbox" id="verkauft" name="verkauft" value="1"
                                    <?= !empty($editBook['verkauft']) ? 'checked' : '' ?>>
                                <label for="verkauft">Verkauft</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Beschreibung</label>
                        <textarea name="beschreibung"
                            class="form-control"><?= htmlspecialchars($editBook['Beschreibung'] ?? '') ?></textarea>
                    </div>

                    <div style="display:flex;gap:1rem;">
                        <button type="submit" name="save"
                            class="btn btn-primary"><?= $action === 'edit' ? 'Aktualisieren' : 'Erstellen' ?></button>
                        <a href="books.php" class="btn btn-outline">Abbrechen</a>
                    </div>
                </form>
            </div>

        <?php else: ?>
            <!-- ── Bücherliste ── -->
            <div class="admin-header">
                <div>
                    <h1>Bücher verwalten</h1>
                    <p>Alle Bücher im Bestand (<?= $totalBooks ?>)</p>
                </div>
                <a href="books.php?action=add" class="btn btn-primary">+ Neues Buch</a>
            </div>

            <!-- Filter -->
            <form method="get" action="books.php" class="admin-filter">
                <input type="text" name="search" class="form-control" placeholder="Titel oder Autor suchen..."
                    value="<?= htmlspecialchars($search) ?>">
                <select name="kategorie" class="form-control">
                    <option value="0">Alle Kategorien</option>
                    <?php foreach ($kats as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $filterKat === (int) $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['kategorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Filtern</button>
                <a href="books.php" class="btn btn-outline">Zurücksetzen</a>
            </form>

            <!-- Tabelle -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titel</th>
                            <th>Autor</th>
                            <th>Kategorie</th>
                            <th>Zustand</th>
                            <th>Katalognr.</th>
                            <th>Verkauft</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($books)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding:2rem;">Keine Bücher gefunden</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($books as $b): ?>
                                <tr>
                                    <td class="font-mono"><?= $b['id'] ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($b['titel'] ?? '–') ?></td>
                                    <td><?= htmlspecialchars($b['autor'] ?? '–') ?></td>
                                    <td><?= htmlspecialchars($b['kategorie'] ?? '–') ?></td>
                                    <td><?= htmlspecialchars($b['zustand'] ?? '–') ?></td>
                                    <td class="font-mono"><?= $b['katalog'] ?>-<?= $b['nummer'] ?></td>
                                    <td>
                                        <?php if ($b['verkauft']): ?>
                                            <span class="text-accent fw-bold">Ja</span>
                                        <?php else: ?>
                                            <span class="text-green fw-bold">Nein</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="td-actions">
                                            <a href="books.php?action=edit&id=<?= $b['id'] ?>" class="btn btn-ghost btn-sm"
                                                title="Bearbeiten">✏️</a>
                                            <form method="post" action="books.php"
                                                onsubmit="return confirm('Buch &quot;<?= htmlspecialchars(addslashes($b['titel'] ?? '')) ?>&quot; wirklich löschen?');"
                                                style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                                <button type="submit" name="delete" class="btn btn-ghost btn-sm"
                                                    title="Löschen">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <?php
                $qBase = array_filter(['search' => $search, 'kategorie' => $filterKat ?: null]);
                $qBase = http_build_query(array_filter($qBase, fn($v) => $v !== null && $v !== ''));
                $qBase = $qBase ? $qBase . '&' : '';
                ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="books.php?<?= $qBase ?>page=<?= $page - 1 ?>" class="btn btn-outline btn-sm">&laquo; Zurück</a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-sm disabled">&laquo; Zurück</span>
                    <?php endif; ?>

                    <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
                        <a href="books.php?<?= $qBase ?>page=<?= $p ?>"
                            class="btn btn-sm <?= $p === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $p ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="books.php?<?= $qBase ?>page=<?= $page + 1 ?>" class="btn btn-outline btn-sm">Weiter &raquo;</a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-sm disabled">Weiter &raquo;</span>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>