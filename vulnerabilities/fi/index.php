<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: File Inclusion' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'fi';
$page[ 'help_button' ]   = 'fi';
$page[ 'source_button' ] = 'fi';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/fi/source/{$vulnerabilityFile}";

// if( count( $_GET ) )
if( isset( $file ) ) {
	pendoTrackEvent( 'file_inclusion_attempted', dvwaCurrentUser(), array(
		'security_level' => dvwaSecurityLevelGet(),
		'file_path' => substr( $file, 0, 200 ),
		'is_remote_url' => (bool) preg_match( '/^https?:\/\//i', $file ),
		'input_length' => strlen( $file ),
	));
	include( $file );
}
else {
	header( 'Location:?page=include.php' );
	exit;
}

dvwaHtmlEcho( $page );

?>
