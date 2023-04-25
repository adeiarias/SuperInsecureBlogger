<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = getenv('JWT_SECRET');

if (!isset($_COOKIE['auth'])) {
	header('Location: index.php');
	exit;
} else {
	$cookie = $_COOKIE['auth'];
	try {
		$decoded = JWT::decode($cookie, new Key($key, 'HS256'));

    ob_start(); // start output buffering

    function updateProfilePicture($file_name, $cookie) {
      $command = 'curl -X PUT http://app:8000/user/newphoto \
          -H "Content-Type: application/json" \
          -H "auth: '.$cookie.'" \
          -d \'{"photo": "'.$file_name.'"}\'';
      exec($command);
    }

    if (isset($_FILES['profile_picture'])) {
      $file_name = $_FILES['profile_picture']['name'];
      $file_tmp = $_FILES['profile_picture']['tmp_name'];
      $upload_dir = 'uploads/';
      $destination = $upload_dir . $file_name;
      $cookie = $_COOKIE['auth'];
      
      if (move_uploaded_file($file_tmp, $destination)) {
        updateProfilePicture($file_name, $cookie);
        header('Location: profile.php');
      } else {
        header('Location: profile.php');
      }
    }

    ob_end_flush(); // send output to the browser
    
	} catch (\Exception $e) {
		header('Location: index.php');
		exit;
	}
}

?>