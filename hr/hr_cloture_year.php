<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Congés
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les 
termes de la Licence Publique Générale GNU publiée par la Free Software Foundation.
Ce programme est distribué car potentiellement utile, mais SANS AUCUNE GARANTIE, 
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation 
dans un but spécifique. Reportez-vous à la Licence Publique Générale GNU pour plus de détails.
Vous devez avoir reçu une copie de la Licence Publique Générale GNU en même temps 
que ce programme ; si ce n'est pas le cas, écrivez à la Free Software Foundation, 
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, États-Unis.
*************************************************************************************************
This program is free software; you can redistribute it and/or modify it under the terms
of the GNU General Public License as published by the Free Software Foundation; either 
version 2 of the License, or any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*************************************************************************************************/

define('_PHP_CONGES', 1);
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$DEBUG = FALSE ;
//$DEBUG = TRUE ;
//echo "exercice = ".$_SESSION['config']['num_exercice']." </br>\n";

// verif des droits du user à afficher la page
verif_droits_user($session, "is_hr", $DEBUG);


	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$choix_groupe            = getpost_variable("choix_groupe");
	$cloture_users           = getpost_variable("cloture_users");
	$cloture_globale         = getpost_variable("cloture_globale");
	$cloture_groupe          = getpost_variable("cloture_groupe");
	$tab_cloture_users       = getpost_variable("tab_cloture_users");
	/*************************************/



echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";	
echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "<title> ".$_SESSION['config']['titre_resp_index']." </title>\n";
//include("../fonctions_javascript.php") ;
echo "</head>\n";
	
$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
echo "<CENTER>\n";

	
	/*************************************/
	/***  suite de la page             ***/
	/*************************************/
	
	/** initialisation des tableaux des types de conges/absences  **/
	// recup du tableau des types de conges (conges et congesexceptionnels)
	// on concatene les 2 tableaux
	$tab_type_cong = ( recup_tableau_types_conges($DEBUG) + recup_tableau_types_conges_exceptionnels($DEBUG)  );

	// titre
	echo "<H2>".$_SESSION['lang']['resp_cloture_exercice_titre']."</H2>\n\n";
		
	if($cloture_users=="TRUE")
	{
		cloture_users($tab_type_cong, $tab_cloture_users, $tab_commentaire_saisie, $DEBUG);
	}
	elseif($cloture_globale=="TRUE")
	{
		cloture_globale($tab_type_cong, $DEBUG);
	}
	elseif($cloture_groupe=="TRUE")
	{
		cloture_globale_groupe($choix_groupe, $tab_type_cong, $DEBUG);
	}
	else
	{
		saisie_cloture($tab_type_cong,$DEBUG);
	}

	
	
/***********************************/
/***  fin de la page             ***/
echo "</CENTER>\n";
	
echo "</body>\n";
echo "</html>\n";
	


/************************************************************************/
/*** FONCTIONS ***/

function saisie_cloture( $tab_type_conges, $DEBUG)
{
//$DEBUG==TRUE;
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;

	// recup de la liste de TOUS les users dont $resp_login est responsable 
	// (prend en compte le resp direct, les groupes, le resp virtuel, etc ...)
	// renvoit une liste de login entre quotes et séparés par des virgules
	$tab_all_users_du_hr=recup_infos_all_users_du_hr($_SESSION['userlogin']);
	$tab_all_users_du_grand_resp=recup_infos_all_users_du_grand_resp($_SESSION['userlogin']);    
	if($DEBUG==TRUE) { echo "tab_all_users_du_hr =<br>\n"; print_r($tab_all_users_du_hr); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_all_users_du_grand_resp =<br>\n"; print_r($tab_all_users_du_grand_resp); echo "<br>\n"; }
	
	if( (count($tab_all_users_du_hr)!=0) || (count($tab_all_users_du_grand_resp)!=0) )
	{
		/************************************************************/
		/* SAISIE GLOBALE pour tous les utilisateurs du responsable */
		affichage_cloture_globale_pour_tous($tab_type_conges, $DEBUG);
		echo "<br>\n";
		
		/***********************************************************************/
		/* SAISIE GROUPE pour tous les utilisateurs d'un groupe du responsable */
		if( $_SESSION['config']['gestion_groupes']==TRUE )
		{
			affichage_cloture_globale_groupe($tab_type_conges, $DEBUG);
		}
		echo "<br>\n";

		/************************************************************/
		/* SAISIE USER PAR USER pour tous les utilisateurs du responsable */
		affichage_cloture_user_par_user($tab_type_conges, $tab_all_users_du_hr, $tab_all_users_du_grand_resp, $DEBUG);
		echo "<br>\n";
		
	}
	else
	 echo $_SESSION['lang']['resp_etat_aucun_user']."<br>\n";
	
	/* FERMETURE FENETRE */
	echo " <form action=\"\" method=\"POST\"> \n";
	echo "<input type=\"button\" value=\"".$_SESSION['lang']['form_close_window']."\" onClick=\"javascript:window.close();\">\n";
	echo " </form> \n";

}


function affichage_cloture_user_par_user($tab_type_conges, $tab_all_users_du_hr, $tab_all_users_du_grand_resp, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;
	
	/************************************************************/
	/* CLOTURE EXERCICE USER PAR USER pour tous les utilisateurs du responsable */
	
	if( (count($tab_all_users_du_hr)!=0) || (count($tab_all_users_du_grand_resp)!=0) )
	{
		echo "<form action=\"$PHP_SELF?session=$session&onglet=cloture_exercice\" method=\"POST\"> \n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td align=\"center\">\n";
		echo "<fieldset class=\"cal_saisie\">\n";
		echo "<legend class=\"boxlogin\">".$_SESSION['lang']['resp_cloture_exercice_users']."</legend>\n";
		echo "	<table>\n";
		echo "	<tr>\n";
		echo "	<td align=\"center\">\n";

		// AFFICHAGE TITRES TABLEAU
		echo "	<table cellpadding=\"2\" class=\"tablo\" width=\"700\">\n";
		echo "	<tr align=\"center\">\n";
		echo "	<td class=\"titre\">".$_SESSION['lang']['divers_nom_maj_1']."</td>\n";
		echo "	<td class=\"titre\">".$_SESSION['lang']['divers_prenom_maj_1']."</td>\n";
		echo "	<td class=\"titre\">".$_SESSION['lang']['divers_quotite_maj_1']."</td>\n";
		foreach($tab_type_conges as $id_conges => $libelle)
		{
			echo "	<td class=\"titre\">$libelle<br><i>(".$_SESSION['lang']['divers_solde'].")</i></td>\n";
		}
		echo "	<td class=\"titre\">".$_SESSION['lang']['divers_cloturer_maj_1']."<br></td>\n" ;
		echo "	<td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."<br></td>\n" ;
		echo "	</tr>\n";
		
		// AFFICHAGE LIGNES TABLEAU

		// affichage des users dont on est responsable :
		foreach($tab_all_users_du_hr as $current_login => $tab_current_user)
		{		
			affiche_ligne_du_user($current_login, $tab_type_conges, $tab_current_user);
		}
		
		// affichage des users dont on est grand responsable :
		if( ($_SESSION['config']['double_validation_conges']==TRUE) && ($_SESSION['config']['grand_resp_ajout_conges']==TRUE) )
		{
			$nb_colspan=50;
			echo " <tr align=\"center\"><td class=\"histo\" colspan=\"$nb_colspan\"><i>".$_SESSION['lang']['resp_etat_users_titre_double_valid']."</i></td></tr>\n";

			foreach($tab_all_users_du_grand_resp as $current_login => $tab_current_user)
			{		
				affiche_ligne_du_user($current_login, $tab_type_conges, $tab_current_user);
			}
		}	
		echo "	</table>\n\n";

		echo "	</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "	<td align=\"center\">\n";
		echo "	<input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">\n";
		echo "	</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		
		echo "</fieldset>\n";
		echo "</td></tr>\n";
		echo "</table>\n";
		echo "<input type=\"hidden\" name=\"cloture_users\" value=\"TRUE\">\n";
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "</form> \n";
	}
}

function affiche_ligne_du_user($current_login, $tab_type_conges, $tab_current_user)
{
	echo " <tr align=\"center\">\n";
	//tableau de tableaux les nb et soldes de conges d'un user (indicé par id de conges)
	$tab_conges=$tab_current_user['conges']; 
	
	/** sur la ligne ,   **/
	echo " <td class=\"titre\">".$tab_current_user['nom']."</td>\n";
	echo " <td class=\"titre\">".$tab_current_user['prenom']."</td>\n";
	echo " <td class=\"histo\">".$tab_current_user['quotite']."%</td>\n";
	
	foreach($tab_type_conges as $id_conges => $libelle)
	{
		echo " <td class=\"histo\">".$tab_conges[$libelle]['nb_an']." <i>(".$tab_conges[$libelle]['solde'].")</i></td>\n";
	}
			
	// si le num d'exercice du user est < à celui de l'appli (il n'a pas encore été basculé): on peut le cocher
	if($tab_current_user['num_exercice'] < $_SESSION['config']['num_exercice'])
		echo "	<td align=\"center\" class=\"histo\"><input type=\"checkbox\" name=\"tab_cloture_users[$current_login]\" value=\"TRUE\" checked></td>\n";
	else
		echo "	<td align=\"center\" class=\"histo\"><img src=\"../img/stop.png\" width=\"16\" height=\"16\" border=\"0\" ></td>\n";
			
	$comment_cloture = $_SESSION['lang']['resp_cloture_exercice_commentaire']." ".date("m/Y");
	echo "	<td align=\"center\" class=\"histo\"><input type=\"text\" name=\"tab_commentaire_saisie[$current_login]\" size=\"20\" maxlength=\"200\" value=\"$comment_cloture\"></td>\n";
	echo " 	</tr>\n";
}


function affichage_cloture_globale_pour_tous($tab_type_conges, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;
	
	/************************************************************/
	/* CLOTURE EXERCICE GLOBALE pour tous les utilisateurs du responsable */
	
	echo "<form action=\"$PHP_SELF?session=$session&onglet=cloture_exercice\" method=\"POST\"> \n";
	echo "<table>\n";
	echo "<tr><td align=\"center\">\n";
	echo "	<fieldset class=\"cal_saisie\">\n";
	echo "	<legend class=\"boxlogin\">".$_SESSION['lang']['resp_cloture_exercice_all']."</legend>\n";
	echo "	<table>\n";
	echo "	<tr>\n";
	echo "		<td class=\"big\">&nbsp;&nbsp;&nbsp;".$_SESSION['lang']['resp_cloture_exercice_for_all_text_confirmer']." &nbsp;&nbsp;&nbsp;</td>\n";
	echo "	</tr>\n";
	// bouton valider
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" align=\"center\"><input type=\"submit\" value=\"".$_SESSION['lang']['form_valid_cloture_global']."\"></td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	</fieldset>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "<input type=\"hidden\" name=\"cloture_globale\" value=\"TRUE\">\n";
	echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
	echo "</form> \n";
}


function affichage_cloture_globale_groupe($tab_type_conges, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;
	
	/***********************************************************************/
	/* SAISIE GROUPE pour tous les utilisateurs d'un groupe du responsable */

	// on établi la liste complète des groupes dont on est le resp (ou le grd resp)
	$list_group_resp=get_list_groupes_du_resp($_SESSION['userlogin']);
	if( ($_SESSION['config']['double_validation_conges']==TRUE) && ($_SESSION['config']['grand_resp_ajout_conges']==TRUE) )
		$list_group_grd_resp=get_list_groupes_du_grand_resp($_SESSION['userlogin'], $DEBUG);
	else
		$list_group_grd_resp="";
		
	$list_group="";
	if($list_group_resp!="")
	{
		$list_group = $list_group_resp;
		if($list_group_grd_resp!="")
			$list_group = $list_group.",".$list_group_grd_resp;
	}
	else
	{
		if($list_group_grd_resp!="")
			$list_group = $list_group_grd_resp;
	}
	
		
	if($list_group!="") //si la liste n'est pas vide ( serait le cas si n'est responsable d'aucun groupe)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\"> \n";
		echo "<table>\n";
		echo "<tr><td align=\"center\">\n";
		echo "	<fieldset class=\"cal_saisie\">\n";
		echo "	<legend class=\"boxlogin\">".$_SESSION['lang']['resp_cloture_exercice_groupe']."</legend>\n";
		
		echo "	<table>\n";
		echo "	<tr>\n";

			// création du select pour le choix du groupe
			$text_choix_group="<select name=\"choix_groupe\" >";
			$sql_group = "SELECT g_gid, g_groupename FROM conges_groupe WHERE g_gid IN ($list_group) ORDER BY g_groupename "  ;
			$ReqLog_group = SQL::query($sql_group) ;
				
			while ($resultat_group = $ReqLog_group->fetch_array()) 
			{
				$current_group_id=$resultat_group["g_gid"];
				$current_group_name=$resultat_group["g_groupename"];
				$text_choix_group=$text_choix_group."<option value=\"$current_group_id\" >$current_group_name</option>";
			}
			$text_choix_group=$text_choix_group."</select>" ;

		echo "		<td class=\"big\">".$_SESSION['lang']['resp_ajout_conges_choix_groupe']." : $text_choix_group</td>\n";
		
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"big\">".$_SESSION['lang']['resp_cloture_exercice_for_groupe_text_confirmer']." </td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\"><input type=\"submit\" value=\"".$_SESSION['lang']['form_valid_cloture_group']."\"></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		
		echo "	</fieldset>\n";
		echo "</td></tr>\n";
		echo "</table>\n";

		echo "<input type=\"hidden\" name=\"onglet\" value=\"cloture_exercice\">\n";
		echo "<input type=\"hidden\" name=\"cloture_groupe\" value=\"TRUE\">\n";
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "</form> \n";
	}
}

/*********************************************************************************************/

// cloture / debut d'exercice user par user pour les users du resp (ou grand resp)
function cloture_users($tab_type_conges, $tab_cloture_users, $tab_commentaire_saisie, $DEBUG=FALSE) 
{
//$DEBUG=TRUE;
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id(); 

	// recup de la liste de TOUS les users dont $resp_login est responsable 
	// (prend en compte le resp direct, les groupes, le resp virtuel, etc ...)
	// renvoit une liste de login entre quotes et séparés par des virgules
	$tab_all_users_du_hr=recup_infos_all_users_du_hr($_SESSION['userlogin']);
	$tab_all_users_du_grand_resp=recup_infos_all_users_du_grand_resp($_SESSION['userlogin']);
	if($DEBUG==TRUE) { echo "tab_all_users_du_hr =<br>\n"; print_r($tab_all_users_du_hr); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_all_users_du_grand_resp =<br>\n"; print_r($tab_all_users_du_grand_resp); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_type_conges =<br>\n"; print_r($tab_type_conges); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_cloture_users =<br>\n"; print_r($tab_cloture_users); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_commentaire_saisie =<br>\n"; print_r($tab_commentaire_saisie); echo "<br>\n"; }
	
	if( (count($tab_all_users_du_hr)!=0) || (count($tab_all_users_du_grand_resp)!=0) )
	{
		// traitement des users dont on est responsable :
		foreach($tab_all_users_du_hr as $current_login => $tab_current_user)
		{		
			// tab_cloture_users[$current_login]=TRUE si checkbox "cloturer" est cochée
			if( (isset($tab_cloture_users[$current_login])) && ($tab_cloture_users[$current_login]=TRUE) )
			{
				$commentaire = $tab_commentaire_saisie[$current_login];
				cloture_current_year_for_login($current_login, $tab_current_user, $tab_type_conges, $commentaire, $DEBUG);
			}
		}
		// traitement des users dont on est grand responsable :
		if( ($_SESSION['config']['double_validation_conges']==TRUE) && ($_SESSION['config']['grand_resp_ajout_conges']==TRUE) )
		{
			foreach($tab_all_users_du_grand_resp as $current_login => $tab_current_user)
			{		
				// tab_cloture_users[$current_login]=TRUE si checkbox "cloturer" est cochée
				if( (isset($tab_cloture_users[$current_login])) && ($tab_cloture_users[$current_login]=TRUE) )
				{
					$commentaire = $tab_commentaire_saisie[$current_login];
					cloture_current_year_for_login($current_login, $tab_current_user, $tab_type_conges, $commentaire, $DEBUG);
				}
			}
		}	
	}
	
	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " ".$_SESSION['lang']['form_modif_ok']." <br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session\">";
	}

}


function cloture_current_year_for_login($current_login, $tab_current_user, $tab_type_conges, $commentaire, $DEBUG=FALSE)
{
	// si le num d'exercice du user est < à celui de l'appli (il n'a pas encore été basculé): on le bascule d'exercice
	if($tab_current_user['num_exercice'] < $_SESSION['config']['num_exercice'])
	{
		// calcule de la date limite d'utilisation des reliquats (si on utilise une date limite et qu'elle n'est pas encore calculée)
		set_nouvelle_date_limite_reliquat($DEBUG);
		
		//tableau de tableaux les nb et soldes de conges d'un user (indicé par id de conges)
		$tab_conges_current_user=$tab_current_user['conges']; 
		foreach($tab_type_conges as $id_conges => $libelle)
		{
			$user_nb_jours_ajout_an = $tab_conges_current_user[$libelle]['nb_an'];
			$user_solde_actuel=$tab_conges_current_user[$libelle]['solde'];
			$user_reliquat_actuel=$tab_conges_current_user[$libelle]['reliquat'];
			
			if($DEBUG==TRUE) {echo "$current_login --- $id_conges --- $user_nb_jours_ajout_an<br>\n";}
	
			/**********************************************/
			/* Modification de la table conges_solde_user */
			
			if($_SESSION['config']['autorise_reliquats_exercice']==TRUE)
			{
				// ATTENTION : si le solde du user est négatif, on ne compte pas de reliquat et le nouveau solde est nb_jours_an + le solde actuel (qui est négatif)
				if($user_solde_actuel>0)
				{
					//calcul du reliquat pour l'exercice suivant
					if($_SESSION['config']['nb_maxi_jours_reliquats']!=0)
					{
						if($user_solde_actuel <= $_SESSION['config']['nb_maxi_jours_reliquats'])
							$new_reliquat = $user_solde_actuel ;
						else
							$new_reliquat = $_SESSION['config']['nb_maxi_jours_reliquats'] ;
					}
					else
						$new_reliquat = $user_reliquat_actuel + $user_solde_actuel ;
					
					//
					// update D'ABORD du reliquat
					$sql_reliquat = 'UPDATE conges_solde_user SET su_reliquat = $new_reliquat WHERE su_login=\''.SQL::escape($current_login).'\'  AND su_abs_id = $id_conges ';
					$ReqLog_reliquat = SQL::query($sql_reliquat) ;
				}
				else
					$new_reliquat = $user_solde_actuel ; // qui est nul ou negatif


				$new_solde = $user_nb_jours_ajout_an + $new_reliquat  ;
					
				// update du solde
				$sql_solde = 'UPDATE conges_solde_user SET su_solde = $new_solde WHERE su_login=\''.SQL::escape($current_login).'\'  AND su_abs_id = $id_conges ' ;
				$ReqLog_solde = SQL::query($sql_solde) ;
			}
			else
			{
				// ATTENTION : meme si on accepte pas les reliquats, si le solde du user est négatif, il faut le reporter: le nouveau solde est nb_jours_an + le solde actuel (qui est négatif)
				if($user_solde_actuel < 0)
					$new_solde = $user_nb_jours_ajout_an + $user_solde_actuel ; // qui est nul ou negatif
				else
					$new_solde = $user_nb_jours_ajout_an ;
					
				$sql_solde = 'UPDATE conges_solde_user SET su_solde = $new_solde WHERE su_login=\''.SQL::escape($current_login).'\' AND su_abs_id = $id_conges ';
				$ReqLog_solde = SQL::query($sql_solde) ;
			}

			/* Modification de la table conges_users */
			// ATTENTION : ne pas faire "SET u_num_exercice = u_num_exercice+1" dans la requete SQL car on incrémenterait pour chaque type d'absence !
			$new_num_exercice=$_SESSION['config']['num_exercice'] ;
			$sql2 = 'UPDATE conges_users SET u_num_exercice = $new_num_exercice WHERE u_login=\''.SQL::escape($current_login).'\'  ';
			$ReqLog2 = SQL::query($sql2) ;
			
			// on insert l'ajout de conges dans la table periode (avec le commentaire)
			$date_today=date("Y-m-d");
			insert_dans_periode($current_login, $date_today, "am", $date_today, "am", $user_nb_jours_ajout_an, $commentaire, $id_conges, "ajout", 0, $DEBUG);
	    }
	    
	    // on incrémente le num_exercice de l'application si tous les users on été basculés.
	    update_appli_num_exercice($DEBUG);
	}	
}


// verifie si tous les users on été basculés de l'exerccice précédent vers le suivant.
// si oui : on incrémente le num_exercice de l'application
function update_appli_num_exercice($DEBUG=FALSE)
{
	// verif
	$appli_num_exercice = $_SESSION['config']['num_exercice'] ;
	$sql_verif = "SELECT u_login FROM conges_users WHERE u_login != 'admin' AND u_login != 'conges' AND u_num_exercice != $appli_num_exercice "  ;
	$ReqLog_verif = SQL::query($sql_verif) ;
				
	if($ReqLog_verif->num_rows == 0)
	{
		/* Modification de la table conges_appli */
		$sql_update= "UPDATE conges_appli SET appli_valeur = appli_valeur+1 WHERE appli_variable='num_exercice' ";
		$ReqLog_update = SQL::query($sql_update) ;
		
		// ecriture dans les logs
		$new_appli_num_exercice = $appli_num_exercice+1 ;
		log_action(0, "", "", "fin/debut exercice (appli_num_exercice : $appli_num_exercice -> $new_appli_num_exercice)", $DEBUG);
	} 

}


// cloture / debut d'exercice pour TOUS les users du resp (ou grand resp)
function cloture_globale($tab_type_conges, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id(); 

	// recup de la liste de TOUS les users dont $resp_login est responsable 
	// (prend en compte le resp direct, les groupes, le resp virtuel, etc ...)
	// renvoit une liste de login entre quotes et séparés par des virgules
	$tab_all_users_du_hr=recup_infos_all_users_du_hr($_SESSION['userlogin']);
	$tab_all_users_du_grand_resp=recup_infos_all_users_du_grand_resp($_SESSION['userlogin']);
	if($DEBUG==TRUE) { echo "tab_all_users_du_hr =<br>\n"; print_r($tab_all_users_du_hr); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_all_users_du_grand_resp =<br>\n"; print_r($tab_all_users_du_grand_resp); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_type_conges =<br>\n"; print_r($tab_type_conges); echo "<br>\n"; }
	
	$comment_cloture = $_SESSION['lang']['resp_cloture_exercice_commentaire']." ".date("m/Y");

	if( (count($tab_all_users_du_hr)!=0) || (count($tab_all_users_du_grand_resp)!=0) )
	{
		// traitement des users dont on est responsable :
		foreach($tab_all_users_du_hr as $current_login => $tab_current_user)
		{		
			cloture_current_year_for_login($current_login, $tab_current_user, $tab_type_conges, $comment_cloture, $DEBUG);
		}
		// traitement des users dont on est grand responsable :
		if( ($_SESSION['config']['double_validation_conges']==TRUE) && ($_SESSION['config']['grand_resp_ajout_conges']==TRUE) )
		{
			foreach($tab_all_users_du_grand_resp as $current_login => $tab_current_user)
			{		
				cloture_current_year_for_login($current_login, $tab_current_user, $tab_type_conges, $comment_cloture, $DEBUG);
			}
		}	
	}
	
	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " ".$_SESSION['lang']['form_modif_ok']." <br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session\">";
	}

}

// cloture / debut d'exercice pour TOUS les users d'un groupe'
function cloture_globale_groupe($group_id, $tab_type_conges, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id(); 

	// recup de la liste de TOUS les users du groupe
	$tab_all_users_du_groupe=recup_infos_all_users_du_groupe($group_id, $DEBUG);
	if($DEBUG==TRUE) { echo "tab_all_users_du_groupe =<br>\n"; print_r($tab_all_users_du_groupe); echo "<br>\n"; }
	if($DEBUG==TRUE) { echo "tab_type_conges =<br>\n"; print_r($tab_type_conges); echo "<br>\n"; }
	
	$comment_cloture = $_SESSION['lang']['resp_cloture_exercice_commentaire']." ".date("m/Y");

	if(count($tab_all_users_du_groupe)!=0)
	{
		// traitement des users dont on est responsable :
		foreach($tab_all_users_du_groupe as $current_login => $tab_current_user)
		{		
			cloture_current_year_for_login($current_login, $tab_current_user, $tab_type_conges, $comment_cloture, $DEBUG);
		}
	}
	
	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " ".$_SESSION['lang']['form_modif_ok']." <br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session\">";
	}

}

// calcule de la date limite d'utilisation des reliquats (si on utilise une date limite et qu'elle n'est pas encore calculée) et stockage dans la table
function set_nouvelle_date_limite_reliquat($DEBUG=FALSE)
{
	//si on autorise les reliquats
	if($_SESSION['config']['autorise_reliquats_exercice']==TRUE)
	{
		// s'il y a une date limite d'utilisationdes reliquats (au format jj-mm)
		if($_SESSION['config']['jour_mois_limite_reliquats']!=0)
		{
			// nouvelle date limite au format aaa-mm-jj
			$t=explode("-", $_SESSION['config']['jour_mois_limite_reliquats']);
			$new_date_limite = date("Y")."-".$t[1]."-".$t[0];
			
			//si la date limite n'a pas encore été updatée
			if($_SESSION['config']['date_limite_reliquats'] < $new_date_limite)
			{
				/* Modification de la table conges_appli */
				$sql_update= 'UPDATE conges_appli SET appli_valeur = \''.$new_date_limite.'\' WHERE appli_variable='date_limite_reliquats' ';
				$ReqLog_update = SQL::query($sql_update) ;
				
			}
		}
	}
}



?>
