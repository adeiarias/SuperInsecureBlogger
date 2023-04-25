<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = getenv('JWT_SECRET');

if (!isset($_COOKIE['auth'])) {
	exit;
	header('Location: index.php');
} else {
	$cookie = $_COOKIE['auth'];
	try {
		$decoded = JWT::decode($cookie, new Key($key, 'HS256'));
		$decoded_array = (array) $decoded;
		$user = $decoded_array['username'];
		$email = str_replace('"', '\"', $_POST['email']);

		$command = 'curl -X PUT http://app:8000/user/email \
		-H "Content-Type: application/json" \
		-H "auth: '.$cookie.'" \
		-d \'{"username": "'.$user.'", "email": "'.$email.'"}\'';
		$response = exec($command);

		header('Location: profile.php');

	} catch (\Exception $e) {
		header('Location: index.php');
		exit;
	}
}

?>