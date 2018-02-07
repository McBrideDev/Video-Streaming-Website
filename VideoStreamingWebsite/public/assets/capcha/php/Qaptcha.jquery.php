<?php
session_start();
$aResponse['error'] = false;
	
if(isset($_POST['action']) && isset($_POST['qaptcha_key']))
{
	$_SESSION['qaptcha_key'] = false;	
	
	if(htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'qaptcha')
	{
		$_SESSION['qaptcha_key'] = $_POST['qaptcha_key'];
		echo json_encode($aResponse);
	}
	else
	{
		$aResponse['error'] = true;
		echo json_encode($aResponse);
	}
}
else
{
	$aResponse['error'] = true;
	echo json_encode($aResponse);
}