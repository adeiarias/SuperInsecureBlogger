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
		<title>Super (In)Secure Blogger</title>
		<link href="admin.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Super (In)Secure Blogger</h1>
				<a href="admin.php"><i class="fas fa-user-shield"></i>Home</a>
				<a href="users.php"><i class="fas fa-user-cog"></i>Manage Users</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
                <div class="content">
						<h2>Customize the application</h2>
						<br>
						<p>
							Use your own XML configuration files to customize the application.
						</p>
							<pre>
							Expected format:
							<b>
							&lt;?xml version="1.0" encoding="UTF-8"?&gt;
							&lt;config&gt;
								&lt;Font-size&gt;
									&lt;size&gt;12&lt;/size&gt;
								&lt;/Font-size&gt;
								&lt;Background-color&gt;
									&lt;color&gt;White&lt;/color&gt;
								&lt;/Background-color&gt;
							&lt;/config&gt;
							</pre>
							</b>
							THIS FEAUTRE IS CURRENTLY UNDER DEVELOPMENT. IT MIGHT NOT WORK AS EXPECTED. SORRY FOR THE INCONVENIENCES.
						<p>
						Current config:
						<br>
						<br>
						Font-size:
						<br>
						Background-color:
						</p>
                        <div class="formbold-main-wrapper">
                            <div class="formbold-form-wrapper">
                                <form action="users.php" method="POST">

                                <div class="formbold-mb-5">
                                    <label for="name" class="formbold-form-label"> Username </label>
                                    <input
                                    type="text"
                                    name="username"
                                    id="username"
                                    placeholder="username"
                                    class="formbold-form-input"
                                    />
                                </div>

                                <div>
                                    <button class="formbold-btn">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div>
                        <h2>Data backup</h2>
						
                </div>
	</body>
</html>