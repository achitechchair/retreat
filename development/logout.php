<?php
	include_once('includes/config.inc.php');	
	$_SESSION['reg_id_edit'] = "";
	$_SESSION['userIs'] = "";	 
	
					  
	$_SESSION['guest_checkout'] = "";
	// Unset all of the session variables.
	$_SESSION = array();	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/'); 
	}	
	// Finally, destroy the session.
	session_destroy();	
	// Goto display message	
	header("Location: ".WEBSITE_URL."/thankyou/logout"); 
	exit(); 
?>