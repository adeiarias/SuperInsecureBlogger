<!DOCTYPE html>
<html>
<head>
	<title>Profile Picture Upload</title>
</head>
<body>

<?php
system("id");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Check if the file was uploaded without errors
	if(isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if the file is a valid image file
		if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
			// Move the uploaded file to the target directory
			if(move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
				echo "<h1>Profile Picture Uploaded Successfully</h1>";
				echo "<img src='$target_file' alt='Profile Picture'>";
			} else {
				echo "<h1>Sorry, there was an error uploading your file.</h1>";
			}
		} else {
			echo "<h1>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</h1>";
		}
	} else {
		echo "<h1>Sorry, there was an error uploading your file.</h1>";
	}
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <h2>Select Profile Picture</h2>
    <input type="file" name="profile_picture" accept=".jpg, .jpeg, .png, .gif" required>
    <br>
    <input type="submit" value="Upload Profile Picture" name="submit">
</form>

</body>
</html>
