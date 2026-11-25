<?php
declare(strict_types=1);
require __DIR__ . '/backend/helpers/core.php';
if (!is_installed()) { redirect(BASE_URL . 'database/install.php'); }
start_session();
if (current_user()) { redirect(BASE_URL . 'index.php'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = mb_substr(trim((string)($_POST['login'] ?? '')), 0, 50);
    $pwd = (string)($_POST['password'] ?? '');
    if (!hash_equals(csrf_token(), (string)($_POST['_csrf'] ?? ''))) {
        $error = 'Formulaire expiré, veuillez réessayer.';
    } else {
        $fails = (int)val("SELECT COUNT(*) FROM historique WHERE action = 'login.echec' AND (login = ? OR ip = ?) AND created_at > (NOW() - INTERVAL 15 MINUTE)", [$login, client_ip()]);
        if ($fails >= 6) {
            $error = 'Trop de tentatives. Réessayez dans 15 minutes.';
        } else {
            $user = row('SELECT * FROM users WHERE login = ?', [$login]);
            if ($user && (int)$user['actif'] === 1 && password_verify($pwd, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['uid'] = (int)$user['id'];
                $_SESSION['last'] = time();
                if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                    q('UPDATE users SET password_hash = ? WHERE id = ?', [password_hash($pwd, PASSWORD_DEFAULT), $user['id']]);
                }
                q('UPDATE users SET derniere_connexion = NOW() WHERE id = ?', [$user['id']]);
                log_action('login.ok', 'Connexion réussie');
                redirect(BASE_URL . 'index.php');
            }
            q('INSERT INTO historique (login, action, details, ip) VALUES (?,?,?,?)', [$login, 'login.echec', 'Identifiants incorrects', client_ip()]);
            $error = 'Identifiant ou mot de passe incorrect.';
        }
    }
}
?><!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Connexion - <?= e(APP_NAME) ?></title>
<link rel="icon" type="image/svg+xml" href="<?= asset('images/logo.svg') ?>">
<link rel="stylesheet" href="<?= asset('vendor/bootstrap/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= asset('vendor/bootstrap-icons/bootstrap-icons.min.css') ?>">
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
<div class="login-bg">
  <form class="login-card" method="post" autocomplete="off">
    <?= csrf_field() ?>
    <div class="text-center mb-4">
      <img src="<?= asset('images/logo.svg') ?>" width="64" height="64" alt="">
      <h1 class="mt-3 mb-1">École du Sabbat &amp; Asa Fitoriana</h1>
      <div class="text-secondary small">Connectez-vous pour accéder aux rapports</div>
    </div>
    <?php foreach (take_flashes() as $fl): ?><div class="alert alert-<?= $fl['t'] === 'success' ? 'success' : 'warning' ?> py-2 small"><?= e($fl['m']) ?></div><?php endforeach; ?>
    <?php if ($error): ?><div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-triangle me-1"></i><?= e($error) ?></div><?php endif; ?>
    <div class="mb-3"><label class="form-label">Identifiant</label>
      <input class="form-control form-control-lg" name="login" required autofocus maxlength="50" value="<?= e($_POST['login'] ?? '') ?>"></div>
    <div class="mb-4"><label class="form-label">Mot de passe</label>
      <input class="form-control form-control-lg" type="password" name="password" required></div>
    <button class="btn btn-primary btn-lg w-100"><i class="bi bi-box-arrow-in-right me-1"></i>Se connecter</button>
  </form>
</div>
</body>
</html>
