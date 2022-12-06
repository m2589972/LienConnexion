<?php

add_action("admin_init", function () {
	
	
	if (!isset($_POST["user_id"])) {
		return;
	}
	
	if (	!isset($_POST["rafraichir_lien_connexion"])
		&&	!isset($_POST["desactiver_lien_connexion"])
	) {
		return;
	}
	
	$user_id = $_POST["user_id"];
	
	
	if (!apply_filters("LienConnexion/autoriser_utilisateur", $user_id, TRUE)) {
		return;
	}
	
	
	// vérifier l'autorisation de modifier l'utilisateur
	
	check_admin_referer("update-user_$user_id");
	
	if (!current_user_can("edit_user", $user_id)) {
		return;
	}
	
	
	// modifier la clé
	
	$cle = "";
	
	if (isset($_POST["rafraichir_lien_connexion"])) {
		$cle = hash("whirlpool", microtime() . mt_rand() . wp_get_session_token());
	}
	
	update_user_meta(
		  $user_id
		, "lien_connexion__cle"
		, $cle
	);
	
	
	// redirection
	$redirect = get_edit_user_link($user_id);
	wp_redirect($redirect);
	exit();
	
	
}); // FIN add_action("admin_init", function () {


add_action("personal_options", function ($user) {
	
	
	if (!apply_filters("LienConnexion/autoriser_utilisateur", $user->ID, TRUE)) {
		return;
	}
	
	
	$configuration = apply_filters("LienConnexion/configuration", NULL);
	
	$cle = $user->lien_connexion__cle;
	
	
	?>
		<tr>
			<th>
				Lien de connexion
			</th>
			<td class="elements_lien_connexion">
				
				<style>
					.elements_lien_connexion div
					{
						margin : 1.5em 0;
					}
				</style>
				
				
				<?php
				
				if ("" === $cle) {
					
					?>
						
						<div>
							Votre lien de connexion n'est pas activé actuellement.
						</div>
						
						<div>
							
							<input
								type="submit"
								name="rafraichir_lien_connexion"
								value="Activer le lien de connexion"
							/>
							
						</div>
						
						
						
					<?php
					
				} else {
					
					
					$lien_connexion = admin_url(
						"admin-post.php?action=$configuration[code_lien_connexion]&cle=$cle"
					);
					
					
					?>
						<div>
							<a href="<?php echo htmlspecialchars($lien_connexion);?>">
								<?php echo htmlspecialchars($lien_connexion);?></a>
						</div>
						
						<div>
							
							<input
								type="submit"
								name="rafraichir_lien_connexion"
								value="Rafraichir le lien de connexion"
							/>
							
						</div>
						
						<div>
							
							<input
								type="submit"
								name="desactiver_lien_connexion"
								value="Désactiver le lien de connexion"
							/>
							
						</div>
						
					<?php
					
				}
				
				?>
			</td>
		</tr>
	<?php
	
	
}); // FIN add_action("personal_options", function ($user) {



