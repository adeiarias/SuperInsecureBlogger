<?php
// We need to use sessions, so you should always start sessions using the below code.
require('db.php');
require __DIR__.'/vendor/autoload.php';
include 'vendor/twig/twig/lib/Twig/Autoloader.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'mustangs';

if (!isset($_COOKIE['auth'])) {
	header('Location: index.php');
	exit;
} else {
	$cookie = $_COOKIE['auth'];
	try {
		$decoded = JWT::decode($cookie, new Key($key, 'HS256'));
		$decoded_array = (array) $decoded;
		$user = $decoded_array['username'];
		$role = $decoded_array['role'];

		$email = "";

			try {
				Twig_Autoloader::register();
				$loader = new Twig_Loader_String();
				$twig = new Twig_Environment($loader);
			
				$output = $twig->render('<!DOCTYPE html>
				<html>
					<head>
						<meta charset="utf-8">
						<title>Profile Page</title>
						<link href="admin/admin.css" rel="stylesheet" type="text/css">
						<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
					</head>
					<body class="loggedin">
						<nav class="navtop">
							<div>
								<h1>Super (In)Secure Blogger</h1>
								<a href="home.php"><i class="fas fa-home"></i>Home</a>
								<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
							</div>
						</nav>
						<div class="content">
							<h2>Profile picture</h2>
								<div>
								<h2>Select Profile Picture</h2>
								<br>
								<input type="file" name="profile_picture" accept=".jpg, .jpeg, .png, .gif" required>
								<h2>
								<img src="uploads/logo.png" alt="Profile Picture">
								<br>
								<br>
								<button type="submit" class="formbold-btn">Upload picture</button>
								</div>
							<h2>Change email</h2>
							<div class="formbold-main-wrapper">
							<div class="formbold-form-wrapper">
								<form action="change.php" method="POST">
				
								<div class="formbold-mb-5">
									<label for="name" class="formbold-form-label"> Email</label>
									<input
									type="text"
									name="email"
									id="email"
									placeholder="Introduce tu email"
									class="formbold-form-input"
									/>
								</div>
				
								<div>
									<button class="formbold-btn">Change</button>
								</div>
								</form>
							</div>
							</div>
							<h2>Personal information</h2>
							<div>
								<p>Your account details are below:</p>
								<table>
									<tr>
										<td>Username:</td>
										<td>'.$username.'</td>
									</tr>
									<tr>
										<td>Email:</td>
										<td>'.$email.'</td>
									</tr>
								</table>
							</div>
						</div>
						
					</body>
				</html>');
				echo $output;
			} catch(Exception $e) {
				Twig_Autoloader::register();
				$loader = new Twig_Loader_String();
				$twig = new Twig_Environment($loader);
				$output = $twig->render('<!DOCTYPE html>
				<html>
					<head>
						<meta charset="utf-8">
						<title>Profile Page</title>
						<link href="admin/admin.css" rel="stylesheet" type="text/css">
						<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
					</head>
					<body class="loggedin">
						<nav class="navtop">
							<div>
								<h1>Super (In)Secure Blogger</h1>
								<a href="home.php"><i class="fas fa-home"></i>Home</a>
								<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
							</div>
						</nav>
						<div class="content">
						<h2>Profile picture</h2>
							<div>
							<h2>Select Profile Picture</h2>
							<br>
							<input type="file" name="profile_picture" accept=".jpg, .jpeg, .png, .gif" required>
							<h2>
							<img src="uploads/logo.png" alt="Profile Picture">
							<br>
							<br>
							<button type="submit" class="formbold-btn">Upload picture</button>
							</div>
							<h2>Change email</h2>
							<div class="formbold-main-wrapper">
							<div class="formbold-form-wrapper">
								<form action="change.php" method="POST">
				
								<div class="formbold-mb-5">
									<label for="name" class="formbold-form-label"> Email</label>
									<input
									type="text"
									name="email"
									id="email"
									placeholder="Introduce tu email"
									class="formbold-form-input"
									/>
								</div>
				
								<div>
									<button class="formbold-btn">Change</button>
								</div>
								</form>
							</div>
							</div>
							<h2>Personal information</h2>
							<div>
								<p>Your account details are below:</p>
								<table>
									<tr>
										<td>Username:</td>
										<td>'.$username.'</td>
									</tr>
									<tr>
										<td>Email:</td>
										<td></td>
									</tr>
								</table>
							</div>
						</div>
						
					</body>
				</html>');
				echo $output;
			}

	} catch (\Exception $e) {
		header('Location: index.php');
		exit;
	}
}
?>