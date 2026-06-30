<?php

header ("X-XSS-Protection: 0");

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
	pendoTrackEvent( 'xss_reflected_payload_submitted', dvwaCurrentUser(), array(
		'security_level' => dvwaSecurityLevelGet(),
		'input_length' => strlen( $_GET[ 'name' ] ),
		'contains_script_tags' => (bool) preg_match( '/<script/i', $_GET[ 'name' ] ),
	));
	// Feedback for end user
	$html .= '<pre>Hello ' . $_GET[ 'name' ] . '</pre>';
}

?>
