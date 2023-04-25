<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = getenv('JWT_SECRET');

if (!isset($_COOKIE['auth'])) {
	header('Location: index.php');
	exit;
} else {
	$cookie = $_COOKIE['auth'];
	try {
		$decoded = JWT::decode($cookie, new Key($key, 'HS256'));
	} catch (\Exception $e) {
		header('Location: index.php');
		exit;
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Super (In)Secure Blogger</title>
		<link href="css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Super (In)Secure Blogger</h1>
                <a href="home.php"><i class="fas fa-home"></i>Home</a>
				<a href="profile.php"><i class="fas fa-user-edit"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2><?php echo $_POST['title']; ?></h2>
			<?php
				ob_start();
				include "/var/www/html/blog_files/".urldecode($_POST['file']);
				$output = ob_get_clean();
				echo "<p>";
				echo $output;
				echo"</p";
			?>
		</div>
	</body>
</html>
