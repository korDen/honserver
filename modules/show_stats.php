<?php

function show_stats($nickname, $table) {
	include 'keys.php';

	$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);

	$statement = $mysqli->prepare("SELECT account_id, selected_upgrades FROM accounts WHERE login = ?");
	$statement->bind_param('s', $nickname);

	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_row();
	if ($row == null) {
		return array();
	}

	if ($table == "mastery") {
		$response["account_id"] = $row[0];
		$response["create_date"] = 'n/a';
		$response["last_activity"] = 'n/a';
		$response["level"] = 1;
		$response["level_exp"] = 0;
		$response["mastery_info"] = "";
		$response["mastery_rewards"] = "";
		$response["name"] = "CLAN";
		$response["nickname"] = $nickname;
		$response["rank"] = 0;
		$response["selected_upgrades"] = $row[1];
		$response["standing"] = 3;
	} else if ($table == "campaign") {
		$response["acc_discos"] = 0;
		$response["acc_games_played"] = 0;
		$response["account_id"] = $row[0];
		$response["account_type"] = 4;
		$response["avgActions_min"] = 0;
		$response["avgCreepKills"] = 0;
		$response["avgDenies"] = 0;
		$response["avgGameLength"] = 0;
		$response["avgNeutralKills"] = 0;
		$response["avgWardsUsed"] = 0;
		$response["avgXP_min"] = 0;
		$response["cam_actions"] = 0;
		$response["cam_amm_solo_count"] = 0;
		$response["cam_amm_solo_rating"] = 0;
		$response["cam_amm_team_count"] = 0;
		$response["cam_amm_team_rating"] = 0;
		$response["cam_annihilation"] = 0;
		$response["cam_bdmg"] = 0;
		$response["cam_bdmgexp"] = 0;
		$response["cam_bgold"] = 0;
		$response["cam_bloodlust"] = 0;
		$response["cam_buybacks"] = 0;
		$response["cam_concedes"] = 0;
		$response["cam_concedevotes"] = 0;
		$response["cam_consumables"] = 0;
		$response["cam_deaths"] = 0;
		$response["cam_denies"] = 0;
		$response["cam_doublekill"] = 0;
		$response["cam_em_played"] = 0;
		$response["cam_exp"] = 0;
		$response["cam_exp_denied"] = 0;
		$response["cam_gold"] = 0;
		$response["cam_gold_spend"] = 0;
		$response["cam_goldlost2death"] = 0;
		$response["cam_heroassists"] = 0;
		$response["cam_herodmg"] = 0;
		$response["cam_heroexp"] = 0;
		$response["cam_herokills"] = 0;
		$response["cam_herokillsgold"] = 0;
		$response["cam_humiliation"] = 0;
		$response["cam_kicked"] = 0;
		$response["cam_ks10"] = 0;
		$response["cam_ks15"] = 0;
		$response["cam_ks3"] = 0;
		$response["cam_ks4"] = 0;
		$response["cam_ks5"] = 0;
		$response["cam_ks6"] = 0;
		$response["cam_ks7"] = 0;
		$response["cam_ks8"] = 0;
		$response["cam_ks9"] = 0;
		$response["cam_level"] = 0;
		$response["cam_losses"] = 0;
		$response["cam_nemesis"] = 0;
		$response["cam_neutralcreepdmg"] = 0;
		$response["cam_neutralcreepexp"] = 0;
		$response["cam_neutralcreepgold"] = 0;
		$response["cam_neutralcreepkills"] = 0;
		$response["cam_pub_count"] = 0;
		$response["cam_quadkill"] = 0;
		$response["cam_razed"] = 0;
		$response["cam_retribution"] = 0;
		$response["cam_secs"] = 0;
		$response["cam_secs_dead"] = 0;
		$response["cam_smackdown"] = 0;
		$response["cam_teamcreepdmg"] = 0;
		$response["cam_teamcreepexp"] = 0;
		$response["cam_teamcreepgold"] = 0;
		$response["cam_teamcreepkills"] = 0;
		$response["cam_time_earning_exp"] = 0;
		$response["cam_triplekill"] = 0;
		$response["cam_wards"] = 0;
		$response["cam_wins"] = 0;
		$response["con_reward"] = ''; // 9 comma-separated values.
		$response["create_date"] = 'n/a';
		$response["cs_discos"] = 0;
		$response["cs_games_played"] = 0;
		$response["curr_season_cam_cs_discos"] = 0;
		$response["curr_season_cam_cs_games_played"] = 0;
		$response["curr_season_cam_discos"] = 0;
		$response["curr_season_cam_games_played"] = 0;
		$response["current_level"] = 0;
		$response["current_ranking"] = 0;
		$response["discos"] = 0;
		$response["error"] = 0;
		$response["event_id"] = 0;
		$response["events"] = 0;
		$response["favHero1"] = 0;
		$response["favHero1Time"] = 0;
		$response["favHero1_2"] = 0;
		$response["favHero1id"] = 0;
		$response["favHero2"] = 0;
		$response["favHero2Time"] = 0;
		$response["favHero2_2"] = 0;
		$response["favHero2id"] = 0;
		$response["favHero3"] = 0;
		$response["favHero3Time"] = 0;
		$response["favHero3_2"] = 0;
		$response["favHero3id"] = 0;
		$response["favHero4"] = 0;
		$response["favHero4Time"] = 0;
		$response["favHero4_2"] = 0;
		$response["favHero4id"] = 0;
		$response["favHero5"] = 0;
		$response["favHero5Time"] = 0;
		$response["favHero5_2"] = 0;
		$response["favHero5id"] = 0;
		$response["games_played"] = 0;
		$response["highest_level_current"] = 0;
		$response["highest_ranking"] = 0;
		$response["k_d_a"] = 0;
		$response["last_activity"] = 'n/a';
		$response["level"] = 1;
		$response["level_exp"] = 0;
		$response["level_percent"] = 0;
		$response["matchDates"] = 0;
		$response["matchIds"] = 0;
		$response["maxXP"] = 0;
		$response["max_exp"] = 0;
		$response["mid_discos"] = 0;
		$response["mid_games_played"] = 0;
		$response["min_exp"] = 0;
		$response["name"] = "";
		$response["nickname"] = $nickname;
		$response["percentEM"] = 0;
		$response["possible_discos"] = 0;
		$response["prev_seasons_cam_cs_discos"] = 0;
		$response["prev_seasons_cam_cs_games_played"] = 0;
		$response["prev_seasons_cam_discos"] = 0;
		$response["prev_seasons_cam_games_played"] = 0;
		$response["rank"] = 100;
		$response["rift_discos"] = 0;
		$response["rift_games_played"] = 0;
		$response["rnk_avg_score"] = 0;
		$response["rnk_discos"] = 0;
		$response["rnk_games_played"] = 0;
		$response["season_id"] = 8;
		$response["selected_upgrades"] = $row[1];
		$response["smr"] = 0;
		$response["standing"] = 3;
		$response["total_discos"] = 0;
		$response["total_games_played"] = 0;
		$response["total_level_exp"] = 0;
		$response["uncs_discos"] = 0;
		$response["uncs_games_played"] = 0;
		$response["unrnk_discos"] = 0;
		$response["unrnk_games_played"] = 0;
		$response["xp2nextLevel"] = 0;
		$response["xpPercent"] = 0;
	} else {
		// Unknown table.
		return array();
	}

	return $response;
}

?>
