<?php

require __DIR__.'/../vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'mustangs';

if (!isset($_COOKIE['auth'])) {
	header('Location: ../index.php');
	exit;
} else {
	$cookie = $_COOKIE['auth'];
	try {
		$decoded = JWT::decode($cookie, new Key($key, 'HS256'));
		$decoded_array = (array) $decoded;
		$user = $decoded_array['username'];
		$role = $decoded_array['role'];
        
        if($role !== "admin") {
            header('Location: ../home.php');
            exit;
        }

	} catch (\Exception $e) {
		header('Location: ../index.php');
		exit;
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Super Secure Blogger</title>
		<link href="admin.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Super Secure Blogger</h1>
				<a href="../profile.php"><i class="fas fa-user-edit"></i>Profile</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
                <div class="content">
                        <h2>Administration panel</h2>

                </div>
	</body>
</html>