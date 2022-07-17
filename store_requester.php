<?php
	define("DEBUG_FILENAME", "store_requester.txt");
	include "utils.php";

	$request_code = $_POST['request_code'];

	if (isset($_POST['category_id'])) {
		$category_id = $_POST['category_id'];
	}

	if ($request_code == 7) { // MSTORE_RESPONSE_REFRESH_SELECTED_ITEMS
		verify_post_params(['account_id', 'request_code', 'cookie', 'hostTime', 'bb']);
		$response = array();
	} else if ($request_code == 8) { // buy avatar.
		verify_post_params(['account_id', 'request_code', 'cookie', 'hero_name', 'avatar_code', 'currency', 'type', 'discount']);
		include "upgrades.php";

		$type = $_POST['type'];
		$hero_name = $_POST['hero_name'];
		$avatar_code = $_POST['avatar_code'];
		$product_name = $hero_name.".".$avatar_code;

		$store_products = get_store_products();
		foreach ($store_products['products'][$type] as $key=>$product) {
			if ($product['name'] == $product_name) {
            	return buy_product($request_code, $product, $_POST['cookie'], $_POST['currency'], "aa");
			}
		}
	} else if ($request_code == 4) { // buy product by id.
		verify_post_params(['account_id', 'request_code', 'cookie', 'product_id', 'category_id', 'page', 'hostTime', 'currency', 'displayAll', 'notPurchasable', 'discount', 'bb']);
		include "upgrades.php";

		$product_id = $_POST['product_id'];
		$product = get_product_by_id($product_id);
		$response = buy_product($request_code, $product, $_POST['cookie'], $_POST['currency'], "t");
		$response["requestHostTime"] = $_POST['hostTime'];
		$response["tauntUnlocked"] = '1';
	} else if ($request_code == 1 && $category_id == 27) { // open vault.
		verify_post_params(['account_id', 'category_id', 'request_code', 'page', 'cookie', 'hostTime', 'displayAll', 'notPurchasable', 'bb']);

		// Taunts.
		include 'keys.php';
		include 'upgrades.php';

		$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);
		$statement = $mysqli->prepare("SELECT upgrades, points, mmpoints FROM accounts WHERE cookie = ?");
		$statement->bind_param('s', $_POST['cookie']);

		$statement->execute();
		$result = $statement->get_result();
		$row = $result->fetch_row();
		if ($row == null) {
			$response['responseCode'] = $request_code;
			$response['error'] = '';
			$response['popupCode'] = 0;
			$response['errorCode'] = 3;
			echo serialize($response);
			return;
		}

		$upgrades = unserialize($row[0]);
		$all_products = get_store_products()['products']['Taunt'];

		$page = $_POST['page'];
		$products = array_slice($all_products, ($page - 1) * 12, null, true);
		if (count($products) > 12) {
			$products = array_slice($products, 0, 12, true);
		}

		$response["productPrices"] = "";
		$response["productNames"] = "";
		$response["productIDs"] = "";
		$response["productAlreadyOwned"] = "";
		$response["productIsBundle"] = "";
		$response["productQuantity"] = "";
		$response["productWebContent"] = "";
		$response["specialBundles"] = "";
		$response["productCharges"] = "";
		$response["productDurations"] = "";
		$response["productTimes"] = "";
		$response["purchasable"] = "";
		$response["productPremium"] = "";
		$response["premium_mmp_cost"] = "";
		$response["productCodes"] = "";
		$response["productLocalContent"] = "";
		$response["productStats"] = "";
		$response["productEnhancements"] = "";
		$response["productEnhancementIDs"] = "";
		$response["chargesRemaining"] = "";
		$response["durationsRemaining"] = "";
		$response["productDescription"] = "";

		foreach ($products as $product_id=>$product) {
			$response["productPrices"] .= "|" . $product['cost'];
			$response["productNames"] .= "|" . $product['cname'];
			$response["productIDs"] .= "|" . $product_id;
			$response["productAlreadyOwned"] .= "|" . intval(in_array("t.".$product['name'], $upgrades));
			$response["productIsBundle"] .= "|0";
			$response["productQuantity"] .= "|-1";
			$response["productWebContent"] .= "|-1";
			$response["specialBundles"] .= "|-1~-1~-1~-1~-1";
			$response["productCharges"] .= "|-1~-1~-1~-1~-1~-1";
			$response["productDurations"] .= "|-1~-1~-1~-1~-1~-1~-1";
			$response["productTimes"] .= "|-1,-1";
			$response["purchasable"] .= "|" . $product['purchasable'];
			$response["productPremium"] .= "|" . $product['premium'];
			$response["premium_mmp_cost"] .= "|".$product['premium_mmp_cost'];
			$response["productCodes"] .= "|t." . $product['name'];
			$response["productLocalContent"] .= "|".$product['content'];
			$response["productStats"] .= "|-1~~0~0";
			$response["productEnhancements"] .= "|";
			$response["productEnhancementIDs"] .= "|";
			$response["chargesRemaining"] .= "|-1~-1~-1";
			$response["durationsRemaining"] .= "|-1";
			$response["productDescription"] .= "|";
		}

		$response["productPrices"] = substr($response["productPrices"], 1);
		$response["productNames"] = substr($response["productNames"], 1);
		$response["productIDs"] = substr($response["productIDs"], 1);
		$response["productAlreadyOwned"] = substr($response["productAlreadyOwned"], 1);
		$response["productIsBundle"] = substr($response["productIsBundle"], 1);
		$response["productQuantity"] = substr($response["productQuantity"], 1);
		$response["productWebContent"] = substr($response["productWebContent"], 1);
		$response["specialBundles"] = substr($response["specialBundles"], 1);
		$response["productCharges"] = substr($response["productCharges"], 1);
		$response["productDurations"] = substr($response["productDurations"], 1);
		$response["productTimes"] = substr($response["productTimes"], 1);
		$response["purchasable"] = substr($response["purchasable"], 1);
		$response["productPremium"] = substr($response["productPremium"], 1);
		$response["premium_mmp_cost"] = substr($response["premium_mmp_cost"], 1);
		$response["productCodes"] = substr($response["productCodes"], 1);
		$response["productLocalContent"] = substr($response["productLocalContent"], 1);
		$response["productStats"] = substr($response["productStats"], 1);
		$response["productEnhancements"] = substr($response["productEnhancements"], 1);
		$response["productEnhancementIDs"] = substr($response["productEnhancementIDs"], 1);
		$response["chargesRemaining"] = substr($response["chargesRemaining"], 1);
		$response["durationsRemaining"] = substr($response["durationsRemaining"], 1);
		$response["productDescription"] = substr($response["productDescription"], 1);

		$response["responseCode"] = $request_code;
	    $response["popupCode"] = -1;
	    $response["totalPages"] = (count($all_products) + 11) / 12;
	    $response["totalPoints"] = $row[1];
	    $response["totalMMPoints"] = $row[2];
	    $response["categoryID"] = 27;
	    $response["currentPage"] = $page;
	    $response["errorCode"] = 0;
	    $response["bundleContents"] = "";
	    $response["selectedUpgrades"] = "";
	    $response["requestHostTime"] = $_POST['hostTime'];
	    $response["tauntUnlocked"] = intval(in_array("t.Standard", $upgrades));
	    $response["tauntUnlockCost"] = get_product_by_id(91)['cost'];
	    $response["tauntUnlockCostMMP"] = get_product_by_id(91)['premium_mmp_cost'];
	    $response["customAccountIcon"] = 0;
	    $response["customAccountIconCost"] = 350;
	    $response["customAccountIconCostMMP"] = 1000;
	    $response["timestamp"] = "1649577218";
	    $response["accountIconsUnlocked"] = 1;
	    $response["gameTokens"] = 0;
	    $response["gamePasses"] = 0;
	    // $response["productEligibility"] = "...";
	    $response["vaultHighlight"] = "NULL";
	    $response["santas"] = "4";
	    $response["santa_event_expiration"] = "2072-06-22 10:15:59";
	} else if ($request_code == 0 || $request_code == 1 || $request_code == 2) {
		include 'keys.php';
		include 'upgrades.php';

    	$cookie = $_POST['cookie'];
    	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);
		$statement = $mysqli->prepare("SELECT upgrades, selected_upgrades, points, mmpoints FROM accounts WHERE cookie = ?");
		$statement->bind_param('s', $cookie);

		$statement->execute();
		$result = $statement->get_result();
		$row = $result->fetch_row();
		if ($row == null) {
			$response['responseCode'] = $request_code;
			$response['popupCode'] = 0;
			$response['errorCode'] = 2;
			$response['error'] = 'User not found.';
			echo serialize($response);
			return;
		}

		// view store?
		$category_id = $_POST['category_id'];
		$page = $_POST['page'];
		$hostTime = $_POST['hostTime'];
		$displayAll = $_POST['displayAll'];
		$notPurchasable = $_POST['notPurchasable'];
		$bb = $_POST['bb'];

		$upgrades = unserialize($row[0]);
		$response["accountIconsUnlocked"] = 1;
		$response["bundleAlreadyOwned"] = '';
		$response["bundleContents"] = '';
		$response["bundleCosts"] = '';
		$response["bundleIDs"] = '';
		$response["bundleIncludedProducts"] = '';
		$response["bundleLocalPaths"] = '';
		$response["bundleNames"] = '';
		$response["categoryID"] = $category_id;
		$response["chargesRemaining"] = '';
		$response["currentPage"] = '';
		$response["customAccountIcon"] = 0;
		$response["customAccountIconCost"] = 350;
		$response["customAccountIconCostMMP"] = 1000;
		$response["errorCode"] = 0;
		$response["grabBag"] = '';
		$response["grabBagIDs"] = '';
		$response["grabBagLocalPaths"] = '';
		$response["grabBagProductNames"] = '';
		$response["grabBagTheme"] = '';
		$response["grabBagTypes"] = '';
		$response["is_newly_verified"] = 0;
		$response["packageCurrencyToCoins"] = '';
		$response["packageIDs"] = '';
		$response["packagePoints"] = '';
		$response["packagePrices"] = '';
		$response["packageSpecial"] = '';
		$response["packageTextures"] = '';
		$response["popupCode"] = 0;
		$response["premium_mmp_cost"] = '';
		$response["productAlreadyOwned"] = '';
		$response["productCharges"] = '';
		$response["productCodes"] = '';
		$response["productDescription"] = '';
		$response["productDurations"] = '';
		$response["productEligibility"] = '';
		$response["productEnhancementIDs"] = '';
		$response["productEnhancements"] = '';
		$response["productIDs"] = '';
		$response["productIsBundle"] = '';
		$response["productLocalContent"] = '';
		$response["productNames"] = '';
		$response["productPremium"] = '';
		$response["productPrices"] = '';
		$response["productQuantity"] = '';
		$response["productTimes"] = '';
		$response["productWebContent"] = '';
		$response["product_id"] = '';
		$response["promoCode"] = '';
		$response["purchasable"] = '';
		$response["regional_currency"] = '';
		$response["requestHostTime"] = $_POST['hostTime'];
		$response["responseCode"] = $request_code;
		$response["santa_event_expiration"] = '';
		$response["santas"] = 0;
		$response["selectedUpgrades"] = $row[1];
		$response["specialBundles"] = '';
		$response["specialDisplay"] = '';
		$response["tauntUnlockCost"] = get_product_by_id(91)['cost'];
	    $response["tauntUnlockCostMMP"] = get_product_by_id(91)['premium_mmp_cost'];
		$response["tauntUnlocked"] = intval(in_array("t.Standard", $upgrades));
		$response["timestamp"] = '';
		$response["totalPoints"] = $row[2];
		$response["totalMMPoints"] = $row[3];
		$response["totalPages"] = 1; // ??
		$response["unlockAccountIconsCost"] = 280;
		$response["unlockAccountIconsCostMMP"] = 350;
		$response["vaultCategory16"] = '';
		$response["vaultCategory2"] = '';

		$all_products = get_store_products()['products']['Taunt'];
		$vaultCategory27 = "";
		foreach ($all_products as $product_id=>$product) {
			$product_code = 't.'.$product['name'];
			if (in_array($product_code, $upgrades)) {
				$vaultCategory27 .= "|".$product_id.'`'.$product_code.'`'.$product['content'].'`'.$product['cname'];
			}
		}
		if ($vaultCategory27 != "") {
			$vaultCategory27 = substr($vaultCategory27, 1);	
		}

		$response["vaultCategory27"] = $vaultCategory27;
		$response["vaultCategory3"] = '';
		$response["vaultCategory4"] = '';
		$response["vaultCategory5"] = '';
		$response["vaultCategory56"] = '';
		$response["vaultCategory57"] = '';
		$response["vaultCategory6"] = '';
		$response["vaultCategory72"] = '';
		$response["vaultCategory74"] = '';
		$response["vaultCategory75"] = '';
		$response["vaultCategory76"] = '';
		$response["vaultCategory77"] = '';
		$response["vaultCategory78"] = '';
		$response["vaultCategory79"] = '';
		$response["vaultHighlight"] = 0;
	}

	// debug logic.
	if (!isset($response)) {
		$response["error"][0] = "Unknown request";

		debug_log("GET: " . json_encode($_GET) . "\nPOST: " . json_encode($_POST) . "\n");
	}

	echo serialize($response);
?>
