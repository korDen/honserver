<?php
	define("DEBUG_FILENAME", "server_requester.txt");
	include "utils.php";

	if (isset($_REQUEST["f"])) {
		// See if we support this type of request.
		$f = $_REQUEST["f"];
		if ($f == "start_game") {
			verify_post_params(['session', 'code', 'extra', 'map', 'version', 'mname', 'mstr', 'casual', 'arrangedmatchtype', 'match_mode', 'accounts']);
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
