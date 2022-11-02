<?php 
	session_start();
	//session_unset($_SESSION['user']); //error - change to unset
	unset($_SESSION['user']); // will delete just the user data
	session_destroy(); 	// will delete ALL data associated with that user.
	header('location: index.php');
?>