<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$info = isset($_GET['info']) ? $_GET['info'] : '';
$err = isset($_GET['err']) ? $_GET['err'] : '';

$files = db_select_all('SELECT id, title, original_name, extension, size_bytes, category, year, semester, uploaded_at FROM files ORDER BY uploaded_at DESC LIMIT 200');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Dashboard — TTU CS</title>
	<link rel="stylesheet" href="/pass/assets/styles.css">
</head>
<body>
	<header>
		<div class="nav">
			<div class="brand">
				<div class="logo"></div>
				<h1>Admin Dashboard</h1>
			</div>
			<div>
				<a class="btn secondary" href="/pass/index.php">Home</a>
				<a class="btn" href="/pass/admin/logout.php">Logout</a>
			</div>
		</div>
	</header>
	<div class="container">
		<div class="grid cols-2">
			<div class="card">
				<div class="section-title">Upload Resource</div>
				<?php if ($info): ?><div style="color:#86efac;margin-bottom:8px"><?php echo h($info); ?></div><?php endif; ?>
				<?php if ($err): ?><div style="color:#fda4af;margin-bottom:8px"><?php echo h($err); ?></div><?php endif; ?>
				<form method="post" action="/pass/admin/upload.php" enctype="multipart/form-data" style="display:grid;gap:12px">
					<input class="input" type="text" name="title" placeholder="Optional title">
					<select name="category" required>
						<option value="">Select Program</option>
						<?php foreach (get_categories() as $cat): ?>
							<option value="<?php echo h($cat); ?>"><?php echo h($cat); ?></option>
						<?php endforeach; ?>
					</select>
					<select name="year" required>
						<option value="">Year</option>
						<?php foreach (get_years_options() as $y): ?>
							<option value="<?php echo $y; ?>">Year <?php echo $y; ?></option>
						<?php endforeach; ?>
					</select>
					<select name="semester" required>
						<option value="">Semester</option>
						<?php foreach (get_semesters_options() as $s): ?>
							<option value="<?php echo $s; ?>">Semester <?php echo $s; ?></option>
						<?php endforeach; ?>
					</select>
					<input type="file" name="file" required>
					<label style="color:#94a3b8">Accepted: pdf, doc, docx, ppt, pptx, xls, xlsx</label>
					<button class="btn" type="submit">Upload</button>
				</form>
			</div>
			<div class="card">
				<div class="section-title">Recent Uploads</div>
				<table class="table">
					<thead><tr><th>Title</th><th>Program</th><th>Year</th><th>Sem</th><th>Size</th><th>When</th><th></th></tr></thead>
					<tbody>
						<?php foreach ($files as $f): ?>
							<tr>
								<td><?php echo h($f['title'] ?: $f['original_name']); ?></td>
								<td><?php echo h($f['category']); ?></td>
								<td><?php echo (int)$f['year']; ?></td>
								<td><?php echo (int)$f['semester']; ?></td>
								<td><?php echo h(format_filesize((int)$f['size_bytes'])); ?></td>
								<td><?php echo h(date('Y-m-d', strtotime($f['uploaded_at']))); ?></td>
								<td style="display:flex;gap:8px">
									<a class="btn secondary" href="/pass/download.php?id=<?php echo (int)$f['id']; ?>">Download</a>
									<a class="btn" style="background:linear-gradient(180deg,#ef4444,#dc2626);color:white" href="/pass/admin/delete.php?id=<?php echo (int)$f['id']; ?>" onclick="return confirm('Delete this file?');">Delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
						<?php if (!$files): ?><tr><td colspan="7" style="color:#94a3b8">No files yet.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>



