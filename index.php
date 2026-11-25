<?php
declare(strict_types=1);
require __DIR__ . '/backend/helpers/core.php';
if (!is_installed()) { redirect(BASE_URL . 'database/install.php'); }
boot_app();

$pages = ['dashboard', 'classes', 'ecole', 'mission', 'interesses', 'stats', 'rapports', 'exports', 'sauvegardes', 'utilisateurs', 'historique', 'profil'];
$page = (string)($_GET['page'] ?? 'dashboard');
if (!in_array($page, $pages, true)) { $page = 'dashboard'; }

// Changement de mot de passe obligatoire à la première connexion
if ((int)current_user()['doit_changer_mdp'] === 1 && $page !== 'profil') {
    flash('warning', 'Pour votre sécurité, veuillez changer votre mot de passe avant de continuer.');
    redirect(url('profil'));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check(); }
require __DIR__ . '/frontend/pages/' . $page . '.php';
