<?php 
function aeshash($action, $string, $aeskey) {
	$output = false;
	$encrypt_method = "AES-256-CBC";
	$secret_key = $aeskey;
	$secret_iv = $aeskey;
	$key = hash(env('encryption.digest'), $secret_key);

	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	if ( $action == 'enc' ) {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	} else if( $action == 'dec' ) {
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}
	return $output;
}