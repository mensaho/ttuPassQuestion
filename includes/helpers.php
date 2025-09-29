<?php
require_once __DIR__ . '/db.php';

function h($string) {
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function get_allowed_extensions() {
	$config = get_config();
	return $config['allowed_extensions'];
}

function ensure_uploads_dir() {
	$config = get_config();
	$dir = $config['uploads_dir'];
	if (!is_dir($dir)) {
		mkdir($dir, 0775, true);
	}
	return $dir;
}

function format_filesize($bytes) {
	$units = ['B','KB','MB','GB'];
	$pow = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
	$pow = min($pow, count($units) - 1);
	$bytes /= (1 << (10 * $pow));
	return round($bytes, 2) . ' ' . $units[$pow];
}

function get_categories() {
	return get_config()['categories'];
}

function get_years_options() {
	return [1,2,3,4];
}

function get_semesters_options() {
	return [1,2];
}
