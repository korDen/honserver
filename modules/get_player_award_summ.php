<?php

function get_player_award_summ($nickname) {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT account_id FROM accounts WHERE login = ?");
	$statement->bind_param('s', $nickname);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();
	if ($row == null) {
		return array();
	}

	$response["account_id"] = $row[0];
	$response["awd_hcs"] = 0;
	$response["awd_ledth"] = 0;
	$response["awd_lgks"] = 0;
	$response["awd_mann"] = 0;
	$response["awd_masst"] = 0;
	$response["awd_mbdmg"] = 0;
	$response["awd_mhdd"] = 0;
	$response["awd_mkill"] = 0;
	$response["awd_mqk"] = 0;
	$response["awd_msd"] = 0;
	$response["awd_mwk"] = 0;
	$response["mvp"] = 0;

	return $response;
}

?>
