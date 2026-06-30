<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// Where are we going to be writing to?
	$target_path  = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/";
	$target_path .= basename( $_FILES[ 'uploaded' ][ 'name' ] );

	// Can we move the file to the upload folder?
	if( !move_uploaded_file( $_FILES[ 'uploaded' ][ 'tmp_name' ], $target_path ) ) {
		// No
		pendoTrackEvent( 'file_uploaded', dvwaCurrentUser(), array(
			'security_level' => dvwaSecurityLevelGet(),
			'file_name' => basename( $_FILES[ 'uploaded' ][ 'name' ] ),
			'file_type' => $_FILES[ 'uploaded' ][ 'type' ],
			'file_size' => $_FILES[ 'uploaded' ][ 'size' ],
			'upload_outcome' => 'failure',
		));
		$html .= '<pre>Your image was not uploaded.</pre>';
	}
	else {
		// Yes!
		pendoTrackEvent( 'file_uploaded', dvwaCurrentUser(), array(
			'security_level' => dvwaSecurityLevelGet(),
			'file_name' => basename( $_FILES[ 'uploaded' ][ 'name' ] ),
			'file_type' => $_FILES[ 'uploaded' ][ 'type' ],
			'file_size' => $_FILES[ 'uploaded' ][ 'size' ],
			'upload_outcome' => 'success',
		));
		$html .= "<pre>{$target_path} succesfully uploaded!</pre>";
	}
}

?>
