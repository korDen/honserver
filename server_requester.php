<?php
	define("DEBUG_FILENAME", "server_requester.txt");
	include "utils.php";

	if (isset($_REQUEST["f"])) {
		// See if we support this type of request.
		$f = $_REQUEST["f"];
		if ($f == "start_game") {
			verify_post_params(['session', 'code', 'extra', 'map', 'version', 'mname', 'mstr', 'casual', 'arrangedmatchtype', 'match_mode', 'accounts']);
			include "modules/start_game.php";
			$response = start_game();
		} else if ($f == "new_session") {
			verify_post_params(['login', 'pass', 'port', 'name', 'desc', 'location', 'ip']);
			include 'modules/new_session.php';
			$response = create_game_session();
		} else if ($f == "accept_key") {
			verify_post_params(['session', 'acc_key']);
			// This doesn't seem to be used in any way.
			$response['server_id'] = 0;
		} else if ($f == "c_conn") {
			verify_post_params(['session', 'cookie', 'ip', 'cas', 'new']);
			include "modules/c_conn.php";
			$response = perform_auth($_POST['cookie']);
		} else if ($f == "get_quickstats") {
			verify_post_params(['ranked', 'casual', 'session', 'account_id']);
			$response = array();
		}
	}

	// debug logic.
	if (!isset($response)) {
		$response["error"][0] = "Unknown request";

		debug_log("GET: " . json_encode($_GET) . "\nPOST: " . json_encode($_POST) . "\n");
	}

	echo serialize($response);
?>
