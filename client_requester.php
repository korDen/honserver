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
			verify_post_params(['aaa']);
			// yes, return.
			return;
		} else if ($f == "get_special_messages") {
			verify_post_params(['cookie']);
			$response = array();
		} else if ($f == "client_events_info") {
			verify_post_params(['cookie']);
			$response = array();
		} else if ($f == "get_products") {
			verify_post_params(['account_id', 'cookie', 'crc']);
			$response = array();
		} else if ($f == "claim_season_rewards") {
			verify_post_params(['cookie']);
			$response = array();
		} else if ($f == "show_stats") {
			verify_post_params(['cookie', 'nickname', 'table', 'f']);
			$response = array();
		} else if ($f == "show_simple_stats") {
			verify_post_params(['cookie', 'nickname']);
			$response = array();
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
			$response = array();
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
