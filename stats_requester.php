<?php
	define("DEBUG_FILENAME", "stats_requester.txt");
	include "utils.php";

	if (isset($_REQUEST["f"])) {
		// See if we support this type of request.
		$f = $_REQUEST["f"];
		if ($f == "submit_stats") {
			verify_post_params(['f', 'session', 'match_stats', 'team_stats', 'player_stats', 'inventory']);
			include "modules/submit_stats.php";
			$response = submit_stats($_POST);
		}
	}

	// debug logic.
	if (!isset($response)) {
		$response["error"][0] = "Unknown request";

		debug_log("GET: " . json_encode($_GET) . "\nPOST: " . json_encode($_POST) . "\n");
	}

	echo serialize($response);
?>
