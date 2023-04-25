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
                $decoded_array = (array) $decoded;
                $user = $decoded_array['username'];
                $role = $decoded_array['role'];

                if ($role === "admin") {
                        header('Location: admin/admin.php');
                        exit;     
                }
        } catch (\Exception $e) {
                header('Location: index.php');
                exit;
        }
}
?>

<!DOCTYPE html>
<html>
        <head>
                <meta charset="utf-8">
                <title>Super (In)Secure Blogger</title>
                <link href="css/style.css" rel="stylesheet" type="text/css">
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        </head>
        <body class="loggedin">
                <nav class="navtop">
                        <div>
                                <h1>Super (In)Secure Blogger</h1>
                                <a href="profile.php"><i class="fas fa-user-edit"></i>Profile</a>
                                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                        </div>
                </nav>
                <div class="content">
                        <h2>Hi <?php echo $user; ?>! We are glad you are here!</h2>
                        <div class="formbold-main-wrapper">
  <div class="formbold-form-wrapper">
    <form action="create.php" method="POST">

      <div class="formbold-mb-5">
        <label for="name" class="formbold-form-label"> Title </label>
        <input
          type="text"
          name="title"
          id="title"
          placeholder="Full Name"
          class="formbold-form-input"
        />
      </div>

      <div class="formbold-mb-5">
        <label for="message" class="formbold-form-label"> Description </label>
        <textarea
          rows="6"
          name="message"
          id="message"
          placeholder="Type your message"
          class="formbold-form-input"
        ></textarea>
      </div>

      <div>
        <button class="formbold-btn">Submit</button>
      </div>
    </form>
  </div>
</div>
                </div>
<div class="content">
<h2>Latest publications</h2>
<?php
    $stmt = $con->prepare('SELECT file, user, title, date FROM blog');
    $stmt->execute();
    $stmt->bind_result($file, $user, $title, $date);
    $number = 1;
    while ($stmt->fetch()) {
        $descr = file_get_contents('blog_files/'.$file);
        echo "<div>";
        echo "<p>Publication number $number</p>";
        echo "<table>";
        echo "<tr>";
        echo "<td>Title: ";
        echo $title;
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>User: ";
        echo $user;
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Date: ";
        echo $date;
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        $number++;
        echo "<br>";
        echo "<form method='post' action='viewfile.php'>";
        echo "<input type='hidden' name='title' value='$title'>";
        echo "<input type='hidden' name='file' value='$file'>";
        echo "<button type='submit' class='formbold-btn'>Access to publication</button>";
        echo "</form>";
        echo "</div>";
    }
    $stmt->close();
?>
                </div>
        </body>
</html>
