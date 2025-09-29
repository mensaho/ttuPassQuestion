<?php
function get_config() {
	static $config = null;
	if ($config === null) {
		$config = require __DIR__ . '/config.php';
	}
	return $config;
}

function db() {
	static $conn = null;
	if ($conn instanceof mysqli) return $conn;
	$c = get_config()['db'];
	$conn = mysqli_connect($c['host'], $c['user'], $c['pass'], $c['name'], $c['port']);
	if (!$conn) {
		die('Database connection failed: ' . mysqli_connect_error());
	}
	mysqli_set_charset($conn, get_config()['db']['charset']);
	return $conn;
}

function db_select_all($sql, $types = '', $params = []) {
	$conn = db();
	if ($types === '' && empty($params)) {
		$res = mysqli_query($conn, $sql);
		if (!$res) die('Query error: ' . mysqli_error($conn));
		$out = [];
		while ($row = mysqli_fetch_assoc($res)) { $out[] = $row; }
		mysqli_free_result($res);
		return $out;
	}
	$stmt = mysqli_prepare($conn, $sql);
	if (!$stmt) die('Prepare failed: ' . mysqli_error($conn));
	if ($types !== '') {
		mysqli_stmt_bind_param($stmt, $types, ...$params);
	}
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	$out = [];
	if ($res) {
		while ($row = mysqli_fetch_assoc($res)) { $out[] = $row; }
		mysqli_free_result($res);
	}
	mysqli_stmt_close($stmt);
	return $out;
}

function db_select_one($sql, $types = '', $params = []) {
	$list = db_select_all($sql, $types, $params);
	return $list ? $list[0] : null;
}

function db_execute($sql, $types = '', $params = []) {
	$conn = db();
	$stmt = mysqli_prepare($conn, $sql);
	if (!$stmt) die('Prepare failed: ' . mysqli_error($conn));
	if ($types !== '') {
		mysqli_stmt_bind_param($stmt, $types, ...$params);
	}
	$ok = mysqli_stmt_execute($stmt);
	$insert_id = mysqli_insert_id($conn);
	$affected = mysqli_stmt_affected_rows($stmt);
	mysqli_stmt_close($stmt);
	return [$ok, $insert_id, $affected];
}

function db_execute_with_long_data($sql, $types, &$params, $longDataMap) {
	// $longDataMap: [paramIndexZeroBased => stringData]
	$conn = db();
	$stmt = mysqli_prepare($conn, $sql);
	if (!$stmt) die('Prepare failed: ' . mysqli_error($conn));
	mysqli_stmt_bind_param($stmt, $types, ...$params);
	foreach ($longDataMap as $idx => $data) {
		// send in chunks to avoid memory spikes
		$chunkSize = 1048576; // 1MB
		for ($offset = 0, $len = strlen($data); $offset < $len; $offset += $chunkSize) {
			mysqli_stmt_send_long_data($stmt, $idx, substr($data, $offset, $chunkSize));
		}
	}
	$ok = mysqli_stmt_execute($stmt);
	$insert_id = mysqli_insert_id($conn);
	$affected = mysqli_stmt_affected_rows($stmt);
	mysqli_stmt_close($stmt);
	return [$ok, $insert_id, $affected];
} 
