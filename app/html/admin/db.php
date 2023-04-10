<?php

$DATABASE_HOST = 'contenedor_mysql';
$DATABASE_USER = 'seper';
$DATABASE_PASS = 'OasBOrESteNDOMen';
$DATABASE_NAME = 'app';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>
