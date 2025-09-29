<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
	list($ok) = db_execute('DELETE FROM files WHERE id = ?', 'i', [$id]);
}
header('Location: /pass/admin/dashboard.php?info=' . urlencode('Deleted'));
exit;
