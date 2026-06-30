<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = $_REQUEST[ 'ip' ];

	// Determine OS and execute the ping command.
	if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
		// Windows
		$cmd = shell_exec( 'ping  ' . $target );
		$os_type = 'Windows';
	}
	else {
		// *nix
		$cmd = shell_exec( 'ping  -c 4 ' . $target );
		$os_type = 'Linux';
	}

	pendoTrackEvent( 'command_injection_executed', dvwaCurrentUser(), array(
		'security_level' => dvwaSecurityLevelGet(),
		'input_length' => strlen( $target ),
		'contains_injection_chars' => (bool) preg_match( '/[;&|`$]/', $target ),
		'os_type' => $os_type,
	));

	// Feedback for the end user
	$html .= "<pre>{$cmd}</pre>";
}

?>
