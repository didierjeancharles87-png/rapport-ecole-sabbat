<?php
declare(strict_types=1);
require __DIR__ . '/backend/helpers/core.php';
start_session();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && hash_equals(csrf_token(), (string)($_POST['_csrf'] ?? ''))) {
    if (current_user()) log_action('logout', 'Déconnexion');
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => $p['path'], 'httponly' => true, 'samesite' => 'Lax']);
    }
    session_destroy();
}
redirect(BASE_URL . 'login.php');
