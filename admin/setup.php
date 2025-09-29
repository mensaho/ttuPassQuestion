<?php
require_once __DIR__ . '/../includes/db.php';
$conn = db();
$sql = file_get_contents(__DIR__ . '/../database.sql');
if ($sql !== false) {
	if (!mysqli_multi_query($conn, $sql)) {
		die('Schema error: ' . mysqli_error($conn));
	}
	// flush remaining results
	do { if ($res = mysqli_store_result($conn)) { mysqli_free_result($res); } } while (mysqli_more_results($conn) && mysqli_next_result($conn));
}

$username = 'admin';
$password = 'admin123'; // change after first login
$hash = password_hash($password, PASSWORD_DEFAULT);

$row = db_select_one('SELECT id FROM users WHERE username = ?','s',[$username]);
if (!$row) {
	list($ok) = db_execute('INSERT INTO users (username, password_hash, role) VALUES (?, ?, "admin")','ss',[$username,$hash]);
	echo 'Admin user created. Username: admin, Password: admin123';
} else {
	echo 'Admin user already exists.';
}
