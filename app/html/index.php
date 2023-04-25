<?php

require __DIR__ . '/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = getenv('JWT_SECRET');

if (isset($_COOKIE['auth'])) {
    $cookie = $_COOKIE['auth'];
    try {
        $decoded = JWT::decode($cookie, new Key($key, 'HS256'));
        $decoded_array = (array) $decoded;
        $user = $decoded_array['username'];
        $role = $decoded_array['role'];

        $redirectTo = $role === 'admin' ? 'admin/admin.php' : 'home.php';
        header("Location: $redirectTo");
        exit;
    } catch (\Exception $e) {
        // Handle exception
    }
}

$stmt = $con->prepare('SELECT file, user, title, date FROM blog');
$stmt->execute();
$stmt->bind_result($file, $user, $title, $date);

$publications = [];
while ($stmt->fetch()) {
    $publications[] = [
        'title' => $title,
        'user' => $user,
        'date' => $date,
    ];
}

$stmt->close();

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
    <?php foreach ($publications as $index => $publication): ?>
        <div>
            <p>Publication number <?= $index + 1 ?></p>
            <table>
                <tr>
                    <td>Title: <?= $publication['title'] ?></td>
                </tr>
                <tr>
                    <td>User: <?= $publication['user'] ?></td>
                </tr>
                <tr>
                    <td>Date: <?= $publication['date'] ?></td>
                </tr>
            </table>
            <br>
            <button src="submit" class="formbold-btn">Access to publication (Auth required)</button>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>