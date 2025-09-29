<?php
require_once __DIR__ . '/../includes/auth.php';
auth_logout();
header('Location: /pass/admin/login.php');
exit;


