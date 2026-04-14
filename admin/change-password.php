<?php
$pageTitle = 'Passwort ändern – Admin';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
requireLogin();

$db = getDb();
$user = currentUser();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_password'] ?? '';
    $new      = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $stmt = $db->prepare('SELECT passwort FROM benutzer WHERE ID = ?');
    $stmt->execute([$user['id']]);
    $row = $stmt->fetch();

    if (!$row || !password_verify($current, $row['passwort'])) {
        $error = 'Das aktuelle Passwort ist falsch.';
    } elseif (strlen($new) < 8) {
        $error = 'Das neue Passwort muss mindestens 8 Zeichen lang sein.';
    } elseif ($new !== $confirm) {
        $error = 'Die neuen Passwörter stimmen nicht überein.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $db->prepare('UPDATE benutzer SET passwort = ? WHERE ID = ?')->execute([$hash, $user['id']]);
        $success = 'Passwort erfolgreich geändert.';
    }
}

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="admin-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1>Passwort ändern</h1>
                <p>Angemeldet als <?= htmlspecialchars($user['name']) ?></p>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="card card-body" style="max-width:480px;">
            <form method="post" action="change-password.php">
                <div class="form-group">
                    <label>Aktuelles Passwort</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Neues Passwort</label>
                    <input type="password" name="new_password" class="form-control" required minlength="8">
                </div>
                <div class="form-group">
                    <label>Neues Passwort bestätigen</label>
                    <input type="password" name="confirm_password" class="form-control" required minlength="8">
                </div>
                <button type="submit" class="btn btn-primary">Passwort ändern</button>
            </form>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
