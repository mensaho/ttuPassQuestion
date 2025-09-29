<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_admin()) {
	header('Location: /pass/admin/dashboard.php');
	exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = isset($_POST['username']) ? trim($_POST['username']) : '';
	$password = isset($_POST['password']) ? (string)$_POST['password'] : '';
	if ($username !== '' && $password !== '') {
		if (auth_login($username, $password)) {
			header('Location: /pass/admin/dashboard.php');
			exit;
		} else {
			$error = 'Invalid credentials';
		}
	} else {
		$error = 'Please enter username and password';
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Login — TTU CS</title>
	<link rel="stylesheet" href="/pass/assets/styles.css">
</head>
<body>
	<div class="container" style="max-width:420px">
		<div class="card">
			<h2 class="section-title">Administrator Login</h2>
			<?php if ($error): ?>
				<div style="color:#fca5a5;margin-bottom:10px"><?php echo h($error); ?></div>
			<?php endif; ?>
			<form method="post" style="display:grid;gap:12px">
				<input class="input" type="text" name="username" placeholder="Username" required>
				<input class="input" type="password" name="password" placeholder="Password" required>
				<button class="btn" type="submit">Login</button>
			</form>
			<div style="margin-top:10px"><a href="/pass/index.php">Back to Home</a></div>
		</div>
	</div>
</body>
</html>
