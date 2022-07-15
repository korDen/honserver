<?php

function perform_login($login, $password) {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_accounts_user, $mysql_accounts_password, $mysql_accounts_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT password, cookie, account_id FROM accounts WHERE login = ?");
	$statement->bind_param('s', $login);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();

	$no_password = "";
	if ($row === null) {
		// User not found. Add it but without password.
		$statement = $mysqli->prepare("INSERT INTO accounts (login, password, cookie) VALUES (?,?,?)");
		$cookie = generate_random_hash();
		$statement->bind_param('sss', $login, $no_password, $cookie);
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
	}

	$ip = $_SERVER['REMOTE_ADDR'];
	$response['nickname'] = $login;
	$response['cookie'] = $cookie;
	$response['ip'] = $ip;
	$response['account_id'] = $account_id;
	$response['account_type'] = ACCOUNT_PREMIUM;
	$response['account_cloud_storage_info']['use_cloud'] = false;
	$response['account_cloud_storage_info']['cloud_autoupload'] = false;
	$response['auth_hash'] = sha1($account_id . $ip . $cookie . HASH_SALT);

	return $response;
}

?>
