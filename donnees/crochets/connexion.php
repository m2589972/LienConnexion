<?php

add_action("admin_init", function () {
	
	
	$configuration = apply_filters("LienConnexion/configuration", NULL);
	
	
	if (	!isset($_GET["action"])
		||	!isset($_GET["cle"])
		||	($configuration["code_lien_connexion"] !== $_GET["action"])
	) {
		return;
	}
	
	
	$cle = trim($_GET["cle"]);
	
	$args = [
		"meta_key" => "lien_connexion__cle",
		"meta_value" => $cle,
	];
	
	$wp_user_search = new \WP_User_Query($args);
	$items = $wp_user_search->get_results();
	
	
	if (	0 === count($items)
		||	(mb_strlen($cle) < $configuration["longueur_cle_minimale"])
	) {
		// clé invalide
		
		$temps = mt_rand(15, 49);
		sleep($temps);
		
		exit();
	}
	
	// protection contre les attaques par force brute
	sleep(2);
	
	
	$utilisateur = array_shift($items);
	
	if (!apply_filters("LienConnexion/autoriser_utilisateur", $utilisateur->ID, TRUE)) {
		return;
	}
	
	
	// connexion
	
	wp_set_auth_cookie(
		  $utilisateur->ID
		, TRUE // $remember
	);
	
	
	// redirection vers l'accueil de l'espace d'administration
	
	$url = admin_url("/");
	$url = apply_filters("LienConnexion/url_apres_connexion", $url, $utilisateur);
	
	wp_redirect($url);
	
	
	exit();
	
}); // FIN add_action("admin_init", function () {


