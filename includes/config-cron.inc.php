<?php
	error_reporting(E_ALL ^ E_NOTICE);
	//Setting INI value at runtime
	@ini_set('session.use_trans_sid','0');
	@ini_set('session.use_only_cookies','1');
	@ini_set('display_errors','1');
	@ini_set('track_errors','1');
	@ini_set('session.gc_maxlifetime','3600');
	//@ini_set('session.save_path','/home/content/77/11229577/html/tmp');
	// HTTP/1.1
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);	
	// HTTP/1.0
	header("Pragma: no-cache");
	//Starting Session
	//ob_start();
	//session_start();
	// Magic quotes runtime off
	//@set_magic_quotes_runtime(false);
	@date_default_timezone_set('America/New_York');

	//include the class file
	require_once('define.inc.php');
	require_once('database.inc.php');
	require_once('security.inc.php');
	require_once('function.inc.php');
	require_once('class.phpmailer.php');
	require_once('class.smtp.php');
	require_once('methods.inc.php');	
	
	 $config = array(
			"DATABASE_HOST" => "localhost",
			"DATABASE_PORT" => "3306",
			"DATABASE_USER" => "root",
			"DATABASE_PASSWORD" => "root",
			"DATABASE_NAME" => "achi_org"
		);
	

	// Instance of the Database class
	$db = new Database($config);	
	
	$db->get("SET time_zone =  '-04:00'");
	// Instance of the Functions class
	$f = new Functions();
	// Instance of the Methods class
	$m = new Methods($db);
	// Xss Clean for all get variables
	require_once('xss.clean.inc.php');
	// Get Admin Settings
	$AdminSettings = $m->getSettings();	
	
?>
