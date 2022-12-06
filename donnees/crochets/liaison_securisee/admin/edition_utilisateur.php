<?php

add_action("personal_options", function ($user) {
	

	if (!current_user_can("edit_user", $user->ID)) {
		return;
	}
	
	
	$configuration = apply_filters("LienConnexion/configuration", NULL);
	
	
	if (	!apply_filters("LienConnexion/autoriser_utilisateur", $user->ID, TRUE)
		||	!isset($configuration["id_page_liaison_securisee"])
	) {
		return;
	}
	
	
	$url = admin_url(
		"admin.php?page=LienConnexion__lancer_liaison_securisee&id_utilisateur={$user->ID}");
	
	
	?>
		<tr>
			<th>
			</th>
			<td>
				
				<a href="<?php echo htmlspecialchars($url);?>">
					Lancer une liaison sécurisée pour
					 <?php echo htmlspecialchars($user->display_name);?></a>
				
			</td>
		</tr>
	<?php
	
	
}); // FIN add_action("personal_options", function ($user) {


