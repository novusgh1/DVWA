<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
	$redirect_target = $_GET['redirect'];
	$parsed = parse_url( $redirect_target );
	$is_external = isset( $parsed['host'] );
	pendoTrackEvent( 'open_redirect_attempted', dvwaCurrentUser(), array(
		'security_level' => dvwaSecurityLevelGet(),
		'redirect_target' => substr( $redirect_target, 0, 200 ),
		'is_external_redirect' => $is_external,
	));
	header ("location: " . $redirect_target);
	exit;
}

http_response_code (500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>
