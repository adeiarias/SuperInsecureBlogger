<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'mustangs';

if (!isset($_COOKIE['auth'])) {
	exit;
	header('Location: ../index.php');
} else {
	$cookie = $_COOKIE['auth'];
	try {
		$decoded = JWT::decode($cookie, new Key($key, 'HS256'));
		$decoded_array = (array) $decoded;
		$user = $decoded_array['username'];
		$role = $decoded_array['role'];

        if ($stmt = $con->prepare('UPDATE users set email = ? where username = ?')) {
            $stmt->bind_param('ss', $_POST['email'], $user);
            $stmt->execute();
            $stmt->close();
            header('Location: ../profile.php');
        } else {
            echo 'Could not prepare statement!';
        }

        $stmt->close();

	} catch (\Exception $e) {
		header('Location: ../index.php');
		exit;
	}
}
?>