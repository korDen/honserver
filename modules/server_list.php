<?php

function add_to_server_list(&$server_list, $ip, $port) {
	$server = array(
		'ip' => $ip,
		'port' => $port,
	);
	array_push($server_list, $server);
}

function obtain_server_list($cookie) {
	include "keys.php";

	$server_list = array();

	$mysqli = new mysqli($mysql_host, $mysql_serverlist_user, $mysql_serverlist_password, $mysql_serverlist_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT ip, serverPort, location FROM serverlist WHERE lastupdate > ?");
	$lastupdate = date('Y-m-d H:i:s', time() - 120);
	$statement->bind_param('s', $lastupdate);

	$statement->execute();
	$result = $statement->get_result();

	while ($row = $result->fetch_assoc()) {
		add_to_server_list($server_list, $row['ip'], $row['serverPort']);
	}

	// add local test server.
	add_to_server_list($server_list, '127.0.0.1', 11000);

	$acc_key = generate_random_hash();
	$response['acc_key'] = $acc_key;
	$response['acc_key_hash'] = sha1($acc_key . $cookie . HASH_SALT);
	$response['server_list'] = $server_list;

	return $response;
}

?>
