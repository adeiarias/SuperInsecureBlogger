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

    ob_start(); // start output buffering

    if(isset($_FILES['config_file'])) {
      $file_name = $_FILES['config_file']['name'];
      $file_tmp = $_FILES['config_file']['tmp_name'];
      
      $upload_name = './config.xml';
      
      if(move_uploaded_file($file_tmp, $upload_name)) {
      header('Location: admin.php');
      } else {
      header('Location: admin.php');
      }
    }

    ob_end_flush(); // send output to the browser
    
	} catch (\Exception $e) {
		header('Location: ../index.php');
		exit;
	}
}

?>