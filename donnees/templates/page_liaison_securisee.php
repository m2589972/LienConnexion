<?php
/*
Ce fichier s'occupe de l'affichage du shortcode [LienConnexion__page_liaison_securisee]
Cet affichage peut être personnalisé en copiant le fichier dans le thème dans le répertoire suivant : 
wp-content/themes/CODE_THEME/LienConnexion/templates/
*/


/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


?>
<div class="page_liaison_securisee">
	
	<div class="identifiant">
		
		Identifiant de la liaison&nbsp;:
		 <?php echo htmlspecialchars($d["identifiant"]);?>
		
	</div>
	<div class="message">
		
		<?php if (FALSE === $d["url_connexion"]) {?>
			
			Indiquez cet identifiant à votre interlocuteur.
			
		<?php } else {?>
			
			L'URL de connexion est le suivant, vous pouvez le mettre dans les marque-pages&nbsp;:<br/>
			<a href="<?php echo htmlspecialchars($d["url_connexion"]);?>">
				<?php echo htmlspecialchars($d["url_connexion"]);?></a>
			
		<?php }?>
		
	</div>
	
</div>


