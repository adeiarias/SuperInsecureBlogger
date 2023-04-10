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
                <title>Super Secure Blogger</title>
                <link href="admin.css" rel="stylesheet" type="text/css">
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        </head>
        <body class="loggedin">
                <nav class="navtop">
                        <div>
                                <h1>Super Secure Blogger</h1>
                                <a href="login.html"><i class="fas fa-user-circle"></i>Login</a>
                                <a href="register.html"><i class="fas fa-user-plus"></i>Register</a>
                        </div>
                </nav>
                <div class="content">
                        <h2>Hi! Welcome to Blogger!. <br>
						Register yourself to create your first publication. What are you waiting for!</h2>
                </div>
				<div class="content">
                        <h2>Latest publications</h2>
						<?php
                                        $stmt = $con->prepare('SELECT file, priority FROM tasks WHERE fixed = 0');
                                        $stmt->execute();
                                        $stmt->bind_result($description, $priority);
                                        $number = 1;
                                        while ($stmt->fetch()) {
                                                $descr = file_get_contents('5bf5dee65aca1cd1497ac8f30ccaf2815e75401e/'.$description);
                                                echo "<div>";
                                                echo "<p>Publication number $number</p>";
                                                echo "<table>";
                                                echo "<tr>";
                                                echo "<td>Title: ";
                                                echo $descr;
                                                echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td>User: ";
                                                echo $priority;
                                                echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td>Date: No</td>";
                                                echo "</tr>";
                                                echo "</table>";
                                                echo "</div>";
                                                $number++;
                                        }
                                        $stmt->close();
                                ?>
				</div>
        </body>
</html>
