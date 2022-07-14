<?php
	header("Content-Encoding: none");
	define("DEBUG", true);

	if (isset($_REQUEST["f"])) {
		// See if we support this type of request.
		$f = $_REQUEST["f"];
		if ($f == "auth") {
			if (isset($_POST["login"]) && isset($_POST["password"])) {
				include "modules/auth.php";
				$response = perform_auth($_POST["login"], $_POST["password"]);
			}
		} else if ($f == "get_special_messages") {
			$response = array();
		} else if ($f == "client_events_info") {
			$response = array();
		} else if ($f == "get_products") {
			$response = array();
		} else if ($f == "get_upgrades") {
			// yes, return.
			return;
		} else if ($f == "claim_season_rewards") {
			$response = array();
		} else if ($f == "show_stats") {
			$response = array();
		} else if ($f == "show_simple_stats") {
			$response = array();
		} else if ($f == "server_list") {
			include "modules/server_list.php";
			$response = obtain_server_list();
		} else if ($f == "logout") {
			$response = array();
		}
	}

	// debug logic.
	if (!isset($response)) {
		$response["error"][0] = "Unknown request";

		if (DEBUG) {
			file_put_contents("get.txt", json_encode($_GET));
			file_put_contents("post.txt", json_encode($_POST));
			file_put_contents("request.txt", json_encode($_REQUEST));
		}
	}

	echo serialize($response);
?>