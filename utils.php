<?php

	header("Content-Encoding: none");
	define("DEBUG", true);

	function debug_log($text) {
		if (DEBUG) {
			file_put_contents(DEBUG_FILENAME, $text, FILE_APPEND | LOCK_EX);
		}
	}

	function verify_post_params($reqired_keys, $optional_keys = []) {
		$all_keys = array_keys($_POST);
		foreach ($reqired_keys as $key) {
			if (($index = array_search($key, $all_keys)) !== false) {
				unset($all_keys[$index]);
			} else {
				debug_log("required key '" . $key . "' not found in all_keys: " . json_encode($all_keys) . ", POST: '" . json_encode($_POST) . ", GET: " . json_encode($_GET) . "\n");
			}
		}

		foreach ($optional_keys as $key) {
			if (($index = array_search($key, $all_keys)) !== false) {
				unset($all_keys[$index]);
			}
		}

		if (!empty($all_keys)) {
			debug_log("unchecked keys are found: " . json_encode($all_keys) . " in POST: '" . json_encode($_POST) . ", GET: " . json_encode($_GET) . "\n");
		}
	}

?>
