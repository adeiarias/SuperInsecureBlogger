<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

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
        
        ob_start();
        include "/var/www/html/5bf5dee65aca1cd1497ac8f30ccaf2815e75401e/".urldecode($_POST['file']);
        $output = ob_get_clean();
        echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');

	} catch (\Exception $e) {
		header('Location: index.php');
		exit;
	}
}
?>