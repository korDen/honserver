<?php

function perform_auth($login, $password) {
	define("ACCOUNT_PREMIUM", 4);

	$response['nickname'] = $login;
	$response['account_id'] = 42; // TODO: fixme.
	$response['account_type'] = ACCOUNT_PREMIUM;
	$response['account_cloud_storage_info']['use_cloud'] = false;
	$response['account_cloud_storage_info']['cloud_autoupload'] = false;
	$response['cookie'] = md5($login . ":" . $password);
	return $response;
}

?>
