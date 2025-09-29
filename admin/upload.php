<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$config = get_config();

function redirect_with($key, $msg) {
	header('Location: /pass/admin/dashboard.php?' . $key . '=' . urlencode($msg));
	exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
	redirect_with('err', 'No file uploaded or error occurred');
}

$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$year = isset($_POST['year']) ? (int)$_POST['year'] : 0;
$semester = isset($_POST['semester']) ? (int)$_POST['semester'] : 0;

if (!in_array($category, get_categories(), true)) redirect_with('err', 'Invalid category');
if (!in_array($year, get_years_options(), true)) redirect_with('err', 'Invalid year');
if (!in_array($semester, get_semesters_options(), true)) redirect_with('err', 'Invalid semester');

$file = $_FILES['file'];
$original = $file['name'];
$size = (int)$file['size'];
if ($size <= 0 || $size > $config['max_upload_bytes']) redirect_with('err','File too large');

$ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
if (!in_array($ext, get_allowed_extensions(), true)) redirect_with('err','Unsupported file type');

$mime = mime_content_type($file['tmp_name']);
$fileData = file_get_contents($file['tmp_name']);

$user = auth_user();

$sql = 'INSERT INTO files (title, original_name, extension, mime_type, size_bytes, category, year, semester, is_public, uploaded_by, file_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)';
$params = [
	$title !== '' ? $title : null,
	$original,
	$ext,
	$mime ?: 'application/octet-stream',
	$size,
	$category,
	$year,
	$semester,
	$user ? $user['id'] : null,
	$fileData,
];
// types: s s s s i s i i i b
$types = 'ssssisiiib';

list($ok) = db_execute_with_long_data($sql, $types, $params, [9 => $fileData]);

redirect_with('info','Upload successful: ' . $original); 
