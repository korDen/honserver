<?php

function perform_auth($cookie) {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_accounts_user, $mysql_accounts_password, $mysql_accounts_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT account_id, login FROM accounts WHERE cookie = ?");
	$statement->bind_param('s', $cookie);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();

	if ($row === null) {
		// User not found.
		return array();
	}

	$response['account_id'] = $row[0];
	$response['game_cookie'] = generate_random_hash();
	$response['account_type'] = ACCOUNT_PREMIUM;
	$response['nickname'] = $row[1];

	return $response;
}

?>
