<?php

function get_simple_stats($nickname) {
	include 'keys.php';
	include 'upgrades.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT account_id, upgrades, selected_upgrades FROM accounts WHERE login = ?");
	$statement->bind_param('s', $nickname);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();

	if ($row == null) {
		$response["error"][0] = "Unknown username";
		return $response;
	}

	$account_id = $row[0];
	$upgrades = unserialize($row[1]);

	// This is complete list of fields used.
	$response['nickname'] = $nickname;
	$response['level'] = 1;
	$response['level_exp'] = 0;
	$response['hero_num'] = 0;
	$response['avatar_num'] = count($upgrades); // Not quite accurate since upgrades includes all, not just avatars.
	$response['total_played'] = 0;
	$response['mvp_num'] = 0;
	$response['selected_upgrades'] = $row[2];
	$response['account_id'] = $account_id;
	$response['season_id'] = 0;
	$response['season_normal'] = array(
		"wins" => 0,
		"losses" => 0,
		"win_streak" => 0,
		"current_level" => 0,
	);
	$response['season_casual'] = array(
		"wins" => 0,
		"losses" => 0,
		"win_streak" => 0,
		"current_level" => 0,
	);

	// $awards = ['awd_hcs', 'awd_ledth', 'awd_lgks', 'awd_mann', 'awd_masst', 'awd_mbdmg', 'awd_mhdd', 'awd_mkill', 'awd_mqk', 'awd_msd', 'awd_mvp', 'awd_mwk']
	$response['award_top4_name'] = array(
		0 => 'awd_hcs',
		1 => 'awd_ledth',
		2 => 'awd_lgks',
		3 => 'awd_mann',
	);
	$response['award_top4_num'] = array(
		0 => 0,
		1 => 0,
		2 => 0,
		3 => 0,
	);

	return $response;
}

?>