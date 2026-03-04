<?php
require_once __DIR__ . '/auth.php';
$currentPath = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Antiquariat Kassius') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/index.php" class="navbar-brand">Antiquariat Kassius</a>
        <ul class="navbar-links">
            <li><a href="<?= BASE_URL ?>/index.php" <?= str_contains($currentPath, 'index.php') || $currentPath === BASE_URL . '/' ? 'class="active"' : '' ?>>Startseite</a></li>
            <li><a href="<?= BASE_URL ?>/books.php" <?= str_contains($currentPath, 'books.php') ? 'class="active"' : '' ?>>Bücher</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="<?= BASE_URL ?>/admin/index.php" <?= str_contains($currentPath, '/admin/') ? 'class="active"' : '' ?>>Admin</a></li>
                <li><a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-nav">Abmelden</a></li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-nav">Admin Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<main>
