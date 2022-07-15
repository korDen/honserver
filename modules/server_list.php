<?php

function obtain_server_list() {
	include "keys.php";

	// $mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	// $statement = $mysqli->prepare("SELECT ip, serverPort, location FROM serverlist WHERE lastupdate > ?");
	// $lastupdate = date('Y-m-d H:i:s', time() - 120);
	// $statement->bind_param("s", $lastupdate);

	// $statement->execute();
	// $result = $statement->get_result();

	$server_list = array();
	$i = 0;
	// while ($row = $result->fetch_assoc()) {
	// 	$server = array(
	// 		'server_id' => $i,
	// 		'ip' => $row['ip'],
	// 		'port' => $row['serverPort'],
	// 		'location' => $row['location'],
	// 		'c_state' => 0,
	// 	);
	// 	$server_list[$i] = $server;
	// 	++$i;
	// }

		$server = array(
			'ip' => '127.0.0.1',
			'port' => 10000,
		);
		array_push($server_list, $server);
		++$i;
	
	$response['server_list'] = $server_list;
	return $response;
}

?>
