<?php
if (isset($_COOKIE['auth'])) {
    unset($_COOKIE['auth']);
    setcookie('auth', '', time() - 3600);
}
// Redirect to the login page:
header('Location: index.php');
?>