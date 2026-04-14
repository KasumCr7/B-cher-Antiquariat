<?php
$pageTitle = 'Kundenverwaltung – Admin';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
requireLogin();

<<<<<<< HEAD
$db = getDb();
$action = $_GET['action'] ?? 'list';
$custId = (int) ($_GET['id'] ?? 0);
=======
$db     = getDb();
$action = $_GET['action'] ?? 'list';
$custId = (int)($_GET['id'] ?? 0);
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
$success = '';

// ── POST: Speichern ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
<<<<<<< HEAD
    $id = (int) ($_POST['id'] ?? 0);
    $vorname = trim($_POST['vorname']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $geburtstag = $_POST['geburtstag'] ?? null;
    $kundeSeit = $_POST['kunde_seit'] ?? date('Y-m-d');
    $geschlecht = $_POST['geschlecht'] ?? 'M';
=======
    $id             = (int)($_POST['id'] ?? 0);
    $vorname        = trim($_POST['vorname']);
    $name           = trim($_POST['name']);
    $email          = trim($_POST['email']);
    $geburtstag     = $_POST['geburtstag'] ?? null;
    $kundeSeit      = $_POST['kunde_seit'] ?? date('Y-m-d');
    $geschlecht     = $_POST['geschlecht'] ?? 'M';
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
    $kontaktpermail = isset($_POST['kontaktpermail']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $db->prepare('UPDATE kunden SET vorname=?, name=?, email=?, geburtstag=?, kunde_seit=?, geschlecht=?, kontaktpermail=? WHERE kid=?');
        $stmt->execute([$vorname, $name, $email, $geburtstag, $kundeSeit, $geschlecht, $kontaktpermail, $id]);
        $success = 'Kunde erfolgreich aktualisiert.';
    } else {
        $stmt = $db->prepare('INSERT INTO kunden (vorname, name, email, geburtstag, kunde_seit, geschlecht, kontaktpermail) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$vorname, $name, $email, $geburtstag, $kundeSeit, $geschlecht, $kontaktpermail]);
        $success = 'Kunde erfolgreich erstellt.';
    }
    header('Location: customers.php?success=' . urlencode($success));
    exit;
}

// ── POST: Löschen ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
<<<<<<< HEAD
    $id = (int) $_POST['id'];
=======
    $id = (int)$_POST['id'];
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
    $db->prepare('DELETE FROM kunden WHERE kid = ?')->execute([$id]);
    header('Location: customers.php?success=' . urlencode('Kunde gelöscht.'));
    exit;
}

<<<<<<< HEAD
if (isset($_GET['success']))
    $success = $_GET['success'];
=======
if (isset($_GET['success'])) $success = $_GET['success'];
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f

// ── Kunde laden (bearbeiten) ──────────────────────────────────────────────────
$editCust = null;
if ($action === 'edit' && $custId > 0) {
    $stmt = $db->prepare('SELECT * FROM kunden WHERE kid = ?');
    $stmt->execute([$custId]);
    $editCust = $stmt->fetch();
}

// ── Kundenliste ───────────────────────────────────────────────────────────────
$search = trim($_GET['search'] ?? '');
<<<<<<< HEAD
$where = ['1=1'];
$params = [];
if ($search) {
    $where[] = '(vorname LIKE ? OR name LIKE ? OR email LIKE ?)';
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}
$whereSQL = implode(' AND ', $where);

$perPage = 20;
$page = max(1, (int) ($_GET['page'] ?? 1));

$countStmt = $db->prepare("SELECT COUNT(*) FROM kunden WHERE $whereSQL");
$countStmt->execute($params);
$totalCustomers = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalCustomers / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $db->prepare("SELECT * FROM kunden WHERE $whereSQL ORDER BY name, vorname LIMIT $perPage OFFSET $offset");
=======
$where  = ['1=1'];
$params = [];
if ($search) { $where[] = '(vorname LIKE ? OR name LIKE ? OR email LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
$whereSQL = implode(' AND ', $where);

$stmt = $db->prepare("SELECT * FROM kunden WHERE $whereSQL ORDER BY name, vorname LIMIT 500");
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
$stmt->execute($params);
$customers = $stmt->fetchAll();

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="admin-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="admin-content">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($action === 'add' || $action === 'edit'): ?>
            <!-- ── Formular ── -->
            <div class="admin-header">
                <div>
                    <h1><?= $action === 'edit' ? 'Kunde bearbeiten' : 'Neuen Kunden hinzufügen' ?></h1>
                </div>
                <a href="customers.php" class="btn btn-outline">Abbrechen</a>
            </div>

            <div class="card card-body">
                <form method="post" action="customers.php">
                    <input type="hidden" name="id" value="<?= $editCust['kid'] ?? 0 ?>">

                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label>Vorname *</label>
<<<<<<< HEAD
                            <input type="text" name="vorname" class="form-control"
                                value="<?= htmlspecialchars($editCust['vorname'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nachname *</label>
                            <input type="text" name="name" class="form-control"
                                value="<?= htmlspecialchars($editCust['name'] ?? '') ?>" required>
=======
                            <input type="text" name="vorname" class="form-control" value="<?= htmlspecialchars($editCust['vorname'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nachname *</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editCust['name'] ?? '') ?>" required>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                        </div>
                    </div>

                    <div class="form-group">
                        <label>E-Mail *</label>
<<<<<<< HEAD
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($editCust['email'] ?? '') ?>" required>
=======
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editCust['email'] ?? '') ?>" required>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                    </div>

                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label>Geburtstag</label>
<<<<<<< HEAD
                            <input type="date" name="geburtstag" class="form-control"
                                value="<?= htmlspecialchars($editCust['geburtstag'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Kunde seit *</label>
                            <input type="date" name="kunde_seit" class="form-control"
                                value="<?= htmlspecialchars($editCust['kunde_seit'] ?? date('Y-m-d')) ?>" required>
=======
                            <input type="date" name="geburtstag" class="form-control" value="<?= htmlspecialchars($editCust['geburtstag'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Kunde seit *</label>
                            <input type="date" name="kunde_seit" class="form-control" value="<?= htmlspecialchars($editCust['kunde_seit'] ?? date('Y-m-d')) ?>" required>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                        </div>
                        <div class="form-group">
                            <label>Geschlecht</label>
                            <select name="geschlecht" class="form-control">
<<<<<<< HEAD
                                <option value="M" <?= ($editCust['geschlecht'] ?? '') === 'M' ? 'selected' : '' ?>>Männlich
                                </option>
                                <option value="F" <?= ($editCust['geschlecht'] ?? '') === 'F' ? 'selected' : '' ?>>Weiblich
                                </option>
=======
                                <option value="M" <?= ($editCust['geschlecht'] ?? '') === 'M' ? 'selected' : '' ?>>Männlich</option>
                                <option value="F" <?= ($editCust['geschlecht'] ?? '') === 'F' ? 'selected' : '' ?>>Weiblich</option>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
<<<<<<< HEAD
                            <input type="checkbox" id="kontaktpermail" name="kontaktpermail" value="1"
                                <?= !empty($editCust['kontaktpermail']) ? 'checked' : '' ?>>
=======
                            <input type="checkbox" id="kontaktpermail" name="kontaktpermail" value="1" <?= !empty($editCust['kontaktpermail']) ? 'checked' : '' ?>>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                            <label for="kontaktpermail">Kontakt per Mail erlaubt</label>
                        </div>
                    </div>

                    <div style="display:flex;gap:1rem;">
<<<<<<< HEAD
                        <button type="submit" name="save"
                            class="btn btn-primary"><?= $action === 'edit' ? 'Aktualisieren' : 'Erstellen' ?></button>
=======
                        <button type="submit" name="save" class="btn btn-primary"><?= $action === 'edit' ? 'Aktualisieren' : 'Erstellen' ?></button>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                        <a href="customers.php" class="btn btn-outline">Abbrechen</a>
                    </div>
                </form>
            </div>

        <?php else: ?>
            <!-- ── Kundenliste ── -->
            <div class="admin-header">
                <div>
                    <h1>Kundenverwaltung</h1>
<<<<<<< HEAD
                    <p>Alle registrierten Kunden (<?= $totalCustomers ?>)</p>
=======
                    <p>Alle registrierten Kunden (<?= count($customers) ?>)</p>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                </div>
                <a href="customers.php?action=add" class="btn btn-primary">+ Neuer Kunde</a>
            </div>

            <!-- Suche -->
            <form method="get" action="customers.php" class="admin-filter">
<<<<<<< HEAD
                <input type="text" name="search" class="form-control" placeholder="Nach Name, Vorname oder E-Mail suchen..."
                    value="<?= htmlspecialchars($search) ?>">
=======
                <input type="text" name="search" class="form-control" placeholder="Nach Name, Vorname oder E-Mail suchen..." value="<?= htmlspecialchars($search) ?>">
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                <button type="submit" class="btn btn-primary">Suchen</button>
                <a href="customers.php" class="btn btn-outline">Zurücksetzen</a>
            </form>

            <!-- Tabelle -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>E-Mail</th>
                            <th>Kunde seit</th>
                            <th>Kontakt per Mail</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
<<<<<<< HEAD
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding:2rem;">Keine Kunden gefunden</td>
                            </tr>
=======
                            <tr><td colspan="7" class="text-center text-muted" style="padding:2rem;">Keine Kunden gefunden</td></tr>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                        <?php else: ?>
                            <?php foreach ($customers as $c): ?>
                                <tr>
                                    <td class="font-mono"><?= $c['kid'] ?></td>
                                    <td><?= htmlspecialchars($c['vorname']) ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($c['name']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($c['email']) ?></td>
                                    <td><?= date('d.m.Y', strtotime($c['kunde_seit'])) ?></td>
                                    <td>
                                        <?php if ($c['kontaktpermail']): ?>
                                            <span class="text-green fw-bold">Ja</span>
                                        <?php else: ?>
                                            <span class="text-muted">Nein</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="td-actions">
<<<<<<< HEAD
                                            <a href="customers.php?action=edit&id=<?= $c['kid'] ?>" class="btn btn-ghost btn-sm"
                                                title="Bearbeiten">✏️</a>
                                            <form method="post" action="customers.php"
                                                onsubmit="return confirm('Kunden &quot;<?= htmlspecialchars(addslashes($c['vorname'] . ' ' . $c['name'])) ?>&quot; wirklich löschen?');"
                                                style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $c['kid'] ?>">
                                                <button type="submit" name="delete" class="btn btn-ghost btn-sm"
                                                    title="Löschen">🗑️</button>
=======
                                            <a href="customers.php?action=edit&id=<?= $c['kid'] ?>" class="btn btn-ghost btn-sm" title="Bearbeiten">✏️</a>
                                            <form method="post" action="customers.php" onsubmit="return confirm('Kunden &quot;<?= htmlspecialchars(addslashes($c['vorname'] . ' ' . $c['name'])) ?>&quot; wirklich löschen?');" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $c['kid'] ?>">
                                                <button type="submit" name="delete" class="btn btn-ghost btn-sm" title="Löschen">🗑️</button>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
<<<<<<< HEAD

            <?php if ($totalPages > 1): ?>
                <?php
                $qBase = $search ? 'search=' . urlencode($search) . '&' : '';
                ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="customers.php?<?= $qBase ?>page=<?= $page - 1 ?>" class="btn btn-outline btn-sm">&laquo; Zurück</a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-sm disabled">&laquo; Zurück</span>
                    <?php endif; ?>

                    <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
                        <a href="customers.php?<?= $qBase ?>page=<?= $p ?>"
                            class="btn btn-sm <?= $p === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $p ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="customers.php?<?= $qBase ?>page=<?= $page + 1 ?>" class="btn btn-outline btn-sm">Weiter &raquo;</a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-sm disabled">Weiter &raquo;</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

=======
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
        <?php endif; ?>
    </div>
</div>

<<<<<<< HEAD
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
=======
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
>>>>>>> 1b7f64d0a90107df640450038e1321cead41e04f
