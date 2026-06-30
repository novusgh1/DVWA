<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (!isset ($_SESSION['last_session_id'])) {
		$_SESSION['last_session_id'] = 0;
	}
	$_SESSION['last_session_id']++;
	$cookie_value = $_SESSION['last_session_id'];
	setcookie("dvwaSession", $cookie_value);
	pendoTrackEvent( 'weak_session_id_generated', dvwaCurrentUser(), array(
		'security_level' => dvwaSecurityLevelGet(),
		'generation_count' => $_SESSION['last_session_id'],
	));
}
?>
