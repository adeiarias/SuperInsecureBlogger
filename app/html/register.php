<?php

require('db.php');

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	exit('Please complete the registration form!');
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}

// API endpoint URL
$url = 'http://app:8000/register';

// Request data
$data = array('username' => $_POST['username'], 'password' => $_POST['password'], 'email' => $_POST['email']);
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

header('Location: index.php');

?>
