<?php

function perform_auth($login, $password) {
	define("ACCOUNT_PREMIUM", 4);

	// $GUEST_PASSWORD = "084e0343a0486ff05530df6c705c8bb4";
	// if ($password != $GUEST_PASSWORD) {
	// 	$response["error"][0] = "Use password 'guest' or register on www.honfans.com";
	// 	return $response;
	// }

	$response['nickname'] = $login;
	$response['account_id'] = 42; // TODO: fixme.
	$response['account_type'] = ACCOUNT_PREMIUM;
	$response['account_cloud_storage_info']['use_cloud'] = false;
	$response['account_cloud_storage_info']['cloud_autoupload'] = false;
	$response['cookie'] = md5($login . ":" . $password);

	return $response;
}

?>
