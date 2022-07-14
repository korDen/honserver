<?php

function perform_auth($login, $password) {
	define("ACCOUNT_PREMIUM", 4);

	$response['account_id'] = 42; // TODO: fixme.
	$response["account_type"] = ACCOUNT_PREMIUM;
	return $response;
}

?>
