<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'mustangs';

ob_start(); // start output buffering

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

        $filename = uniqid().'.txt';
        system("echo ".$_POST['task']." > /var/www/html/5bf5dee65aca1cd1497ac8f30ccaf2815e75401e/".$filename);

        if ($stmt = $con->prepare('INSERT INTO tasks (file, priority, fixed, privileged) VALUES (?, ?, 0, 0)')) {
            $stmt->bind_param('ss', $filename, $_POST['priority']);
            $stmt->execute();
            $stmt->close();
            header('Location: home.php');
        } else {
            echo 'Could not prepare statement!';
        }

        $stmt->close();

	} catch (\Exception $e) {
		header('Location: index.php');
		exit;
	}
}

ob_end_flush(); // send output to the browser

?>
