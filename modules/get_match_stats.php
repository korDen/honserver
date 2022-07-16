<?php

function get_match_stats($match_id) {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT result FROM matches WHERE match_id = ?");
	$statement->bind_param('s', $match_id);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();

	if ($row == null) {
		return array();
	}

	$response = unserialize($row[0]);
	return $response;
}

?>
