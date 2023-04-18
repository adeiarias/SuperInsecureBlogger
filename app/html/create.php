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

        $filename = uniqid().'.txt';
        system("echo ".$_POST['message']." > /var/www/html/5bf5dee65aca1cd1497ac8f30ccaf2815e75401e/".$filename);

        if ($stmt = $con->prepare('INSERT INTO blog (file, user, title, date) VALUES (?, ?, ?, ?)')) {
            $stmt->bind_param('ssss', $filename, $user, $_POST['title'], date("Y-m-d H:i:s"));
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
