<?php
session_start();
include('/Error_D_back');
if (!isset($_SESSION['username'])) {
	    header("Location: login_test.php");
	        exit();
}

echo "Welcome, " . $_SESSION['username'] . "!";
?>

<a href="logout_test.php">Logout</a>
