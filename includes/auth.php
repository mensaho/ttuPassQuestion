<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

function auth_login($username, $password) {
	$user = db_select_one('SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1', 's', [$username]);
	if ($user && password_verify($password, $user['password_hash'])) {
		$_SESSION['user'] = [
			'id' => (int)$user['id'],
			'username' => $user['username'],
			'role' => $user['role'],
		];
		return true;
	}
	return false;
}

function auth_logout() {
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}
	session_destroy();
}

function auth_user() {
	return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function require_admin() {
	$user = auth_user();
	if (!$user || strtolower($user['role']) !== 'admin') {
		header('Location: /pass/admin/login.php');
		exit;
	}
}

function is_admin() {
	$user = auth_user();
	return $user && strtolower($user['role']) === 'admin';
}


