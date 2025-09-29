<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';
$config = get_config();
ensure_uploads_dir();

$category = isset($_GET['category']) ? $_GET['category'] : '';

$params = [];
$types = '';
$where = ['is_public = 1'];
if ($category && in_array($category, get_categories(), true)) { $where[] = 'category = ?'; $params[] = $category; $types .= 's'; }
$sql = 'SELECT id, title, original_name, extension, size_bytes, category, uploaded_at FROM files ' . (count($where)?('WHERE '.implode(' AND ',$where)):'') . ' ORDER BY uploaded_at DESC LIMIT 200';
$files = db_select_all($sql, $types, $params);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo h($config['site_name']); ?></title>
	<link rel="stylesheet" href="/pass/assets/styles.css">
	<style>
		.category-card{position:relative;overflow:hidden;border-radius:16px;padding:20px;background:linear-gradient(135deg,rgba(96,165,250,.15),rgba(34,197,94,.15));border:1px solid rgba(255,255,255,.08)}
		.category-card h3{margin:0 0 6px}
		.category-card p{margin:0;color:#cbd5e1}
		.category-card .glow{position:absolute;right:-40px;top:-40px;width:160px;height:160px;border-radius:50%;background:radial-gradient(circle at center, rgba(96,165,250,.35), transparent 60%)}
	</style>
</head>
<body>
	<header>
		<div class="nav">
			<div class="brand">
				<div class="logo"></div>
				<h1>Takoradi Technical University — Computer Science</h1>
			</div>
			<div>
				<?php if (is_admin()): ?>
					<a class="btn" href="/pass/admin/dashboard.php">Admin Dashboard</a>
				<?php else: ?>
					<a class="btn secondary" href="/pass/admin/login.php">Admin Login</a>
				<?php endif; ?>
			</div>
		</div>
	</header>
	<div class="container">
		<section class="hero">
			<div class="card">
				<span class="badge">Department of Computer Science</span>
				<h2 style="margin:8px 0 6px">Welcome to TTU CS Portal</h2>
				<p style="color:#cbd5e1">Browse study resources by program: Diploma, BTech, HND.</p>
			</div>
			<div class="card">
				<div class="section-title">Filter by Program</div>
				<form method="get" style="display:grid;grid-template-columns:1fr auto;gap:12px">
					<select name="category">
						<option value="">All Programs</option>
						<?php foreach (get_categories() as $cat): ?>
							<option value="<?php echo h($cat); ?>" <?php echo $category===$cat?'selected':''; ?>><?php echo h($cat); ?></option>
						<?php endforeach; ?>
					</select>
					<button class="btn" type="submit">Apply</button>
				</form>
			</div>
		</section>

		<section class="card">
			<div class="section-title">Programs</div>
			<div class="grid cols-4" style="grid-template-columns:repeat(4,1fr);gap:16px">
				<?php foreach (get_categories() as $prog): ?>
					<a class="category-card" href="?category=<?php echo urlencode($prog); ?>">
						<div class="glow"></div>
						<h3><?php echo h($prog); ?></h3>
						<p>View latest materials for <?php echo h($prog); ?>.</p>
					</a>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="card">
			<div class="section-title">Latest Resources</div>
			<table class="table">
				<thead>
					<tr><th>Title</th><th>Program</th><th>Size</th><th>Uploaded</th><th></th></tr>
				</thead>
				<tbody>
					<?php foreach ($files as $f): ?>
						<tr>
							<td><?php echo h($f['title'] ?: $f['original_name']); ?></td>
							<td><?php echo h($f['category']); ?></td>
							<td><?php echo h(format_filesize((int)$f['size_bytes'])); ?></td>
							<td><?php echo h(date('Y-m-d', strtotime($f['uploaded_at']))); ?></td>
							<td><a class="btn" href="/pass/download.php?id=<?php echo (int)$f['id']; ?>">Download</a></td>
						</tr>
					<?php endforeach; ?>
					<?php if (!$files): ?>
						<tr><td colspan="5" style="color:#94a3b8">No files yet.</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</section>
	</div>
	<footer>
		&copy; <?php echo date('Y'); ?> Takoradi Technical University — Department of Computer Science
	</footer>
</body>
</html>



