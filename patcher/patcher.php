<?php

	$os = $_POST['os'];
	$arch = $_POST['arch'];
	$current_version = $_POST['current_version'];

    $response[0] = array(
		"version" => $current_version,
        "os" => $os,
        "arch" => $arch,
        "url" => "http://cdn.naeu.patch.heroesofnewerth.com/",
        "url2" => "http://cdn.naeu.patch.heroesofnewerth.com/",
        "latest_version" => "4.10.1",
    );
    $response["version"] = "4.10.1.0";
    $response["current_version"] = $current_version;

    echo serialize($response);
	
?>
