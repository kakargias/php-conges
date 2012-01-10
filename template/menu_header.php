<?php
	
	defined( '_PHP_CONGES' ) or die( 'Restricted access' );


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
	echo "<head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
		echo "<title> ".$title." </TITLE>\n";
		echo "<link href=\"". TEMPLATE_PATH .$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
		echo "<link href=\"". TEMPLATE_PATH ."style.css\" rel=\"stylesheet\" type=\"text/css\" />";
		include ROOT_PATH .'fonctions_javascript.php' ;
		echo $additional_head;
	echo "</head>\n";
	echo '<body>';
	

	/*****************************************************************************/
	// DEBUT AFFICHAGE DU MENU
	echo "<div id=\"header\">";
		echo "<div class=\"ui-corner-bottom-8\" style=\"background-color: #C11A22; padding: 2px; margin: 10px;\">";
			echo "<div class=\"ui-corner-bottom\" style=\"background-color: white; padding: 2px; \">";

			/*****************************************************************************/
			// DEBUT AFFICHAGE DES BOUTONS ...
	
				echo "<div id=\"header_menu\">";
				
					if($info=="responsable")
					{
						if($_SESSION['config']['resp_affiche_calendrier']==TRUE)
						{
							// bouton calendrier
							if($_SESSION['config']['resp_affiche_calendrier']==TRUE)
							{
								echo '<div style="float: right;">';
									echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../calendrier.php?session=$session','calendrier',1050,600);\">" .
									//echo "<a href=\"../calendrier.php?session=$session\">" .
									 "<img src=\"". TEMPLATE_PATH ."img/rebuild.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('button_calendar') ."\" alt=\"". _('button_calendar') ."\">" .
									  _('button_calendar') ."</a>\n";
								echo '</div>';
							}
							
							// bouton imprim calendrier
							echo '<div style="float: right;">';
								echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../imprim_calendrier.php?session=$session','imprimcal',300,210);\">" .
								//echo "<a href=\"../calendrier.php?session=$session\">" .
								 "<img src=\"". TEMPLATE_PATH ."img/fileprint_4_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('button_imprim_calendar') ."\" alt=\"". _('button_imprim_calendar') ."\">" .
								  _('button_imprim_calendar') ."</a>\n";
							echo '</div>';
							
						}
						
						/*** bouton changement exercice ***/ 
						echo '<div style="float: right;">';
							echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('resp_cloture_year.php?session=$session','cloture_exercice',800,600);\">" .
							 "<img src=\"". TEMPLATE_PATH ."img/reload3.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('button_cloture') ."\" alt=\"". _('button_calendar') ."\">" .
							  _('button_cloture') ."</a>\n";
						echo '</div>';
						
					}
					
					if($info=="admin")
					{
				
						/* bouton db_sauvegarde  ***/
						echo '<div style="float: right;">';
						echo " <a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('admin_db_sauve.php?session=$session','sauvedb',400,300);\">\n";
							echo " <img src=\"". TEMPLATE_PATH ."img/floppy_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('admin_button_save_db_1') ."\" alt=\"". _('admin_button_save_db_1') ."\">\n";
							echo " ". _('admin_button_save_db_2') ."\n";
						echo '</a></div>';
				
						/* bouton jours fermeture  ***/
						echo '<div style="float: right;">';
						echo " <a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('admin_jours_fermeture.php?session=$session','fermeture',1080,690);\">\n";
							echo " <img src=\"". TEMPLATE_PATH ."img/jours_fermeture_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('admin_button_jours_fermeture_1') ."\" alt=\"". _('admin_button_jours_fermeture_1') ."\">\n";
							echo " ". _('admin_button_jours_fermeture_2') ."\n";
						echo '</a></div>';
						
						/* bouton jours chômés  ***/
						echo '<div style="float: right;">';
						echo " <a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('admin_jours_chomes.php?session=$session','jourschomes',1080,610);\">\n";
							echo " <img src=\"". TEMPLATE_PATH ."img/jours_feries_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('admin_button_jours_chomes_1') ."\" alt=\"". _('admin_button_jours_chomes_1') ."\">\n";
							echo " ". _('admin_button_jours_chomes_2') ."\n";
						echo '</a></div>';
				
						/* bouton config des mails php_conges  */
						if($_SESSION['config']['affiche_bouton_config_mail_pour_admin']==TRUE)
						{
							echo '<div style="float: right;">';
							echo " <a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../config/config_mail.php?session=$session','configmail',800,600);\">\n";
							echo " <img src=\"". TEMPLATE_PATH ."img/tux_config_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('admin_button_config_mail_1') ."\" alt=\"". _('admin_button_config_mail_1') ."\">\n";
							echo " ". _('admin_button_config_mail_2') ."\n";
							echo '</a></div>';
						}
						
						/* bouton config types absence php_conges  */
						if($_SESSION['config']['affiche_bouton_config_absence_pour_admin']==TRUE)
						{
							echo '<div style="float: right;">';
							echo " <a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../config/config_type_absence.php?session=$session','configabs',800,600);\">\n";
							echo " <img src=\"". TEMPLATE_PATH ."img/tux_config_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('admin_button_config_abs_1') ."\" alt=\"". _('admin_button_config_abs_1') ."\">\n";
							echo " ". _('admin_button_config_abs_2') ."\n";
							echo '</a></div>';
						}
				
				
						/* bouton config php_conges  */
						if($_SESSION['config']['affiche_bouton_config_pour_admin']==TRUE && $_SESSION['is_admin']=="Y")
						{
							echo '<div style="float: right;">';
							echo " <a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../config/configure.php?session=$session','config',800,600);\">\n";
							echo " <img src=\"". TEMPLATE_PATH ."img/tux_config_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('admin_button_config_1') ."\" alt=\"". _('admin_button_config_1') ."\">\n";
							echo " ". _('admin_button_config_2') ."\n";
							echo '</a></div>';
						}
					}
				
					if($info=="user")
					{
					
						/*** bouton calendrier  ***/
						if($_SESSION['config']['user_affiche_calendrier']==TRUE)
						{
							echo '<div style="float: right;">';
							// affichage dans un popup
							echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../calendrier.php?session=$session','calendrier',1450,550);\">" .
									"<img src=\"". TEMPLATE_PATH ."img/rebuild.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('button_calendar') ."\" alt=\"". _('button_calendar') ."\">" .
									 _('button_calendar') ."</a>\n";
							echo '</div>';
						}
						
						/*** bouton export calendar  ***/
						if($_SESSION['config']['export_ical_vcal']==TRUE)
						{
							echo '<div style="float: right;">';
							echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../export_vcalendar.php?session=$session&user_login=".$_SESSION['userlogin']."','icalvcal',457,280);\">" .
							// echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../export_vcalendar.php?session=$session&&user_login=".$_SESSION['userlogin']."','icalvcal',457,280);\">" .
									"<img src=\"". TEMPLATE_PATH ."img/export-22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('button_export_2') ."\" alt=\"". _('button_export_2') ."\">" .
									 _('button_export_1') ."</a>\n";
							echo '</div>';
						}
				
						/*** bouton éditions papier  ***/
						if($_SESSION['config']['editions_papier']==TRUE)
						{
							echo '<div style="float: right;">';
							echo "<a href=\"../edition/edit_user.php?session=$session&user_login=".$_SESSION['userlogin']."\" target=\"_blank\">" .
									"<img src=\"". TEMPLATE_PATH ."img/edition-22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('button_editions') ."\" alt=\"". _('button_editions') ."\">" .
									 _('button_editions') ."</a>\n";
							echo '</div>';
						}
				
					}
					
				echo '<div style="clear: right;  margin : -6;"></div>';						
					
					/*** bouton mode utilisateur  ***/
					if( $info != "user")
					{
						echo '<div style="float: right;">';
						echo "<a href=\"". ROOT_PATH ."utilisateur/user_index.php?session=$session\" method=\"POST\">" .
								"<img src=\"". TEMPLATE_PATH ."img/user_4_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('resp_menu_button_mode_user') ."\" alt=\"". _('resp_menu_button_mode_user') ."\">" .
								 _('resp_menu_button_mode_user') ."</a>\n";
						echo '</div>';
					}
					
					/*** bouton mode responsable  ***/
					if(is_resp($_SESSION['userlogin']) && $info != "responsable")
					{
						echo '<div style="float: right;">';
						echo "<a href=\"../responsable/resp_index.php?session=$session\" method=\"POST\">" .
								"<img src=\"". TEMPLATE_PATH ."img/user_3_22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('resp_menu_button_mode_responsable') ."\" alt=\"". _('button_responsable_mode') ."\">" .
								 _('button_responsable_mode') ."</a>\n";
						echo '</div>';
					}
					
					 /*** bouton mode HR ***/ 
					if(is_hr($_SESSION['userlogin']) && $info != "hr")
					{
					echo '<div style="float: right;">';
					echo "<a href=\"../hr/hr_index.php?session=$session\" method=\"POST\">" .
							"<img src=\"". TEMPLATE_PATH ."img/user-rh.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('resp_menu_button_mode_hr') ."\" alt=\"". _('resp_menu_button_mode_hr') ."\">" .
							 _('resp_menu_button_mode_hr') ."</a>\n";
					echo "</div>\n";
					}
	
					/*** bouton mode administrateur  ***/
					if(is_admin($_SESSION['userlogin']) && $info != "admin")
					{
						echo '<div style="float: right;">';
						echo "<a href=\"../admin/admin_index.php?session=$session\" method=\"POST\">" .
								"<img src=\"". TEMPLATE_PATH ."img/admin-tools-22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('button_admin_mode') ."\" alt=\"". _('button_admin_mode') ."\">" .
								 _('button_admin_mode') ."</a>\n";
						echo '</div>';
					}
						
					echo '<div style="clear: right; margin : -6; "></div>';
					
					// bouton deconnexion
					if($_SESSION['config']['auth']==TRUE)
					{
						echo '<div style="float: right;">';
						bouton_deconnexion();
						echo '</div>';
					}
					
					// bouton actualiser
					if (isset($onglet) )
					{
						echo '<div style="float: right; ">';
						if($onglet  == "resp_traite_user")
							bouton_actualiser("resp_traite_user&user_login=$user_login");  // on ajoute le user_login en paramètre à passer dans le lien ...
						else
							bouton_actualiser($onglet);
						echo '</div>';
					}
					
					echo '<div style="clear: right; margin : 0;"></div>';
					
					echo "</div>";
					echo "<div id=\"header_menu\">";
					
					if ( is_resp($_SESSION['userlogin']) ) {
						$home = 'responsable/resp_index.php?session='.$session;
					}
					else {
						$home = 'utilisateur/user_index.php?session='.$session;
					}
					
					echo '<div style="float: left; margin:-90px; margin-left:20px"><a href="'. ROOT_PATH . $home .'"><img src="'. TEMPLATE_PATH .'img/logo_adex.png"/></a></div>';	
				echo "</div>";
			
			// FIN AFFICHAGE DES BOUTONS ...
			/*****************************************************************************/
			echo "</div>";
		echo "</div>";
	echo "</div>";
	
	// FIN AFFICHAGE DU MENU
	/*****************************************************************************/
	
	
	
	// echo "<div id=\"content-center\">";

	echo "<div id=\"content\">";	
		echo "<div id=\"content-center\";>";
			echo "<center>\n";
