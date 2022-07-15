<?php

function create_game_session() {
	$response['session'] = generate_random_hash();
	return $response;
}

?>
