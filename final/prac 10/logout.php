<?php
session_start();
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $options = session_get_cookie_params();
    setcookie(session_name(), "", time() - 3600, $options["path"], $options["domain"], $options["secure"], $options["httponly"]);
}
session_destroy();
header("Location: login.php?msg=logout");
exit;
?>
