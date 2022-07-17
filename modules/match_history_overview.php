<?php

function match_history_overview($nickname, $num) {
	// TODO: implement properly.
	for ($i = 0; $i < $num; $i++) {
		// match id, result(0 = loss, 1 = win), team (unused?), kills, deaths, assists, heroid, duration, mapname, mdt, heroname
		$response["m".$i] = '12314,1,unused,31,21,17,12,32,caldavar,1/1/2022 9AM UTC,Hero_Frosty';
	}

	return $response;
}

?>
