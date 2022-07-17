<?php

function select_taunt($taunts, $product_name, &$selected_upgrades) {
	foreach ($taunts as $product_id=>$taunt) {
		if ($taunt['name'] == $product_name) {
			// remove all taunts.
			foreach ($selected_upgrades as $k=>$v) {
				if (str_starts_with($v, "t.")) {
					$selected_upgrades[$k] = "t.".$product_name;
					return;
				}
			}
		}
	}

	array_push($selected_upgrades, "t.".$product_name);
}

function select_upgrades($cookie, $upgrades_to_select) {
	include 'keys.php';
	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT selected_upgrades FROM accounts WHERE cookie = ?");
	$statement->bind_param('s', $cookie);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();

	if ($row == null) {
		$response['error'][0] = "Cound not find the user.";
		return $response;
	}

	include 'upgrades.php';
	$selected_upgrades_old = $row[0];
	if ($selected_upgrades_old == "") {
		$selected_upgrades = array();	
	} else {
		$selected_upgrades = explode(",", $selected_upgrades_old);
	}

	$taunts = get_store_products()['products']['Taunt'];
	foreach ($upgrades_to_select as $product_name) {
		select_taunt($taunts, $product_name, $selected_upgrades);
	}

	$selected_upgrades_new = implode(",", $selected_upgrades);

	$statement = $mysqli->prepare("UPDATE accounts SET selected_upgrades = ? WHERE cookie = ? AND selected_upgrades = ?");
	$statement->bind_param('sss', $selected_upgrades_new, $cookie, $selected_upgrades_old);
	$statement->execute();

	return array();
}

?>
