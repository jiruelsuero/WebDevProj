<?php
// Start the session to manage user login state
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page
header("Location: log-in.php");
exit(); 
?>
