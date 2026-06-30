<?php

/**
 * Pendo Server-Side Track Event Helper
 *
 * Sends track events to the Pendo Track API via HTTP POST.
 * Uses a short timeout to avoid impacting page load times.
 * Failures are silently ignored so tracking never breaks application flow.
 */

function pendoTrackEvent( $eventName, $visitorId = 'anonymous', $properties = array() ) {
	$integrationKey = 'd81f5a8d-afce-48ee-b9e2-06b4e2ace171';
	$endpoint = 'https://data.pendo.io/data/track';

	$payload = array(
		'type'       => 'track',
		'event'      => $eventName,
		'visitorId'  => $visitorId,
		'accountId'  => 'dvwa',
		'timestamp'  => round( microtime( true ) * 1000 ),
		'properties' => (object) $properties,
	);

	$jsonPayload = json_encode( $payload );

	try {
		$ch = curl_init( $endpoint );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $jsonPayload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'x-pendo-integration-key: ' . $integrationKey,
		));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 3 );
		curl_exec( $ch );
		curl_close( $ch );
	} catch ( Exception $e ) {
		// Silently fail - tracking should never break application flow
	}
}

?>
