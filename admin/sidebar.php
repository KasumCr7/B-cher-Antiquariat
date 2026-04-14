<?php
$currentPath = $_SERVER['REQUEST_URI'];
?>
<aside class="sidebar">
    <div class="sidebar-title">Navigation</div>
    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/admin/index.php" <?= str_ends_with($currentPath, 'admin/index.php') ? 'class="active"' : '' ?>>
            <span class="sidebar-icon"></span> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/admin/books.php" <?= str_contains($currentPath, 'admin/books.php') ? 'class="active"' : '' ?>>
            <span class="sidebar-icon"></span> Bücher verwalten
        </a>
        <a href="<?= BASE_URL ?>/admin/customers.php" <?= str_contains($currentPath, 'admin/customers.php') ? 'class="active"' : '' ?>>
            <span class="sidebar-icon"></span> Kundenverwaltung
        </a>
        <a href="<?= BASE_URL ?>/index.php">
            <span class="sidebar-icon"></span> Zur Website
        </a>
        <a href="<?= BASE_URL ?>/admin/change-password.php" <?= str_contains($currentPath, 'change-password.php') ? 'class="active"' : '' ?>>
            <span class="sidebar-icon"></span> Passwort ändern
        </a>
        <a href="<?= BASE_URL ?>/logout.php" style="margin-top:auto;">
            <span class="sidebar-icon"></span> Abmelden
        </a>
    </nav>
</aside>