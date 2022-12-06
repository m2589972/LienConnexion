<?php

add_action("wp_loaded", function () {
	
	$GLOBALS["LienConnexion"]["donnees_template"] = [];
	
});


add_action("LienConnexion/donnees_template", function ($donnees_template) {
	
	foreach ($donnees_template as $cle => $valeur) {
		$GLOBALS["LienConnexion"]["donnees_template"][$cle] = $valeur;
	}
	
});


add_action("LienConnexion/template", function ($code_template) {
	
	// recherche dans les templates du thème enfant ou du thème parent
	$template = locate_template("LienConnexion/templates/$code_template.php");
	
	// s'il n'existe pas dans le thème
	if ("" === $template) {
		
		$base_extension = apply_filters("LienConnexion/base_extension", NULL);
		
		// recherche dans les templates de l'extension
		$template = "$base_extension/donnees/templates/$code_template.php";
		
	}
	
	// données du template
	$d = $GLOBALS["LienConnexion"]["donnees_template"];
	
	
	require $template;
	
}, 10, 2);


