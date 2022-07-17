<?php
	define("DEBUG_FILENAME", "client_requester.txt");
	include "utils.php";

	if (isset($_REQUEST["f"])) {
		// See if we support this type of request.
		$f = $_REQUEST["f"];
		if ($f == "auth") {
			verify_post_params(['login', 'password', 'dontClearCookies', 'OSType', 'MajorVersion', 'MinorVersion', 'MicroVersion', 'SysInfo']);

			include "modules/auth.php";
			$response = perform_login($_POST["login"], $_POST["password"]);
		} else if ($f == "server_list") {
			verify_post_params(['cookie', 'gametype'], ['region']);

			include "modules/server_list.php";
			$response = obtain_server_list($_POST['cookie']);
		} else if ($f == "get_upgrades") {
			verify_post_params(['cookie']);
			include 'modules/get_upgrades.php';
			$response = get_upgrades($_POST['cookie']);
		} else if ($f == "get_initStats") {
			verify_post_params(['cookie']);
			include 'modules/get_initStats.php';
			$response = get_init_stats($_POST['cookie']);
		} else if ($f == "get_special_messages") {
			verify_post_params(['cookie']);
			$response = array();
		} else if ($f == "client_events_info") {
			verify_post_params(['cookie']);
			$response = array();
		} else if ($f == "get_products") {
			verify_post_params(['account_id', 'cookie', 'crc']);
			include 'upgrades.php';
			$response = get_store_products();
		} else if ($f == "claim_season_rewards") {
			verify_post_params(['cookie']);
			$response = array();
		} else if ($f == "show_stats") {
			verify_post_params(['cookie', 'nickname', 'table', 'f']);
			$response = array();
		} else if ($f == "show_simple_stats") {
			verify_post_params(['cookie', 'nickname']);
			include 'modules/show_simple_stats.php';
			$response = get_simple_stats($_POST['nickname']);
		} else if ($f == "get_account_mastery") {
			verify_post_params(['cookie', 'f']);
			$response = array();
		} else if ($f == "logout") {
			verify_post_params(['cookie']);
			$response = array();
		} else if ($f == "get_daily_special") {
			verify_post_params(['account_id', 'f', 'cookie']);
			$response = array();
		} else if ($f == "grab_last_matches_from_nick") {
			verify_post_params(['nickname', 'f', 'hosttime']);
			$response = array();
		} else if ($f == "get_hero_usage_list") {
			verify_post_params(['cookie', 'f', 'sort']);
			$response = array();
		} else if ($f == "get_hero_stats") {
			verify_post_params(['hosttime', 'nickname', 'f', 'account_id', 'cookie', 'hero']);
			$response = array();
		} else if ($f == "get_campaign_hero_stats") {
			verify_post_params(['f', 'cookie', 'nickname', 'hero_name', 'is_casual']);
			$response = array();
		} else if ($f == "get_account_all_hero_stats") {
			verify_post_params(['cookie']);
			$response = array(
	            'all_hero_stats' => array(
	            	'ranked' => array(
	            	// Example of what it can contain:
	            	// 	0 => array(
	            	// 		'cli_name' => 'Hero_Valkyrie',
            		// 		'rnk_ph_used' => 20,
            		// 		'rnk_ph_wins' => 8,
            		// 		'rnk_ph_losses' => 12,
	            	// 	),
	            	),
	            	'casual' => array(),
	            	'campaign' => array(),
	            	'campaign_casual' => array(),
	            ),
			);
		} else if ($f == "get_match_stats") {
			verify_post_params(['match_id', 'cookie']);
			include 'modules/get_match_stats.php';
			$response = get_match_stats($_POST['match_id']);
		}
	}

	// debug logic.
	if (!isset($response)) {
		$response["error"][0] = "Unknown request";

		debug_log("GET: " . json_encode($_GET) . "\nPOST: " . json_encode($_POST) . "\n");
	}

	echo serialize($response);
?>
