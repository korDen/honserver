<?php

function start_game() {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("INSERT INTO matches (result) VALUES (?)");
	$empty = serialize(array());
	$statement->bind_param('s', $empty);
	$statement->execute();
	$match_id = $statement->insert_id;

	$response['match_id'] = $match_id;
	$response['match_date'] = date('Y-m-d H:i:s', time());

	return $response;
}

?>
