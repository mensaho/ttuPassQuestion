<?php
return [
	'db' => [
		'host' => '127.0.0.1',
		'port' => 3306,
		'name' => 'ttu_cs_portal',
		'user' => 'root',
		'pass' => '',
		'charset' => 'utf8mb4',
	],
	'uploads_dir' => __DIR__ . '/../uploads',
	'max_upload_bytes' => 50 * 1024 * 1024,
	'allowed_extensions' => ['pdf','doc','docx','ppt','pptx','xls','xlsx'],
	'site_name' => 'Takoradi Technical University - Computer Science',
	'categories' => ['Diploma','BTech','HND'],
];
