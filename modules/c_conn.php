<?php

function perform_auth($cookie) {
	include 'keys.php';
	include 'upgrades.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT account_id, login, upgrades FROM accounts WHERE cookie = ?");
	$statement->bind_param('s', $cookie);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();

	if ($row === null) {
		// User not found.
		return array();
	}

	// Required.
	$response['account_id'] = $row[0];
	$response['game_cookie'] = generate_random_hash();
	$response['account_type'] = ACCOUNT_PREMIUM;
	$response['nickname'] = $row[1];
	$response['my_upgrades'] = unserialize($row[2]);

	return $response;
}

?>
