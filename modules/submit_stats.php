<?php

function submit_stats($data) {
	include 'keys.php';

	$result[0] = true;

	$match_summ = $data['match_stats'];
	$match_id = $match_summ['match_id'];

	$winning_team = 0;
	$player_stats = array();
	foreach ($data['player_stats'] as $account_id=>$value) {
		$hero_name = array_key_first($value);
		$player = $value[$hero_name];
		if ($player['wins']) {
			$winning_team = $player['team']; 
		}
		$player['account_id'] = $account_id;
		$player['match_id'] = $match_id;
		$player['cli_name'] = $hero_name;
		$player_stats[$account_id] = $player;
	}
	$result['match_player_stats'][$match_id] = $player_stats;

	$match_summ['winning_team'] = $winning_team;
	$result['match_summ'][$match_id] = $match_summ;

	if (isset($data['inventory'])) {
		$inventory = array();
		foreach ($data['inventory'] as $account_id=>$value) {
			$player_stat = array(
				"account_id" => strval($account_id),
	            "match_id" => strval($match_id),
	        );
			for ($slot = 0; $slot < 6; $slot++) {
				if (isset($value[$slot])) {
					$player_stat["slot_".($slot + 1)] = $value[$slot];
				} else {
					$player_stat["slot_".($slot + 1)] = null;
				}
			}
			$inventory[$account_id] = $player_stat;
		}
		$result['inventory'][$match_id] = $inventory;
	}
	$result_string = serialize($result);

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("UPDATE matches SET result = ? WHERE match_id = ?");
	$statement->bind_param('si', $result_string, $match_id);
	$statement->execute();

	$response['match_info'] = 'OK';
	$response['match_summ'] = 'OK';
	$response['match_stats'] = 'OK';
	$response['match_history'] = 'OK';

	return $response;
}

?>
