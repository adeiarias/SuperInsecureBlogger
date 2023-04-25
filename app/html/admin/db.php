<?php

$DATABASE_HOST = getenv('db_host');
$DATABASE_USER = getenv('db_user');
$DATABASE_PASS = getenv('db_passwd');
$DATABASE_NAME = getenv('database');
// Try and connect using the info above.
$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>
