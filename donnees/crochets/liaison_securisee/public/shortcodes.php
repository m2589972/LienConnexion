<?php

add_action("template_redirect", function () {
	
	
	$configuration = apply_filters("LienConnexion/configuration", NULL);
	
	if (!isset($configuration["id_page_liaison_securisee"])) {
		return "";
	}
	
	
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
	
});


add_shortcode("LienConnexion__lien_vers_liaison_securisee", function ($attr, $content, $tag) {
	
	
	$configuration = apply_filters("LienConnexion/configuration", NULL);
	
	if (!isset($configuration["id_page_liaison_securisee"])) {
		return "";
	}
	
	
	// temporisation
	
	if (!isset($_SESSION["liaison_securisee"])) {
		$_SESSION["liaison_securisee"] = time()
			 + $configuration["temporisation_liaison_securisee"];
	}
	
	if (	$_SESSION["liaison_securisee"] > time()
		||	(FALSE === get_transient("LienConnexion__ouverture_liaison_securisee"))
	) {
		return "";
	}
	
	
	// affichage
	
	$page = get_post($configuration["id_page_liaison_securisee"]);
	
	do_action("LienConnexion/donnees_template", [
		"lien_liaison_securisee" => get_permalink($page),
	]);
	
	
	ob_start();
		do_action("LienConnexion/template", "lien_vers_liaison_securisee");
	return ob_get_clean();
	
});


add_shortcode("LienConnexion__page_liaison_securisee", function ($attr, $content, $tag) {
	
	
	$configuration = apply_filters("LienConnexion/configuration", NULL);
	
	if (!isset($configuration["id_page_liaison_securisee"])) {
		return "";
	}
	
	
	// temporisation
	
	if (!isset($_SESSION["liaison_securisee"])) {
		$_SESSION["liaison_securisee"] = time()
			 + $configuration["temporisation_liaison_securisee"];
	}
	
	if (	$_SESSION["liaison_securisee"] > time()
		||	(FALSE === get_transient("LienConnexion__ouverture_liaison_securisee"))
	) {
		return "";
	}
	
	
	// identifiant de la session
	
	if (!isset($_SESSION["identifiant"])) {
		
		$chaine = hash("whirlpool", microtime() . wp_get_session_token(), TRUE);
		$chaine = strtoupper(base64_encode($chaine)) . mt_rand("1000000111", "9800123999");
		
		preg_match_all("/[2345678]/", $chaine, $chiffres);
		preg_match_all("/[abcdefghjnrstvyz]/i", $chaine, $lettres);
		
		$chiffres = $chiffres[0];
		$lettres = $lettres[0];
		
		
		$_SESSION["identifiant"] = "$lettres[1]$chiffres[0]$lettres[0]$chiffres[1]";
		
	}
	
	$identifiant = $_SESSION["identifiant"];
	
	
	// création de la liaison
	
	$liaisons = get_transient("LienConnexion__liaisons_securisees");
	
	if (FALSE === $liaisons) {
		$liaisons = [];
	}
	
	if (!isset($liaisons[$identifiant])) {
		$liaisons[$identifiant] = TRUE;
	}
	
	set_transient(
		  "LienConnexion__liaisons_securisees"
		, $liaisons
		, $configuration["temps_activation_liaison_securisee"]
	);
	
	
	// affichage
	
	$url_extension = apply_filters("LienConnexion/url_extension", NULL);
	$version_extension = apply_filters("LienConnexion/version_extension", NULL);
	
	wp_enqueue_style(
		  "LienConnexion__affichage"
		, "$url_extension/liens/css/public/page_liaison_securisee.css"
		, []
		, $version_extension
	);
	
	
	do_action("LienConnexion/donnees_template", [
		"identifiant" => $identifiant,
		"url_connexion" => get_transient("LienConnexion__liaison__$identifiant"),
	]);
	
	
	ob_start();
		do_action("LienConnexion/template", "page_liaison_securisee");
	return ob_get_clean();
	
});


