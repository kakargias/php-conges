<?php
function saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $onglet,  $DEBUG=FALSE)
{
//$DEBUG=TRUE;
	if( $DEBUG ) { echo 'user_login = '.$user_login.', year_calendrier_saisie_debut = '.$year_calendrier_saisie_debut.', mois_calendrier_saisie_debut = '.$mois_calendrier_saisie_debut.', year_calendrier_saisie_fin = '.$year_calendrier_saisie_fin.', mois_calendrier_saisie_fin = '.$mois_calendrier_saisie_fin.', onglet = '.$onglet.'<br>';}

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	$mois_calendrier_saisie_debut_prec=0; $year_calendrier_saisie_debut_prec=0;
	$mois_calendrier_saisie_debut_suiv=0; $year_calendrier_saisie_debut_suiv=0;
	$mois_calendrier_saisie_fin_prec=0; $year_calendrier_saisie_fin_prec=0;
	$mois_calendrier_saisie_fin_suiv=0; $year_calendrier_saisie_fin_suiv=0;

	init_tab_jours_fermeture($user_login);

//		echo '<form action="'.$PHP_SELF.'?session='.$session.'" method="POST">' ;
		echo '<form action="'.$PHP_SELF.'?session='.$session.'&onglet='.$onglet.'" method="POST">' ;
//		echo '<form action="'.$PHP_SELF.'?session='.$session.'&login_user='.$user_login.'" method="POST">' ;
		// il faut indiquer le champ de formulaire 'login_user' car il est récupéré par le javascript qui apelle le calcul automatique.
//		echo '<input type="hidden" name="login_user" value="'.$user_login.'">';

			echo '<table cellpadding="0" cellspacing="5" border="0">';
			echo '<tr align="center">';
			echo '<td>';
				echo '<table cellpadding="0" cellspacing="0" border="0">';
				echo '<tr align="center">';
					echo '<td>';
					echo '<fieldset class="cal_saisie">';
						echo '<table cellpadding="0" cellspacing="0" border="0">';
						echo '<tr align="center">';
							echo "<td>\n";
								/******************************************************************/
								// affichage du calendrier de saisie de la date de DEBUT de congès
								/******************************************************************/
								echo '<table cellpadding="0" cellspacing="0" width="250" border="0">';
								echo '<tr>';
									init_var_navigation_mois_year($mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
												$mois_calendrier_saisie_debut_prec, $year_calendrier_saisie_debut_prec,
												$mois_calendrier_saisie_debut_suiv, $year_calendrier_saisie_debut_suiv,
												$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
												$mois_calendrier_saisie_fin_prec, $year_calendrier_saisie_fin_prec,
												$mois_calendrier_saisie_fin_suiv, $year_calendrier_saisie_fin_suiv );

								// affichage des boutons de défilement
								// recul du mois saisie début
								echo '<td align="center" class="big">';
								echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_prec.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_prec.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin.'&user_login='.$user_login.'&onglet='.$onglet.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simfirs.gif" width="16" height="16" border="0" alt="'. _('divers_mois_precedent') .'" title="'. _('divers_mois_precedent') .'"> ';
								echo '</a>';
								echo '</td>';

								echo '<td align="center" class="big">'. _('divers_debut_maj') .' :</td>';

								// affichage des boutons de défilement
								// avance du mois saisie début
								// si le mois de saisie fin est antérieur ou égal au mois de saisie début, on avance les 2 , sinon on avance que le mois de saisie début
								if( (($year_calendrier_saisie_debut_suiv==$year_calendrier_saisie_fin) && ($mois_calendrier_saisie_debut_suiv>=$mois_calendrier_saisie_fin))
								    || ($year_calendrier_saisie_debut_suiv>$year_calendrier_saisie_fin)  )
									$lien_mois_debut_suivant = $PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_suiv.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_suiv.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_debut_suiv.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_debut_suiv.'&user_login='.$user_login.'&onglet='.$onglet ;
								else
									$lien_mois_debut_suivant = $PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_suiv.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_suiv.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin.'&user_login='.$user_login.'&onglet='.$onglet ;
								echo '<td align="center" class="big">';
								echo '<a href="'.$lien_mois_debut_suivant.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simlast.gif" width="16" height="16" border="0" alt="'. _('divers_mois_suivant') .'" title="'. _('divers_mois_suivant') .'"> ';
								echo '</a>';
								echo '</td>';


								echo '</tr>';
								echo '</table>';
								/*** calendrier saisie date debut ***/
								affiche_calendrier_saisie_date($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, 'new_debut', $DEBUG);
							echo '</td>';
							/**************************************************/
							/* cellule 2 : boutons radio matin ou après midi */
							echo '<td align="left">';
								echo '<input type="radio" name="new_demi_jour_deb" ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris'])
								{
									// attention : IE6 : bug avec les "OnChange" sur les boutons radio!!! (on remplace par OnClick)
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="am" checked><b><u>'. _('form_am') .'</u></b><br><br>';

								echo '<input type="radio" name="new_demi_jour_deb" ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris'])
								{
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="pm"><b><u>'. _('form_pm') .'</u></b><br><br>';
							echo '</td>';
							/**************************************************/
						echo '</tr>';
						echo '</table>';
					echo '</fieldset>';
					echo '</td>';
				echo '</tr>';
				echo '<tr align="center">';
					echo '<td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="10" border="0" vspace="0" hspace="0"></td>';
				echo '</tr>';
				echo '<tr align="center">';
					echo '<td>';
					echo '<fieldset class="cal_saisie">';
						echo '<table cellpadding="0" cellspacing="0" border="0">';
						echo '<tr align="center">';
							echo '<td>';
								/******************************************************************/
								// affichage du calendrier de saisie de la date de FIN de congès
								/******************************************************************/
								echo '<table cellpadding="0" cellspacing="0" width="250" border="0">';
								echo '<tr>';
									$mois_calendrier_saisie_fin_prec = $mois_calendrier_saisie_fin==1 ? 12 : $mois_calendrier_saisie_fin-1 ;
									$mois_calendrier_saisie_fin_suiv = $mois_calendrier_saisie_fin==12 ? 1 : $mois_calendrier_saisie_fin+1 ;

								// affichage des boutons de défilement
								// recul du mois saisie fin
								// si le mois de saisie fin est antérieur ou égal au mois de saisie début, on recule les 2 , sinon on recule que le mois de saisie fin
								if( (($year_calendrier_saisie_debut==$year_calendrier_saisie_fin_prec) && ($mois_calendrier_saisie_debut>=$mois_calendrier_saisie_fin_prec))
								    || ($year_calendrier_saisie_debut>$year_calendrier_saisie_fin_prec) )
								    $lien_mois_fin_precedent = ''.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_fin_prec.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_fin_prec.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_prec.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_prec.'&user_login='.$user_login.'&onglet='.$onglet;
								else
									$lien_mois_fin_precedent = ''.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_prec.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_prec.'&user_login='.$user_login.'&onglet='.$onglet;
								echo '<td align="center" class="big">';
								echo '<a href="'.$lien_mois_fin_precedent.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simfirs.gif" width="16" height="16" border="0" alt="'. _('divers_mois_precedent') .'" title="'. _('divers_mois_precedent') .'">';
								echo ' </a>';
								echo '</td>';

								echo '<td align="center" class="big">'. _('divers_fin_maj') .' :</td>';

								// affichage des boutons de défilement
								// avance du mois saisie fin
								echo '<td align="center" class="big">';
								echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_suiv.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_suiv.'&user_login='.$user_login.'&onglet='.$onglet.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simlast.gif" width="16" height="16" border="0" alt="'. _('divers_mois_suivant') .'" title="'. _('divers_mois_suivant') .'"> ';
								echo '</a>';
								echo '</td>';
								echo '</tr>';
								echo '</table>';
								/*** calendrier saisie date fin ***/
								affiche_calendrier_saisie_date($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, 'new_fin',  $DEBUG);
							echo '</td>';
							/**************************************************/
							/* cellule 2 : boutons radio matin ou après midi */
							echo '<td align="left">';
								echo '<input type="radio" name="new_demi_jour_fin" ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris'])
								{
									// attention : IE6 : bug avec les "OnChange" sur les boutons radio!!! (on remplace par OnClick)
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="am"><b><u>'. _('form_am') .'</u></b><br><br>';

								echo '<input type="radio" name="new_demi_jour_fin"  ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris'])
								{
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="pm" checked><b><u>'. _('form_pm') .'</u></b><br><br>';
							echo '</td>';
							/**************************************************/
						echo '</tr>';
						echo '</table>';
					echo '</fieldset>';
					echo '</td>';
				echo '</tr>';
				echo '</table>';
			echo '</td>';
			echo '<td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="2" border="0" vspace="0" hspace="0"></td>';
			echo '<td>';

				/*******************/
				/*   formulaire    */
				/*******************/
				echo '<table cellpadding="0" cellspacing="2" border="0" >';
				echo '<tr>';
				echo '<td valign="top">';
					echo '<table cellpadding="2" cellspacing="3" border="0" >';
//					echo '<input type="hidden" name="login_user" value="'.'.$_SESSION['userlogin'].'.'">';
					echo '<input type="hidden" name="login_user" value="'.$user_login.'">';
					echo '<input type="hidden" name="session" value="'.$session.'">';
					// bouton 'compter les jours'
					if($_SESSION['config']['affiche_bouton_calcul_nb_jours_pris'])
					{
						echo '<tr><td colspan="2">';
							echo '<input type="button" onclick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;" value="'. _('saisie_conges_compter_jours') .'">';
						echo '</td></tr>';
					}
					// zones de texte
					echo '<tr align="center"><td><b>'. _('saisie_conges_nb_jours') .'</b></td><td><b>'. _('divers_comment_maj_1') .'</b></td></tr>';

					if($_SESSION['config']['disable_saise_champ_nb_jours_pris'])  // zone de texte en readonly et grisée
						$text_nb_jours ='<input type="text" name="new_nb_jours" size="10" maxlength="30" value="" style="background-color: #D4D4D4; " readonly="readonly">' ;
					else
						$text_nb_jours ='<input type="text" name="new_nb_jours" size="10" maxlength="30" value="">' ;

					$text_commentaire='<input type="text" name="new_comment" size="25" maxlength="30" value="">' ;
					echo '<tr align="center">';
					echo '<td>'.($text_nb_jours).'</td><td>'.($text_commentaire).'</td>';
					echo '</tr>';
					echo '<tr align="center"><td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="10" border="0" vspace="0" hspace="0"></td><td></td></tr>';
					echo '<tr align="center">';
					echo '<td colspan=2>';
						echo '<input type="hidden" name="user_login" value="'.$user_login.'">';
						echo '<input type="hidden" name="new_demande_conges" value=1>';
						// boutons du formulaire
						// les classes "button_type_submit" et "button_type_cancel"
						// servent à choisir leur position (droite gauche) dans vos feuilles de style (voir style.css)
						echo '<input type="submit" class="button_type_submit" value="'. _('form_submit') .'">   <input type="reset" class="button_type_cancel" value="'. _('form_cancel') .'">';
					echo '</td>';
					echo '</tr>';
					echo '</table>';

				echo '</td>';
				/*****************/
				/* boutons radio */
				/*****************/
				// recup d tableau des types de conges
				$tab_type_conges=recup_tableau_types_conges( $DEBUG);
				// recup du tableau des types d'absence
				$tab_type_absence=recup_tableau_types_absence( $DEBUG);
				// recup d tableau des types de conges exceptionnels
				$tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels( $DEBUG);

				$already_checked = false;
				
				echo '<td align="left" valign="top">';
				// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
				// OU si le user n'a pas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
				if( (($_SESSION['config']['user_saisie_demande'])&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['user_saisie_demande']==FALSE)&&($user_login!=$_SESSION['userlogin'])) )
				{
					// congés
					echo '<b><i><u>'. _('divers_conges') .' :</u></i></b><br>';
					foreach($tab_type_conges as $id => $libelle)
					{
						if($id==1) {
							echo '<input type="radio" name="new_type" value="'.$id.'" checked> '.$libelle.'<br>';
							$already_checked = true;
						}
						else
							echo '<input type="radio" name="new_type" value="'.$id.'"> '.$libelle.'<br>';
					}
				}
				// si le user a droit de saisir une mission ET si on est PAS dans une fenetre de responsable
				// OU si le resp a droit de saisir une mission ET si on est PAS dans une fenetre dd'utilisateur
				// OU si le resp a droit de saisir une mission ET si le resp est resp de lui meme
				if( (($_SESSION['config']['user_saisie_mission'])&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['resp_saisie_mission'])&&($user_login!=$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['resp_saisie_mission'])&&(is_resp_of_user($_SESSION['userlogin'], $user_login,  $DEBUG))) )
				{
					echo '<br>';
					// absences
					echo '<b><i><u>'. _('divers_absences') .' :</u></i></b><br>';
					foreach($tab_type_absence as $id => $libelle) {
						if (!$already_checked){
							echo '<input type="radio" name="new_type" value="'.$id.'" checked> '.$libelle.'<br>';
							$already_checked = true;
						}
						else
							echo '<input type="radio" name="new_type" value="'.$id.'"> '.$libelle.'<br>';
					}
				}
				// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
				// OU si le user n'a pas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
				if( ($_SESSION['config']['gestion_conges_exceptionnels']) && (
				    (($_SESSION['config']['user_saisie_demande'])&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['user_saisie_demande']==FALSE)&&($user_login!=$_SESSION['userlogin'])) ) )
				{
					echo '<br>';
					// congés exceptionnels
					echo '<b><i><u>'. _('divers_conges_exceptionnels') .' :</u></i></b><br>';
					 foreach($tab_type_conges_exceptionnels as $id => $libelle)
					{
						 if($id==1) {
							 echo '<input type="radio" name="new_type" value="'.$id.'" checked> '.$libelle.'<br>';
						 }
						 else
							 echo '<input type="radio" name="new_type" value="'.$id.'"> '.$libelle.'<br>';
					 }
				}

				echo '</td>';
				echo '</tr>';
				echo '</table>';

			echo '</td>';
			echo '</tr>';
			echo '</table>';

		echo '</form>' ;
}
?>