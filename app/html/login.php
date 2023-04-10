<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
$key = 'mustangs';

if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

// API endpoint URL
$url = 'http://app:8000/login';

// Request data
$data = array('username' => $_POST['username'], 'password' => $_POST['password']);
$data_string = json_encode($data);

// Set curl options
$options = array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data_string,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
    )
);

// Init curl
$curl = curl_init();
curl_setopt_array($curl, $options);

// Send request and get response
$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Close curl
curl_close($curl);

ob_start(); // start output buffering

// Check response status code
if ($http_code == 200) {
    // Authentication successful
    $token = json_decode($response, true)['token'];
    setcookie('auth', $token);
    $command = "curl http://app:8000/users/".$_POST["username"]." -H 'auth: '".$token." | jq .role | tr -d '\"'";
    $role = system($command);
    if ($role === "admin") {
        header('Location: admin/admin.php');
    } else {
        header('Location: home.php');
    }
} else {
    // Authentication failed
    echo 'Authentication error: ' . $http_code;
}

ob_end_flush(); // send output to the browser

?>
