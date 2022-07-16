<?php

function get_init_stats($cookie) {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT account_id FROM accounts WHERE cookie = ?");
	$statement->bind_param('s', $cookie);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();
	if ($row == null) {
		$response["error"][0] = "Unknown cookie";
		return $response;
	}

	$account_id = $row[0];
	$infos['account_id'] = $account_id;
	$response['infos'][0] = $infos;

	return $response;
}

?>
