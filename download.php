<?php
require_once __DIR__ . '/includes/helpers.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(404); exit('Not found'); }
$row = db_select_one('SELECT original_name, mime_type, size_bytes, file_data FROM files WHERE id = ?', 'i', [$id]);
if (!$row) { http_response_code(404); exit('Not found'); }
header('Content-Description: File Transfer');
header('Content-Type: ' . $row['mime_type']);
header('Content-Disposition: attachment; filename="' . rawurlencode($row['original_name']) . '"');
header('Content-Length: ' . (int)$row['size_bytes']);
echo $row['file_data'];



