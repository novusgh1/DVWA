<?php

require_once dirname( __FILE__ ) . '/../../../dvwa/includes/dvwaPendoTrack.inc.php';

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
	session_start();
	$redirect_target = $_GET['redirect'];
	$parsed_url = parse_url( $redirect_target );
	$visitor_id = isset( $_SESSION['dvwa']['username'] ) ? $_SESSION['dvwa']['username'] : 'anonymous';
	$security_level = isset( $_COOKIE['security'] ) ? $_COOKIE['security'] : 'unknown';

	pendoTrackEvent( 'open_redirect_attempted', $visitor_id, array(
		'security_level' => $security_level,
		'redirect_target' => substr( $redirect_target, 0, 200 ),
		'is_external_redirect' => (bool) preg_match( '/^https?:\/\//i', $redirect_target ),
		'target_domain' => isset( $parsed_url['host'] ) ? $parsed_url['host'] : 'local',
	));

	header ("location: " . $_GET['redirect']);
	exit;
}

http_response_code (500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>
