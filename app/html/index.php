<?php
require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'mustangs';

if (isset($_COOKIE['auth'])) {
        $cookie = $_COOKIE['auth'];
        try {
                $decoded = JWT::decode($cookie, new Key($key, 'HS256'));
                $decoded_array = (array) $decoded;
                $user = $decoded_array['username'];
                $role = $decoded_array['role'];

                if ($role === "admin") {
                        header('Location: admin/admin.php');
                        exit;     
                } else {
						header('Location: home.php');
						exit;
				}
        } catch (\Exception $e) {}
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
                                <a href="login.html"><i class="fas fa-user-circle"></i>Login</a>
                                <a href="register.html"><i class="fas fa-user-plus"></i>Register</a>
                        </div>
                </nav>
                <div class="content">
                        <h2>Hi! Welcome to Blogger!<br>
			Register yourself to create your first publication. <br>
                        What are you waiting for!</h2>
                </div>
				<div class="content">
                        <h2>Latest publications</h2>
						<?php
                                        $stmt = $con->prepare('SELECT file, user, title, date FROM blog');
                                        $stmt->execute();
                                        $stmt->bind_result($file, $user, $title, $date);
                                        $number = 1;
                                        while ($stmt->fetch()) {
                                                echo "<div>";
                                                echo "<p>Publication number $number</p>";
                                                echo "<table>";
                                                echo "<tr>";
                                                echo "<td>Title: ";
                                                echo $title;
                                                echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td>User: ";
                                                echo $user;
                                                echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td>Date: ";
                                                echo $date;
                                                echo "</td>";
                                                echo "</tr>";
                                                echo "</table>";
                                                $number++;
                                                echo "<br>";
                                                echo "<button src='submit' class='formbold-btn'>Access to publication (Auth required)</button>";
                                                echo "</div>";
                                        }
                                        $stmt->close();
                                ?>
				</div>
        </body>
</html>
