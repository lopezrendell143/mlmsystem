<?php
session_start();

// 1. Unset all session keys in memory
$_SESSION = array();

// 2. Destroy the session cookie in the user's browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Clear and destroy the session instance on the server
session_destroy();

// 4. Force bounce back to the login gate
header("Location: login.php");
exit;
?>