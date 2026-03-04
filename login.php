<?php
$pageTitle = 'Admin Login – Antiquariat Kassius';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $db   = getDb();
        $stmt = $db->prepare('SELECT * FROM benutzer WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['passwort'])) {
            $_SESSION['user'] = [
                'id'    => $user['ID'],
                'name'  => $user['vorname'] . ' ' . $user['name'],
                'email' => $user['email'],
                'admin' => (bool)$user['admin'],
            ];
            header('Location: ' . BASE_URL . '/admin/index.php');
            exit;
        } else {
            $error = 'Ungültige E-Mail-Adresse oder falsches Passwort.';
        }
    } else {
        $error = 'Bitte E-Mail und Passwort eingeben.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="login-page">
    <div class="login-card">
        <h1>Administrator Login</h1>
        <p>Melden Sie sich an, um auf die Verwaltungsfunktionen zuzugreifen</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="email">E-Mail-Adresse</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="admin@beispiel.de"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Passwort</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Anmelden</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
