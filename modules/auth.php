<?php

function perform_login($login, $password) {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT password, cookie, account_id, points, mmpoints, upgrades FROM accounts WHERE login = ?");
	$statement->bind_param('s', $login);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();

	$no_password = "";
	if ($row === null) {
		// User not found. Add it but without password.
		$statement = $mysqli->prepare("INSERT INTO accounts (login, password, cookie, points, mmpoints, upgrades, selected_upgrades) VALUES (?,?,?)");
		$cookie = generate_random_hash();
		$points = 0;
		$mmpoints = 1000;
		$upgrades = array();
		$upgrades_serialized = serialize("upgrades");
		$selected_upgrades = "ai.Default Icon";
		$statement->bind_param('sssiiss', $login, $no_password, $cookie, $points, $mmpoints, $upgrades_serialized, $selected_upgrades);
		$statement->execute();
		$account_id = $statement->insert_id;
	} else if ($row[0] != $no_password && $row[0] != $password) {
		// Password doesn't match.
		$response['error'][0] = "Invalid password.";
		return $response;
	} else {
		// Password matches. Update cookie.
		$cookie = generate_random_hash();
		$account_id = $row[2];

		$statement = $mysqli->prepare("UPDATE accounts SET cookie = ? WHERE account_id = ?");
		$statement->bind_param("si", $cookie, $account_id);
		$statement->execute();

		$points = $row[3];
		$mmpoints = $row[4];
		$upgrades = unserialize($row[5]);
	}

	// Required fields.
	$ip = $_SERVER['REMOTE_ADDR'];

	// Required for auth.
	$response['ip'] = $ip;
	$response['cookie'] = $cookie;
	$response['auth_hash'] = sha1($account_id . $ip . $cookie . HASH_SALT);

	$response['nickname'] = $login;
	$response['account_id'] = $account_id;
	$response['account_type'] = ACCOUNT_PREMIUM;

	// Not required but avoids error messages in console.
	$response['account_cloud_storage_info']['use_cloud'] = false;
	$response['account_cloud_storage_info']['cloud_autoupload'] = false;

	// infos.
	$infos['account_id'] = $account_id;
	$response['infos'][0] = $infos;
	$response['my_upgrades_info'] = array();

	$response['points'] = $points;
	$response['mmpoints'] = $mmpoints;
	$response['my_upgrades'] = $upgrades;

	return $response;
}

?>
