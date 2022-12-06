<?php

add_action("admin_init", function () {
	
	if (	!isset($_GET["action"])
		||	("LienConnexion__liaison_securisee__validation" !== $_GET["action"])
		||	!isset($_POST["id_utilisateur"])
	) {
		return;
	}
	
	
	if (!current_user_can("edit_user", $_POST["id_utilisateur"])) {
		return;
	}
	
	
	$utilisateur = get_userdata($_POST["id_utilisateur"]);
	
	
	// lien de connexion
	
	$cle = $utilisateur->lien_connexion__cle;
	$configuration = apply_filters("LienConnexion/configuration", NULL);
	
	$lien_connexion = admin_url(
		"admin-post.php?action=$configuration[code_lien_connexion]&cle=$cle"
	);
	
	
	$identifiant = $_POST["identifiant"];
	
	set_transient(
		  "LienConnexion__liaison__$identifiant"
		, $lien_connexion
		, $configuration["temps_activation_liaison_securisee"]
	);
	
	
	// changement d'état de la liaison
	
	$liaisons = get_transient("LienConnexion__liaisons_securisees");
	
	$liaisons[$identifiant] = FALSE;
	
	set_transient(
		  "LienConnexion__liaisons_securisees"
		, $liaisons
		, $configuration["temps_activation_liaison_securisee"]
	);
	
	
	// redirection
	
	$url  = "admin.php";
	$url .= "?page=LienConnexion__lancer_liaison_securisee";
	$url .= "&id_utilisateur=$utilisateur->ID";
	$url .= "&identifiant=$identifiant";
	$url .= "&fait";
	
	$url = admin_url($url);
	wp_redirect($url);
	
	exit();
	
	
});


add_action("admin_menu", function () {
	
	
	add_submenu_page(
		  " "
		, "Lancer liaison securisée"
		, ""
		, "read"
		, "LienConnexion__lancer_liaison_securisee"
		, function () {
			
			if (!isset($_GET["id_utilisateur"])) {
				return;
			}
			
			if (!current_user_can("edit_user", $_GET["id_utilisateur"])) {
				return;
			}
			
			
			$configuration = apply_filters("LienConnexion/configuration", NULL);
			
			
			if (	!apply_filters("LienConnexion/autoriser_utilisateur", $_GET["id_utilisateur"], TRUE)
				||	!isset($configuration["id_page_liaison_securisee"])
			) {
				return;
			}
			
			
			$utilisateur = get_userdata($_GET["id_utilisateur"]);
			
			
			// ouverture de la liaison
			
			set_transient(
				  "LienConnexion__ouverture_liaison_securisee"
				, "oui"
				, $configuration["temps_activation_liaison_securisee"]
			);
			
			
			// recherche des liaisons
			
			$liaisons = get_transient("LienConnexion__liaisons_securisees");
			
			if (FALSE === $liaisons) {
				$liaisons = [];
			}
			
			
			?>
				
				<h2>
					Lancement liaison sécurisée pour 
					 <?php echo htmlspecialchars($utilisateur->display_name);?>
				</h2>
				
				
				<?php if (isset($_GET["fait"])) {?>
					
					<div class="message">
						L'URL de connexion a été envoyé à l'identifiant 
						 <?php echo htmlspecialchars($_GET["identifiant"]);?>.
					</div>
					
				<?php } elseif (isset($_GET["identifiant"])) {?>
				
					<?php
						$texte_bouton  = "";
						$texte_bouton .= "Valider la liaison avec";
						$texte_bouton .= " l'identifiant $_GET[identifiant]";
						
						$action = "LienConnexion__liaison_securisee__validation";
						$url_validation = admin_url("admin-post.php?action=$action");
					?>
					
					<form action="<?php echo htmlspecialchars($url_validation);?>" method="POST">
						
						<?php foreach ($_GET as $cle => $valeur) {?>
							
							<input
								type="hidden"
								name="<?php echo htmlspecialchars($cle);?>"
								value="<?php echo htmlspecialchars($valeur);?>"
							/>
							
						<?php }?>
						
						<input type="submit" 
							value="<?php echo htmlspecialchars($texte_bouton);?>"/>
						
					</form>
					
					
					
				<?php } else {?>
					
					<form action="" method="GET">
						
						<?php foreach ($_GET as $cle => $valeur) {?>
							
							<input
								type="hidden"
								name="<?php echo htmlspecialchars($cle);?>"
								value="<?php echo htmlspecialchars($valeur);?>"
							/>
							
						<?php }?>
						
						
						<?php foreach ($liaisons as $identifiant => $choix_possible) {?>
							
							<?php
								if (!$choix_possible) {
									continue;
								}
							?>
							
							<div>
								<label>
									<input
										type="radio"
										name="identifiant"
										value="<?php echo htmlspecialchars($identifiant);?>"
									/>
									<?php echo htmlspecialchars($identifiant);?>
								</label>
							</div>
							
						<?php }?>
						
						<input type="submit" value="Suite"/>
						
					</form>
					
				<?php }?>
				
				
			<?php
			
			
		} // FIN contenu de la page
	); // FIN add_submenu_page(
	
	
}); // FIN add_action("admin_menu", function () {


