<?php
session_start();
session_destroy();  // Destroy the session to log the user out
header("Location: login.html");  // Redirect back to the login page
exit();
?>
