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

function get_username($uname) {
    $cookie = $_COOKIE['auth'];
    $command = "curl http://app:8000/users/".$_POST["username"]." -H 'auth: '".$cookie;
    $response = exec($command);
    $username = json_decode($response, true)['username'];
    $email = json_decode($response, true)['email'];
    return array($username, $email);
}

if(isset($_POST['username'])) {
    $username = $_POST['username'];
    $result = get_username($username);
    $user_info = array("Username" => $result[0], "Email" => $result[1]);
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
				<a href="profile.php"><i class="fas fa-user-edit"></i>Profile</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
                <div class="content">
                        <h2>Manage Users</h2>
						<h3>Access registered users' information</h3>
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
                        <?php if(isset($user_info)): ?>
                        <h3>User information</h3>
							<div>
								<p>Searched user's information can be found below:</p>
								<table>
									<tr>
										<td>Username:</td>
										<td><?php
                                        if ($user_info["Username"] !== null) {
                                            echo $user_info["Username"];
                                        } else {
                                            echo "The provided user does not exist";
                                        }
                                        ?>
                                        </td>
									</tr>
									<tr>
										<td>Email:</td>
										<td><?php echo $user_info["Email"]; ?></td>
									</tr>
								</table>
						</div>
                        <?php endif; ?>
                </div>
	</body>
</html>
