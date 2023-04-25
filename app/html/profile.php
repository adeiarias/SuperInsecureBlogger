<?php
// We need to use sessions, so you should always start sessions using the below code.
require('db.php');
require __DIR__.'/vendor/autoload.php';
include 'vendor/twig/twig/lib/Twig/Autoloader.php';

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
		$decoded_array = (array) $decoded;
		$username = $decoded_array['username'];
		$role = $decoded_array['role'];

		# Get current user's email address
		$command = "curl http://app:8000/users/".$username." -H 'auth: '".$cookie;
		$response = exec($command);
		$email = json_decode($response, true)['email'];

		$command = "curl http://app:8000/user/photo -H 'auth: '".$cookie;
		$response = exec($command);
		$response = str_replace("\"", "", $response);

		try {
			Twig_Autoloader::register();
			$loader = new Twig_Loader_String();
			$twig = new Twig_Environment($loader);
		
			$output = $twig->render('<!DOCTYPE html>
			<html>
				<head>
					<meta charset="utf-8">
					<title>Profile Page</title>
					<link href="css/style.css" rel="stylesheet" type="text/css">
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
                            <form action="upload.php" method="POST" enctype="multipart/form-data">
                                <div>
                                    <input type="file" name="profile_picture" accept=".jpg, .jpeg, .png, .gif" required>
                                </div>
								<br>
								<div>
									<img src="uploads/'.$response.'" alt="Profile Picture" id="profile_picture_preview" style="max-width: 400px;">
								</div>
								<br>
                                <div>
                                    <button class="formbold-btn">Upload picture</button>
                                </div>
                            </form>
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
					<link href="css/style.css" rel="stylesheet" type="text/css">
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
						<form action="upload.php" method="POST">
							<div>
								<input type="file" name="profile_picture" accept=".jpg, .jpeg, .png, .gif" required>
							</div>
							<br>
							<div>
								<img src="uploads/'.$response.'" alt="Profile Picture" id="profile_picture_preview" style="max-width: 400px;">
							</div>
							<br>
							<div>
								<button class="formbold-btn">Upload picture</button>
							</div>
						</form>
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
		}

	} catch (\Exception $e) {
		header('Location: index.php');
		exit;
	}
}
?>