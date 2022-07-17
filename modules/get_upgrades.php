<?php

function get_upgrades($cookie) {
	include 'keys.php';
	include 'upgrades.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT account_id, points, mmpoints, upgrades, selected_upgrades FROM accounts WHERE cookie = ?");
	$statement->bind_param('s', $cookie);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();
	if ($row == null) {
		$response["error"][0] = "Unknown cookie";
		return $response;
	}

	$account_id = $row[0];
	$field_stats['account_id'] = $account_id;
	$response['field_stats'][0] = $field_stats;
	$response['my_upgrades_info'] = array();

	$response['points'] = $row[1];
	$response['mmpoints'] = $row[2];
	$response['game_tokens'] = 0;
	$response['standing'] = 3;
	$response['level'] = 1;
	$response['level_exp'] = 1;
	// $response['season_level'] = 0;
	// $response['creep_level'] = 0;
	// $response['gca_prime_inv'] = array();
	$response['my_upgrades'] = unserialize($row[3]);
	$response['selected_upgrades'] = $row[4];

	return $response;
}

?>
