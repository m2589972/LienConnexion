<?php
/*
Ce fichier s'occupe de l'affichage du shortcode [LienConnexion__lien_vers_liaison_securisee]
Cet affichage peut être personnalisé en copiant le fichier dans le thème dans le répertoire suivant : 
wp-content/themes/CODE_THEME/LienConnexion/templates/
*/


/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


?>
<span class="conteneur_lien_vers_liaison_securisee">
	<a href="<?php echo htmlspecialchars($d["lien_liaison_securisee"]);?>">
		Liaison sécurisée</a>
</span>


