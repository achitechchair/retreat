<?php
	//session_start();
	require_once("../includes/config.inc.php");
	/*$username = $_SESSION['_admin'];
	if($username!="admin")
	{
		$log_entry = array(
			"user_id" => $_SESSION['_admin_users_id'],
			"ip_address" => $_SERVER['REMOTE_ADDR'],
			"type" => "Logout",
			"create_date" => "CURDATE()",
			"create_time" => "CURTIME()"
		);
		$db->insert("tbl_user_log", $log_entry);
	}
	
	$db->get("DELETE FROM `tbl_user_log` WHERE `create_date` < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)", __FILE__, __LINE__);*/
	// Unset all of the session variables.
	$_SESSION = array();	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (isset($_COOKIE[session_name()]))
	{
		setcookie(session_name(), '', time()-42000, '/');
	}	
	// Finally, destroy the session.
	session_destroy();	
	// Goto display message
	header("Location: index.php?action=1");
	exit();
?>