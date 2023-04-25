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
		$decoded_array = (array) $decoded;
		$user = $decoded_array['username'];
		$role = $decoded_array['role'];
        
        if($role !== "admin") {
            header('Location: ../home.php');
            exit;
        }

	} catch (\Exception $e) {
		header('Location: ../index.php');
		exit;
	}
}

if (isset($_POST['backup'])) {
	$user = getenv("db_user");
	$password = getenv("db_passwd");
	$db = getenv("db_host");
	$backup_command = "mysqldump -u ".$user." -p".$password." ".$db." > " . $_POST["backup"];
	exec($backup_command);
}

libxml_disable_entity_loader (false);
$dom = new DOMDocument();
$dom->loadXML(file_get_contents('config.xml'), LIBXML_NOENT | LIBXML_DTDLOAD);
$xml = simplexml_import_dom($dom);
$size = $xml->Font_size->size;
$color = $xml->Background_color->color;
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Super (In)Secure Blogger</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Super (In)Secure Blogger</h1>
				<a href="users.php"><i class="fas fa-user-cog"></i>Manage Users</a>
				<a href="posts.php"><i class="fas fa-comments"></i>Manage posts</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
				<div class="content">
					<h2>Administration panel</h2>
					<h3>Data backup</h3>
					<p>Make a complete data backup of the application</p>
					<div class="formbold-main-wrapper">
                            <div class="formbold-form-wrapper">
                                <form action="" method="POST">

                                <div class="formbold-mb-5">
                                    <label for="name" class="formbold-form-label"> Backup name </label>
                                    <input
                                    type="text"
                                    name="backup"
                                    id="backup"
                                    placeholder="backup name"
                                    class="formbold-form-input"
                                    />
                                </div>

                                <div>
                                    <button class="formbold-btn">Backup</button>
									<br>
									<br>
									<?php
										if (isset($_POST['backup'])) {
											echo "A backup file will be available shortly";
										}
									?>
                                </div>
                                </form>
                            </div>
                        </div>
					<h3>Customize the application</h3>
						<p>
							Use your own XML configuration files to customize the application.
						</p>
						Expected format:
	<pre>
	<b>
	&lt;?xml version="1.0" encoding="UTF-8"?&gt;
	&lt;config&gt;
		&lt;Font_size&gt;
			&lt;size&gt;12&lt;/size&gt;
		&lt;/Font_size&gt;
		&lt;Background_color&gt;
			&lt;color&gt;White&lt;/color&gt;
		&lt;/Background_color&gt;
	&lt;/config&gt;
	</pre>
						</b>
						THIS FEAUTRE IS CURRENTLY UNDER DEVELOPMENT. IT MIGHT NOT WORK AS EXPECTED. SORRY FOR THE INCONVENIENCES.
					
							
						<div>
							<br>
                            <form action="upload.php" method="POST" enctype="multipart/form-data">
                                <div>
                                    <input type="file" name="config_file" accept=".xml," required>
                                </div>
								<br>
                                <div>
                                    <button class="formbold-btn">Upload config file</button>
                                </div>
                            </form>
						</div>
                           
						<p>		
						Current config:
						<br>
						<br>
						Font-size: <?php echo $size; ?>
						<br>
						Background-color: <?php echo $color; ?>
						</p>				
                </div>
	</body>
</html>
