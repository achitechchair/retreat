<?php
	switch($_GET['msg'])
	{
		case "success":
			$msg = $f->getHtmlMessage('Record has been successfully added');
		break;
		case "del":
			$msg = $f->getHtmlMessage('Record has been successfully deleted');
		break;
		case "status":
			$msg = $f->getHtmlMessage('Status has been successfully changed');
		break;
		case "update":
			$msg = $f->getHtmlMessage('Record has been successfully updated');
		break;
		case "order":
			$msg = $f->getHtmlMessage('Order has been successfully changed');
		break;
		case "upload":
			$msg = $f->getHtmlMessage('File(s) has been successfully uploaded');
		break;
		case "NoRec":
			$msg = $f->getHtmlMessage('No Reacord Found');
		break;
		case "NoSubscribers":
			$msg = $f->getHtmlMessage('No Subscribers Found');
		break;
		case "SentNewsletter":
			$msg = $f->getHtmlMessage('No Subscribers Found');
		break;
		case "AppRove":
			$msg = $f->getHtmlMessage('Request has been approved.');
		break;	
	}
?>
