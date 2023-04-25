<?php

require __DIR__.'/../vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = getenv('JWT_SECRET');

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

function get_posts($uname) {
    global $conn;
    $sql = 'SELECT file, title, date FROM blog where user=\''.$uname.'\'';
    $result = mysqli_query($conn, $sql);
    $posts = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
    return $posts;
}

if(isset($_POST['username'])) {
    $username = $_POST['username'];
    $posts = get_posts($username); // replace 'username' with the actual username
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Super (In)Secure Blogger</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Super (In)Secure Blogger</h1>
                <a href="users.php"><i class="fas fa-comments"></i>Manage users</a>
				<a href="admin.php"><i class="fas fa-user-shield"></i>Home</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
                <div class="content">
                        <h2>Manage Posts</h2>
						<h3>Search posts by username</h3>
                        <div class="formbold-main-wrapper">
                            <div class="formbold-form-wrapper">
                                <form action="" method="POST">

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
                                    <button class="formbold-btn">Search</button>
                                </div>
                                </form>
                            </div>
                        </div>
                        <h3>Posts</h3>
                        <?php
                            if(isset($_POST['username'])) {
                                $number = 1;
                                foreach ($posts as $post) {
                                    $descr = file_get_contents('../blog_files/'.$file);
                                    echo "<div>";
                                    echo "<p>Publication number $number</p>";
                                    echo "<table>";
                                    echo "<tr>";
                                    echo "<td>Title: ";
                                    echo $post['title'];
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<td>Date: ";
                                    echo $post['date'];
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "</table>";
                                    $number++;
                                    echo "<br>";
                                    echo "<form method='post' action='../viewfile.php'>";
                                    echo "<input type='hidden' name='title' value=".$post["title"].">";
                                    echo "<input type='hidden' name='file' value=".$post["file"].">";
                                    echo "<button type='submit' class='formbold-btn'>Access to publication</button>";
                                    echo "</form>";
                                    echo "</div>";
                                }
                            }
                        ?>
                </div>
	</body>
</html>
