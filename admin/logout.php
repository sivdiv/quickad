<?php
require_once('includes.php');

// Unset all session values
$_SESSION = array();

// get session parameters
$params = session_get_cookie_params();

// Delete the actual cookie.
setcookie(session_name(),
    '', time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]);

// Destroy session
session_destroy();
if (isset($_COOKIE['quickadmin_remember'])) {
    unset($_COOKIE['quickadmin_remember']);
    setcookie('quickadmin_remember', null, -1, '/');
}
echo '<script>window.location="login.php"</script>';



?>

