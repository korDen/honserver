<?php
	define("DEBUG_FILENAME", "store_requester.txt");
	include "utils.php";

	$request_code = $_POST['request_code'];
	if ($request_code == 8) { // buy avatar.
		verify_post_params(['account_id', 'request_code', 'cookie', 'hero_name', 'avatar_code', 'currency', 'type', 'discount']);
		include "upgrades.php";

		$type = $_POST['type'];
		$hero_name = $_POST['hero_name'];
		$avatar_code = $_POST['avatar_code'];
		$product_name = $hero_name.".".$avatar_code;

		$store_products = get_store_products();
		foreach ($store_products['products'][$type] as $key=>$product) {
			if ($product['name'] == $product_name) {
            	if (!$product['purchasable']) {
            		// cannot purchase this item.
					$response['responseCode'] = 8;
					$response['popupCode'] = 0;
					$response['errorCode'] = 1;
					$response['error'] = 'This item is not purchasable.';
					break;
            	}

            	include 'keys.php';

            	$cookie = $_POST['cookie'];
            	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);
				$statement = $mysqli->prepare("SELECT upgrades, points, mmpoints FROM accounts WHERE cookie = ?");
				$statement->bind_param('s', $cookie);

				$statement->execute();
				$result = $statement->get_result();
				$row = $result->fetch_row();
				if ($row == null) {
					// could not find this user.
					$response['responseCode'] = 8;
					$response['popupCode'] = 0;
					$response['errorCode'] = 2;
					$response['error'] = 'User not found.';
					break;
				}

				$upgrades_old = $row[0];
				$upgrades = unserialize($upgrades_old);
				$upgrade_name = "aa.".$product_name;
				if (in_array($upgrade_name, $upgrades)) {
					$response['responseCode'] = 8;
					$response['popupCode'] = 0;
					$response['errorCode'] = 3;
					$response['error'] = 'User already owns this product.';
					break;
				}

            	$currency = $_POST['currency'];
            	$points_old = $row[1];
            	$mmpoints_old = $row[2];
            	if ($currency == 0) {
            		// gold.
            		$cost = $product['cost'];
            		if ($points_old < $cost) {
            			$response['responseCode'] = 8;
						$response['popupCode'] = 0;
						$response['errorCode'] = 4;
						$response['error'] = 'Not enough gold coins.';
            			break;
            		}

            		$points_new = $points_old - $cost;
            		$mmpoints_new = $mmpoints_old;
            	} else {
            		// assume silver.
            		$cost = $product['premium_mmp_cost'];
            		if ($mmpoints_old < $cost) {
            			$response['responseCode'] = 8;
						$response['popupCode'] = 0;
						$response['errorCode'] = 5;
						$response['error'] = 'Not enough silver coins.';
            			break;
            		}

            		$points_new = $points_old;
            		$mmpoints_new = $mmpoints_old - $cost;
            	}

            	array_push($upgrades, $upgrade_name);
            	$upgrades_new = serialize($upgrades);

            	$statement = $mysqli->prepare("UPDATE accounts SET points = ?, mmpoints = ?, upgrades = ? WHERE cookie = ? AND points = ? AND mmpoints = ? AND upgrades = ?");
				$statement->bind_param('iissiis', $points_new, $mmpoints_new, $upgrades_new, $cookie, $points_old, $mmpoints_old, $upgrades_old);
				$statement->execute();

				$response['responseCode'] = 8;
				$response['popupCode'] = 3;
				$response['errorCode'] = 0;
				$response['error'] = '';
				break;
			}
		}
	}

	// debug logic.
	if (!isset($response)) {
		$response["error"][0] = "Unknown request";

		debug_log("GET: " . json_encode($_GET) . "\nPOST: " . json_encode($_POST) . "\n");
	}

	echo serialize($response);
?>
