<?php

require __DIR__.'/vendor/autoload.php';
require('db.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'mustangs';

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
                <title>Developer Management System</title>
                <link href="admin.css" rel="stylesheet" type="text/css">
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        </head>
        <body class="loggedin">
                <nav class="navtop">
                        <div>
                                <h1>Developer Management System</h1>
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
          name="priority"
          id="priority"
          placeholder="Full Name"
          class="formbold-form-input"
        />
      </div>

      <div class="formbold-mb-5">
        <label for="message" class="formbold-form-label"> Description </label>
        <textarea
          rows="6"
          name="task"
          id="task"
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
                                        $stmt = $con->prepare('SELECT file, priority FROM tasks WHERE fixed = 0');
                                        $stmt->execute();
                                        $stmt->bind_result($description, $priority);
                                        $number = 1;
                                        while ($stmt->fetch()) {
                                                $descr = file_get_contents('5bf5dee65aca1cd1497ac8f30ccaf2815e75401e/'.$description);
                                                echo "<div>";
                                                echo "<p>Publication number $number</p>";
                                                echo "<table>";
                                                echo "<tr>";
                                                echo "<td>Title: ";
                                                echo $descr;
                                                echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td>User: ";
                                                echo $priority;
                                                echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td>Date: No</td>";
                                                echo "</tr>";
                                                echo "</table>";
                                                $number++;
                                                echo "<br>";
                                                echo "<button src='submit' class='formbold-btn'>Access to publication</button>";
                                                echo "</div>";
                                        }
                                        $stmt->close();
                                ?>
                </div>
        </body>
</html>
