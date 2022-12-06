<?php
/*
Plugin Name: LienConnexion
Version: 6
*/

if (!function_exists("add_action")) {
	echo "extension";
	exit();
}


add_filter("LienConnexion/configuration", function ($_) {
	
	return [
		"code_lien_connexion" => "lien_connexion",
		"longueur_cle_minimale" => 120,
		"temporisation_liaison_securisee" => 3, // en secondes
		"temps_activation_liaison_securisee" => 3 * MINUTE_IN_SECONDS,
		//"id_page_liaison_securisee" => ...,
	];
	
});


add_action("wp_loaded", function () {
	
	require "donnees/crochets/configuration.php";
	
	
	require "donnees/crochets/connexion.php";
	
	require "donnees/crochets/edition_utilisateur.php";
	
	require "donnees/crochets/liaison_securisee.php";
	
	
	
}, 2);


add_filter("LienConnexion/base_extension", function ($_) {
	return __DIR__;
});
add_filter("LienConnexion/url_extension", function ($_) {
	return plugins_url("", __FILE__);
});


add_filter("LienConnexion/version_extension", function ($_) {
	
	if (!isset($GLOBALS["LienConnexion"]["version_extension"])) {
		
		$data = get_file_data(__FILE__, ["version" => "Version"]);
		$GLOBALS["LienConnexion"]["version_extension"] = $data["version"];
		
	}
	
	
	return $GLOBALS["LienConnexion"]["version_extension"];
	
});


