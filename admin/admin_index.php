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

// verif des droits du user à afficher la page
verif_droits_user($session, "is_admin", $DEBUG);

// => html avec menu

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
	echo"<meta http-equiv=\"X-UA-Compatible\" content=\"IE=8\" />";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<link href=\"../style.css\" rel=\"stylesheet\" type=\"text/css\" />";
	echo "<TITLE> ".$_SESSION['config']['titre_admin_index']." </TITLE>\n";
	include("../fonctions_javascript.php") ;
echo "</head>\n";

	$info="admin";
	include("../menu.php");

	/*** initialisation des variables ***/
	/*************************************/
	/* recup des parametres reçus :  */
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$onglet         = getpost_variable("onglet", "admin-users") ;
	$choix_group    = getpost_variable("choix_group") ;
	$choix_resp     = getpost_variable("choix_resp") ;
	$choix_user     = getpost_variable("choix_user") ;
	$choix_gestion_groupes_responsables = getpost_variable("choix_gestion_groupes_responsables") ;
	$choix_gestion_groupes_users        = getpost_variable("choix_gestion_groupes_users") ;
	$saisie_user     = getpost_variable("saisie_user") ;
	$saisie_group    = getpost_variable("saisie_group") ;

	// si on recupere les users dans ldap et qu'on vient d'en créer un depuis la liste déroulante
	if ($_SESSION['config']['export_users_from_ldap'] == TRUE && isset($_POST['new_ldap_user']))
	{
		$index = 0;
		// On lance une boucle pour selectionner tous les items
		// traitements : $login contient les valeurs successives
		foreach($_POST['new_ldap_user'] as $login)
		{
			$tab_login[$index]=$login;
			$index++;
			// cnx à l'annuaire ldap :
			$ds = ldap_connect($_SESSION['config']['ldap_server']);
			ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3) ;
			if ($_SESSION['config']['ldap_user'] == "")
				$bound = ldap_bind($ds);
			else
				$bound = ldap_bind($ds, $_SESSION['config']['ldap_user'], $_SESSION['config']['ldap_pass']);

			// recherche des entrées :
			$filter = "(".$_SESSION['config']['ldap_login']."=".$login.")";

			$sr   = ldap_search($ds, $_SESSION['config']['searchdn'], $filter);
			$data = ldap_get_entries($ds,$sr);

			foreach ($data as $info)
			{
				$tab_new_user[$login]['login'] = $login;
				$ldap_libelle_prenom=$_SESSION['config']['ldap_prenom'];
				$ldap_libelle_nom=$_SESSION['config']['ldap_nom'];
				$tab_new_user[$login]['prenom'] = utf8_decode($info[$ldap_libelle_prenom][0]);
				$tab_new_user[$login]['nom'] = utf8_decode($info[$ldap_libelle_nom][0]);

				$ldap_libelle_mail=$_SESSION['config']['ldap_mail'];
				$tab_new_user[$login]['email']= $info[$ldap_libelle_mail][0] ;
				if($DEBUG == TRUE) { print_r($info); echo "<br>\n"; }
			}

			$tab_new_user[$login]['quotite']    = getpost_variable("new_quotite") ;
			$tab_new_user[$login]['is_resp']= getpost_variable("new_is_resp") ;
			$tab_new_user[$login]['resp_login']= getpost_variable("new_resp_login") ;
			$tab_new_user[$login]['is_admin']= getpost_variable("new_is_admin") ;
			$tab_new_user[$login]['is_hr']= getpost_variable("new_is_hr") ;
			$tab_new_user[$login]['see_all']    = getpost_variable("new_see_all") ;

			if ($_SESSION['config']['how_to_connect_user'] == "dbconges")
			{
				$tab_new_user[$login]['password1']= getpost_variable("new_password1") ;
				$tab_new_user[$login]['password2']= getpost_variable("new_password2") ;
			}
//			$tab_new_user[$login]['email']= getpost_variable("new_email") ;
			$tab_new_jours_an= getpost_variable("tab_new_jours_an") ;
			$tab_new_solde= getpost_variable("tab_new_solde") ;
			$tab_checkbox_sem_imp= getpost_variable("tab_checkbox_sem_imp") ;
			$tab_checkbox_sem_p= getpost_variable("tab_checkbox_sem_p") ;
			$tab_new_user[$login]['new_jour']= getpost_variable("new_jour") ;
			$tab_new_user[$login]['new_mois']= getpost_variable("new_mois") ;
			$tab_new_user[$login]['new_year']= getpost_variable("new_year") ;
 		}
	}
	else
	{
		$tab_new_user[0]['login']    = getpost_variable("new_login") ;
		$tab_new_user[0]['nom']    = getpost_variable("new_nom") ;
		$tab_new_user[0]['prenom']    = getpost_variable("new_prenom") ;


		$tab_new_user[0]['quotite']    = getpost_variable("new_quotite") ;
		$tab_new_user[0]['is_resp']= getpost_variable("new_is_resp") ;
		$tab_new_user[0]['resp_login']= getpost_variable("new_resp_login") ;
		$tab_new_user[0]['is_admin']= getpost_variable("new_is_admin") ;
		$tab_new_user[0]['is_hr']= getpost_variable("new_is_hr") ;
 		$tab_new_user[0]['see_all']    = getpost_variable("new_see_all") ;

		if ($_SESSION['config']['how_to_connect_user'] == "dbconges")
		{
			$tab_new_user[0]['password1']= getpost_variable("new_password1") ;
			$tab_new_user[0]['password2']= getpost_variable("new_password2") ;
		}
		$tab_new_user[0]['email']= getpost_variable("new_email") ;
		$tab_new_jours_an= getpost_variable("tab_new_jours_an") ;
		$tab_new_solde= getpost_variable("tab_new_solde") ;
		$tab_checkbox_sem_imp= getpost_variable("tab_checkbox_sem_imp") ;
		$tab_checkbox_sem_p= getpost_variable("tab_checkbox_sem_p") ;
		$tab_new_user[0]['new_jour']= getpost_variable("new_jour") ;
		$tab_new_user[0]['new_mois']= getpost_variable("new_mois") ;
		$tab_new_user[0]['new_year']= getpost_variable("new_year") ;
	}

	$new_group_name=addslashes( getpost_variable("new_group_name")) ;
	$new_group_libelle=addslashes( getpost_variable("new_group_libelle")) ;
	$new_group_double_valid= getpost_variable("new_group_double_valid") ;
	$change_group_users= getpost_variable("change_group_users") ;
	$checkbox_group_users= getpost_variable("checkbox_group_users") ;
	$change_user_groups= getpost_variable("change_user_groups") ;
	$checkbox_user_groups= getpost_variable("checkbox_user_groups") ;
	$change_group_responsables= getpost_variable("change_group_responsables") ;
	$checkbox_group_resp= getpost_variable("checkbox_group_resp") ;
	$checkbox_group_grd_resp= getpost_variable("checkbox_group_grd_resp") ;
	$change_responsable_group= getpost_variable("change_responsable_group") ;
	$checkbox_resp_group= getpost_variable("checkbox_resp_group") ;
	$checkbox_grd_resp_group= getpost_variable("checkbox_grd_resp_group") ;
	/* FIN de la recup des parametres    */
	/*************************************/


	if($DEBUG==TRUE)
	{
		echo "tab_new_jours_an = "; print_r($tab_new_jours_an) ; echo "<br>\n";
		echo "tab_new_solde = "; print_r($tab_new_solde) ; echo "<br>\n";
	}
	

	/*******************************************************/

	echo "<H1>". _('admin_titre') ."</H1>";
	echo '<table cellpadding="1" cellspacing="2" border="1">';
	echo "<tr>\n";
	
	/*************************************/
	/***  suite de la page             ***/
	/*************************************/

	//connexion mysql

	if($saisie_user=="ok")
	{
		if($_SESSION['config']['export_users_from_ldap'] == TRUE)
		{
			foreach($tab_login as $login)
			{
				ajout_user($tab_new_user[$login], $tab_checkbox_sem_imp, $tab_checkbox_sem_p, $tab_new_jours_an, $tab_new_solde, $checkbox_user_groups, $DEBUG);
			}
		}
		else
			ajout_user($tab_new_user[0], $tab_checkbox_sem_imp, $tab_checkbox_sem_p, $tab_new_jours_an, $tab_new_solde, $checkbox_user_groups, $DEBUG);
	}
	elseif($saisie_group=="ok")
	{
		ajout_groupe($new_group_name, $new_group_libelle, $new_group_double_valid,  $DEBUG);
	}
	elseif($change_group_users=="ok")
	{
		modif_group_users($choix_group, $checkbox_group_users, $DEBUG);
	}
	elseif($change_user_groups=="ok")
	{
		modif_user_groups($choix_user, $checkbox_user_groups,  $DEBUG);
	}
	elseif($change_group_responsables=="ok")
	{
		modif_group_responsables($choix_group, $checkbox_group_resp, $checkbox_group_grd_resp, $DEBUG);
	}
	elseif($change_responsable_group=="ok")
	{
		modif_resp_groupes($choix_resp, $checkbox_resp_group, $checkbox_grd_resp_group, $DEBUG);
	}
	else
	{

		/* affichage normal */
		affichage($onglet, $new_group_name, $new_group_libelle, $choix_group, $choix_user, $choix_resp, $tab_new_user[0], $tab_new_jours_an, $tab_new_solde, $DEBUG);
	}

	
	echo "</tr></table>\n";
	
	include '../bottom.php';


/*********************************************************************************/
/*  FONCTIONS   */
/*********************************************************************************/

function affichage($onglet, $new_group_name, $new_group_libelle, $choix_group, $choix_user, $choix_resp, &$tab_new_user, &$tab_new_jours_an, &$tab_new_solde, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	/* AFFICHAGE DES ONGLETS...  ***/
	// on affiche CERTAINS onglets seulement si la gestion de groupe est activée
	echo "<!-- affichage des onglets -->\n";
	
		if($onglet!="admin-users")
			echo "<td class=\"onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-users\" class=\"bouton-onglet\"> ". _('admin_onglet_gestion_user') ." </a></td>\n";
		else
			echo "<td class=\"current-onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-users\" class=\"bouton-current-onglet\"> ". _('admin_onglet_gestion_user') ." </a></td>\n";

		if($onglet!="ajout-user")
			echo "<td class=\"onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=ajout-user\" class=\"bouton-onglet\"> ". _('admin_onglet_add_user') ." </a></td>\n";
		else
			echo "<td class=\"current-onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=ajout-user\" class=\"bouton-current-onglet\"> ". _('admin_onglet_add_user') ." </a></td>\n";

	if($_SESSION['config']['gestion_groupes']==TRUE)
	{
			if($onglet!="admin-group")
				echo "<td class=\"onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group\" class=\"bouton-onglet\"> ". _('admin_onglet_gestion_groupe') ." </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group\" class=\"bouton-current-onglet\"> ". _('admin_onglet_gestion_groupe') ." </a></td>\n";

			if($onglet!="admin-group-users")
				echo "<td class=\"onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-users\" class=\"bouton-onglet\"> ". _('admin_onglet_groupe_user') ." </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-users\" class=\"bouton-current-onglet\"> ". _('admin_onglet_groupe_user') ." </a></td>\n";

			if($_SESSION['config']['responsable_virtuel']==FALSE)
			{
				if($onglet!="admin-group-responsables")
					echo "<td class=\"onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables\" class=\"bouton-onglet\"> ". _('admin_onglet_groupe_resp') ." </a></td>\n";
				else
					echo "<td class=\"current-onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables\" class=\"bouton-current-onglet\"> ". _('admin_onglet_groupe_resp') ." </a></td>\n";
			}
		echo "</tr>\n";
		echo "</table>\n" ;
		
	}


	echo "<!-- AFFICHAGE DE LA PAGE DEMANDéE -->\n";
	echo "<center>\n" ;
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"92%\">\n" ;
	/**************************************/
	/* AFFICHAGE DE LA PAGE DEMANDéE  ***/
	echo "<tr align=\"center\">\n";
	echo "<td>\n";
	echo " <br/> ";

	/**********************/
	/* ADMIN Utilisateurs */
	/**********************/
	if($onglet=="admin-users")
	{
		affiche_gestion_utilisateurs($DEBUG);
	}
	/**********************/
	/* AJOUT Utilisateurs */
	/**********************/
	if($onglet=="ajout-user")
	{
		affiche_formulaire_ajout_user($tab_new_user, $tab_new_jours_an, $tab_new_solde, $DEBUG);
	}
	/**********************/
	/* ADMIN Groupes */
	/**********************/
	elseif($onglet=="admin-group")
	{
		affiche_gestion_groupes($new_group_name, $new_group_libelle, $DEBUG);
	}
	/********************************/
	/* ADMIN Groupes<->Utilisateurs */
	/********************************/
	elseif($onglet=="admin-group-users")
	{
		affiche_choix_gestion_groupes_users($choix_group, $choix_user, $DEBUG);
	}
	/********************************/
	/* ADMIN Groupes<->Responsables */
	/********************************/
	elseif($onglet=="admin-group-responsables")
	{
		affiche_choix_gestion_groupes_responsables($choix_group, $choix_resp);
	}

	echo "	</td>\n";
	echo "</tr>\n";
	/* FIN AFFICHAGE DE LA PAGE DEMANDéE  ***/
	/******************************************/
	echo "</table>\n";
	echo "</CENTER>\n";

}



function ajout_user(&$tab_new_user, $tab_checkbox_sem_imp, $tab_checkbox_sem_p, &$tab_new_jours_an, &$tab_new_solde, $checkbox_user_groups, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	if($DEBUG==TRUE)
	{
		echo "tab_new_jours_an = "; print_r($tab_new_jours_an) ; echo "<br>\n";
		echo "tab_new_solde = "; print_r($tab_new_solde) ; echo "<br>\n";
	}

	// si pas d'erreur de saisie :
	if( verif_new_param($tab_new_user, $tab_new_jours_an, $tab_new_solde, $DEBUG)==0)
	{
		echo $tab_new_user['login']."---".$tab_new_user['nom']."---".$tab_new_user['prenom']."---".$tab_new_user['quotite']."\n";
		echo "---".$tab_new_user['is_resp']."---".$tab_new_user['resp_login']."---".$tab_new_user['is_admin']."---".$tab_new_user['is_hr']."---".$tab_new_user['see_all']."---".$tab_new_user['email']."<br>\n";

		foreach($tab_new_jours_an as $id_cong => $jours_an)
		{
			echo $tab_new_jours_an[$id_cong]."---".$tab_new_solde[$id_cong]."<br>\n";
		}
		$new_date_deb_grille=$tab_new_user['new_year']."-".$tab_new_user['new_mois']."-".$tab_new_user['new_jour'];
		echo "$new_date_deb_grille<br>\n" ;

		/*****************************/
		/* INSERT dans conges_users  */
		if ($_SESSION['config']['how_to_connect_user'] == "dbconges")
				$motdepasse = md5($tab_new_user['password1']);
		else
			$motdepasse = "none";
			

			
		$sql1 = "INSERT INTO conges_users SET ";
		$sql1=$sql1."u_login='".$tab_new_user['login']."', ";
		$sql1=$sql1."u_nom='".addslashes($tab_new_user['nom'])."', ";
		$sql1=$sql1."u_prenom='".addslashes($tab_new_user['prenom'])."', ";
		$sql1=$sql1."u_is_resp='".$tab_new_user['is_resp']."', ";
		
		
		if($tab_new_user['is_resp'] = "no_resp")
			$sql1=$sql1."u_resp_login= NULL , ";
		else
			$sql1=$sql1."u_resp_login='". $tab_new_user['is_resp']."', ";
		
		
		$sql1=$sql1."u_is_admin='".$tab_new_user['is_admin']."', ";
		$sql1=$sql1."u_is_hr='".$tab_new_user['is_hr']."', ";
		$sql1=$sql1."u_see_all='".$tab_new_user['see_all']."', ";
		$sql1=$sql1."u_passwd='$motdepasse', ";
		$sql1=$sql1."u_quotite=".$tab_new_user['quotite'].",";
		$sql1=$sql1." u_email='".$tab_new_user['email']."' ";
		$result1 = SQL::query($sql1);


		/**********************************/
		/* INSERT dans conges_solde_user  */
		foreach($tab_new_jours_an as $id_cong => $jours_an)
		{
			$sql3 = "INSERT INTO conges_solde_user (su_login, su_abs_id, su_nb_an, su_solde, su_reliquat) ";
			$sql3 = $sql3. "VALUES ('".$tab_new_user['login']."' , $id_cong, ".$tab_new_jours_an[$id_cong].", ".$tab_new_solde[$id_cong].", 0) " ;
			$result3 = SQL::query($sql3);
		}


		/*****************************/
		/* INSERT dans conges_artt  */
		$list_colums_to_insert="a_login";
		$list_values_to_insert="'".$tab_new_user['login']."'";
		// on parcours le tableau des jours d'absence semaine impaire
		if($tab_checkbox_sem_imp!="") {
			while (list ($key, $val) = each ($tab_checkbox_sem_imp)) {
				//echo "$key => $val<br>\n";
				$list_colums_to_insert="$list_colums_to_insert, $key";
				$list_values_to_insert="$list_values_to_insert, '$val'";
			}
		}
		if($tab_checkbox_sem_p!="") {
			while (list ($key, $val) = each ($tab_checkbox_sem_p)) {
				//echo "$key => $val<br>\n";
				$list_colums_to_insert="$list_colums_to_insert, $key";
				$list_values_to_insert="$list_values_to_insert, '$val'";
			}
		}

		$sql2 = "INSERT INTO conges_artt ($list_colums_to_insert, a_date_debut_grille) VALUES ($list_values_to_insert, '$new_date_deb_grille')" ;
		$result2 = SQL::query($sql2);


		/***********************************/
		/* ajout du usre dans ses groupes  */
		$result4=TRUE;
		if( ($_SESSION['config']['gestion_groupes']==TRUE) && ($checkbox_user_groups!="") )
		{
			$result4=commit_modif_user_groups($tab_new_user['login'], $checkbox_user_groups, $DEBUG);
		}



		/*****************************/

		if($result1==TRUE && $result2==TRUE && $result3==TRUE && $result4==TRUE)
			echo  _('form_modif_ok') ."<br><br> \n";
		else
			echo  _('form_modif_not_ok') ."<br><br> \n";

		$comment_log = "ajout_user : ".$tab_new_user['login']." / ".addslashes($tab_new_user['nom'])." ".addslashes($tab_new_user['prenom'])." (".$tab_new_user['quotite']." %)" ;
		log_action(0, "", $tab_new_user['login'], $comment_log, $DEBUG);

		/* APPEL D'UNE AUTRE PAGE */
		echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-users\" method=\"POST\"> \n";
		echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
		echo " </form> \n";
	}
}


function verif_new_param(&$tab_new_user, &$tab_new_jours_an, &$tab_new_solde, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	foreach($tab_new_jours_an as $id_cong => $jours_an)
	{
		$valid=verif_saisie_decimal($tab_new_jours_an[$id_cong], $DEBUG);    //verif la bonne saisie du nombre décimal
		$valid=verif_saisie_decimal($tab_new_solde[$id_cong], $DEBUG);    //verif la bonne saisie du nombre décimal
	}
	if($DEBUG==TRUE)
	{
		echo "tab_new_jours_an = "; print_r($tab_new_jours_an) ; echo "<br>\n";
		echo "tab_new_solde = "; print_r($tab_new_solde) ; echo "<br>\n";
	}


	// verif des parametres reçus :
	// si on travaille avec la base dbconges, on teste tout, mais si on travaille avec ldap, on ne teste pas les champs qui viennent de ldap ...
	if( ($_SESSION['config']['export_users_from_ldap'] == FALSE &&
		(strlen($tab_new_user['nom'])==0 || strlen($tab_new_user['prenom'])==0
//		|| strlen($tab_new_user['jours_an'])==0
//		|| strlen($tab_new_user['solde_jours'])==0
		|| strlen($tab_new_user['password1'])==0 || strlen($tab_new_user['password2'])==0
		|| strcmp($tab_new_user['password1'], $tab_new_user['password2'])!=0 || strlen($tab_new_user['login'])==0
		|| strlen($tab_new_user['quotite'])==0
		|| $tab_new_user['quotite']>100)
		)
		|| ($_SESSION['config']['export_users_from_ldap'] == TRUE &&
		(strlen($tab_new_user['login'])==0
//		||strlen($tab_new_user['jours_an'])==0
//		|| strlen($tab_new_user['solde_jours'])==0
		|| strlen($tab_new_user['quotite'])==0
		|| $tab_new_user['quotite']>100)
		)
		)
	{
		echo "<H3><font color=\"red\"> ". _('admin_verif_param_invalides') ." </font></H3>\n"  ;
		// affichage des param :
		echo $tab_new_user['login']."---".$tab_new_user['nom']."---".$tab_new_user['prenom']."---".$tab_new_user['quotite']."---".$tab_new_user['is_resp']."---".$tab_new_user['resp_login']."<br>\n";
		//echo $tab_new_user['jours_an']."---".$tab_new_user['solde_jours']."---".$tab_new_user['rtt_an']."---".$tab_new_user['solde_rtt']."<br>\n";
		foreach($tab_new_jours_an as $id_cong => $jours_an)
		{
			echo $tab_new_jours_an[$id_cong]."---".$tab_new_solde[$id_cong]."<br>\n";
		}

		echo "<form action=\"$PHP_SELF?session=$session&onglet=ajout-user\" method=\"POST\">\n"  ;
		echo "<input type=\"hidden\" name=\"new_login\" value=\"".$tab_new_user['login']."\">\n";
		echo "<input type=\"hidden\" name=\"new_nom\" value=\"".$tab_new_user['nom']."\">\n";
		echo "<input type=\"hidden\" name=\"new_prenom\" value=\"".$tab_new_user['prenom']."\">\n";
		echo "<input type=\"hidden\" name=\"new_is_resp\" value=\"".$tab_new_user['is_resp']."\">\n";
		echo "<input type=\"hidden\" name=\"new_resp_login\" value=\"".$tab_new_user['resp_login']."\">\n";
		echo "<input type=\"hidden\" name=\"new_is_admin\" value=\"".$tab_new_user['is_admin']."\">\n";
		echo "<input type=\"hidden\" name=\"new_is_hr\" value=\"".$tab_new_user['is_hr']."\">\n";
		echo "<input type=\"hidden\" name=\"new_see_all\" value=\"".$tab_new_user['see_all']."\">\n";
		echo "<input type=\"hidden\" name=\"new_quotite\" value=\"".$tab_new_user['quotite']."\">\n";
		echo "<input type=\"hidden\" name=\"new_email\" value=\"".$tab_new_user['email']."\">\n";
		foreach($tab_new_jours_an as $id_cong => $jours_an)
		{
			echo "<input type=\"hidden\" name=\"tab_new_jours_an[$id_cong]\" value=\"".$tab_new_jours_an[$id_cong]."\">\n";
			echo "<input type=\"hidden\" name=\"tab_new_solde[$id_cong]\" value=\"".$tab_new_solde[$id_cong]."\">\n";
		}

		echo "<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n";
		echo "<input type=\"submit\" value=\"". _('form_redo') ."\">\n";
		echo"</form>\n" ;

		return 1;
	}
	else {
		// verif si le login demandé n'existe pas déjà ....
		$sql_verif='SELECT u_login FROM conges_users WHERE u_login=\''.SQL::quote($tab_new_user['login']).'\'';
		$ReqLog_verif = SQL::query($sql_verif);

		$num_verif = $ReqLog_verif->num_rows;
		if ($num_verif!=0)
		{
			echo "<H3><font color=\"red\"> ". _('admin_verif_login_exist') ." </font></H3>\n"  ;
			echo "<form action=\"$PHP_SELF?session=$session&onglet=ajout-user\" method=\"POST\">\n"  ;
			echo "<input type=\"hidden\" name=\"new_login\" value=\"".$tab_new_user['login']."\">\n";
			echo "<input type=\"hidden\" name=\"new_nom\" value=\"".$tab_new_user['nom']."\">\n";
			echo "<input type=\"hidden\" name=\"new_prenom\" value=\"".$tab_new_user['prenom']."\">\n";
			echo "<input type=\"hidden\" name=\"new_is_resp\" value=\"".$tab_new_user['is_resp']."\">\n";
			echo "<input type=\"hidden\" name=\"new_resp_login\" value=\"".$tab_new_user['resp_login']."\">\n";
			echo "<input type=\"hidden\" name=\"new_is_admin\" value=\"".$tab_new_user['is_admin']."\">\n";
			echo "<input type=\"hidden\" name=\"new_is_hr\" value=\"".$tab_new_user['is_hr']."\">\n";
			echo "<input type=\"hidden\" name=\"new_quotite\" value=\"".$tab_new_user['quotite']."\">\n";
			echo "<input type=\"hidden\" name=\"new_email\" value=\"".$tab_new_user['email']."\">\n";

			foreach($tab_new_jours_an as $id_cong => $jours_an)
			{
				echo "<input type=\"hidden\" name=\"tab_new_jours_an[$id_cong]\" value=\"".$tab_new_jours_an[$id_cong]."\">\n";
				echo "<input type=\"hidden\" name=\"tab_new_solde[$id_cong]\" value=\"".$tab_new_solde[$id_cong]."\">\n";
			}

			echo "<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n";
			echo "<input type=\"submit\" value=\"". _('form_redo') ."\">\n";
			echo "</form>\n" ;

			return 1;
		}
		elseif($_SESSION['config']['where_to_find_user_email'] == "dbconges" && strrchr($tab_new_user['email'], "@")==FALSE)
		{
			echo "<H3> ". _('admin_verif_bad_mail') ." </H3>\n" ;
			echo "<form action=\"$PHP_SELF?session=$session&onglet=ajout-user\" method=\"POST\">\n" ;
			echo "<input type=\"hidden\" name=\"new_login\" value=\"".$tab_new_user['login']."\">\n";
			echo "<input type=\"hidden\" name=\"new_nom\" value=\"".$tab_new_user['nom']."\">\n";
			echo "<input type=\"hidden\" name=\"new_prenom\" value=\"".$tab_new_user['prenom']."\">\n";
			echo "<input type=\"hidden\" name=\"new_is_resp\" value=\"".$tab_new_user['is_resp']."\">\n";
			echo "<input type=\"hidden\" name=\"new_resp_login\" value=\"".$tab_new_user['resp_login']."\">\n";
			echo "<input type=\"hidden\" name=\"new_is_admin\" value=\"".$tab_new_user['is_admin']."\">\n";
			echo "<input type=\"hidden\" name=\"new_is_hr\" value=\"".$tab_new_user['is_hr']."\">\n";
			echo "<input type=\"hidden\" name=\"new_quotite\" value=\"".$tab_new_user['quotite']."\">\n";
			echo "<input type=\"hidden\" name=\"new_email\" value=\"".$tab_new_user['email']."\">\n";

			foreach($tab_new_jours_an as $id_cong => $jours_an)
			{
				echo "<input type=\"hidden\" name=\"tab_new_jours_an[$id_cong]\" value=\"".$tab_new_jours_an[$id_cong]."\">\n";
				echo "<input type=\"hidden\" name=\"tab_new_solde[$id_cong]\" value=\"".$tab_new_solde[$id_cong]."\">\n";
			}

			echo "<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n";
			echo "<input type=\"submit\" value=\"". _('form_redo') ."\">\n";
			echo "</form>\n" ;

			return 1;
		}
		else
			return 0;
	}
}



function affiche_gestion_utilisateurs($DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H2> ". _('admin_onglet_gestion_user') ." :</H2>\n\n";
	
	/*********************/
	/* Etat Utilisateurs */
	/*********************/

	// recup du tableau des types de conges (seulement les conges)
	$tab_type_conges=recup_tableau_types_conges($DEBUG);

	// recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) {
	  $tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels($DEBUG);
	}

	// AFFICHAGE TABLEAU
	// echo "<h3><font color=\"red\">". _('admin_users_titre') ." :</font></h3>\n";

	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
	echo "<tr>\n";
	echo "<td class=\"titre\">". _('divers_nom_maj_1') ."</td>\n";
	echo "<td class=\"titre\">". _('divers_prenom_maj_1') ."</td>\n";
	echo "<td class=\"titre\">". _('divers_login_maj_1') ."</td>\n";
	echo "<td class=\"titre\">". _('divers_quotite_maj_1') ."</td>\n";
	foreach($tab_type_conges as $id_type_cong => $libelle)
	{
		echo "<td class=\"titre\">$libelle / ". _('divers_an') ."</td>\n";
		echo "<td class=\"titre\">". _('divers_solde') ." $libelle</td>\n";
	}

	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) {
	  foreach($tab_type_conges_exceptionnels as $id_type_cong => $libelle)
	  {
	    echo "<td class=\"titre\">". _('divers_solde') ." $libelle</td>\n";
	  }
	}
	echo "<td class=\"titre\">". _('admin_users_is_resp') ."</td>\n";
	echo "<td class=\"titre\">". _('admin_users_resp_login') ."</td>\n";
	echo "<td class=\"titre\">". _('admin_users_is_admin') ."</td>\n";
	echo "<td class=\"titre\">". _('admin_users_is_hr') ."</td>\n";
	echo "<td class=\"titre\">". _('admin_users_see_all') ."</td>\n";
	if($_SESSION['config']['where_to_find_user_email']=="dbconges")
		echo "<td class=\"titre\">". _('admin_users_mail') ."</td>\n";
	echo "<td></td>\n";
	echo "<td></td>\n";
	if($_SESSION['config']['admin_change_passwd']==TRUE)
		echo "<td></td>\n";
	echo "</tr>\n";

	// Récuperation des informations des users:
	$tab_info_users=array();
	// si l'admin peut voir tous les users  OU si on est en mode "responsble virtuel" OU si l'admin n'est responsable d'aucun user
	if(($_SESSION['config']['admin_see_all']==TRUE) || ($_SESSION['config']['responsable_virtuel']==TRUE) || (admin_is_responsable($_SESSION['userlogin'])==FALSE))
		$tab_info_users = recup_infos_all_users($DEBUG);
	else
		$tab_info_users = recup_infos_all_users_du_resp($_SESSION['userlogin'], $DEBUG);

	if($DEBUG==TRUE) { echo "tab_info_users :<br>\n"; print_r($tab_info_users); echo "<br><br>\n";}

	foreach($tab_info_users as $current_login => $tab_current_infos)
	{

		
		$admin_modif_user="<a href=\"admin_modif_user.php?session=$session&u_login=$current_login\">"."<img src=\"../img/edition-22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('form_modif') ."\" alt=\"". _('form_modif') ."\"></a>" ;
		$admin_suppr_user="<a href=\"admin_suppr_user.php?session=$session&u_login=$current_login\">"."<img src=\"../img/stop.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('form_supprim') ."\" alt=\"". _('form_supprim') ."\"></a>" ;
		$admin_chg_pwd_user="<a href=\"admin_chg_pwd_user.php?session=$session&u_login=$current_login\">"."<img src=\"../img/password.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('form_password') ."\" alt=\"". _('form_password') ."\"></a>" ;


		echo "<tr>\n";
		echo "<td class=\"histo\"><b>".$tab_current_infos['nom']."</b></td>\n";
		echo "<td class=\"histo\"><b>".$tab_current_infos['prenom']."</b></td>\n";
		echo "<td class=\"histo\">$current_login</td>\n";
		echo "<td class=\"histo\">".$tab_current_infos['quotite']."%</td>\n";

		//tableau de tableaux les nb et soldes de conges d'un user (indicé par id de conges)
		$tab_conges=$tab_current_infos['conges'];
		
		foreach($tab_type_conges as $id_conges => $libelle)
		{
			if (isset($tab_conges[$libelle]))
			{
				echo "<td class=\"histo\">".$tab_conges[$libelle]['nb_an']."</td>\n";
				echo "<td class=\"histo\">".$tab_conges[$libelle]['solde']."</td>\n";
			}
			else
			{
				echo "<td class=\"histo\">0</td>\n";
				echo "<td class=\"histo\">0</td>\n";
			}
		}
		if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE)
		{
			foreach($tab_type_conges_exceptionnels as $id_conges => $libelle)
			{
				if (isset($tab_conges[$libelle]))
					echo "<td class=\"histo\">".$tab_conges[$libelle]['solde']."</td>\n";
				else
					echo "<td class=\"histo\">0</td>\n";
			}
		}
		echo "<td class=\"histo\">".$tab_current_infos['is_resp']."</td>\n";
		echo "<td class=\"histo\">".$tab_current_infos['resp_login']."</td>\n";
		echo "<td class=\"histo\">".$tab_current_infos['is_admin']."</td>\n";
		echo "<td class=\"histo\">".$tab_current_infos['is_hr']."</td>\n";
		echo "<td class=\"histo\">".$tab_current_infos['see_all']."</td>\n";
		if($_SESSION['config']['where_to_find_user_email']=="dbconges")
			echo "<td class=\"histo\">".$tab_current_infos['email']."</td>\n";
		echo "<td class=\"histo\">$admin_modif_user</td>\n";
		echo "<td class=\"histo\">$admin_suppr_user</td>\n";
		if(($_SESSION['config']['admin_change_passwd']==TRUE) && ($_SESSION['config']['how_to_connect_user'] == "dbconges"))
			echo "<td class=\"histo\">$admin_chg_pwd_user</td>\n";
		echo "</tr>\n";
	}
	echo"</table>\n\n";
	echo "<br>\n";
}



// affaichage du formulaire de saisie d'un nouveau user
function affiche_formulaire_ajout_user(&$tab_new_user, &$tab_new_jours_an, &$tab_new_solde,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	// recup du tableau des types de conges (seulement les conges)
	$tab_type_conges=recup_tableau_types_conges($DEBUG);

	// recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE)
	{
	  $tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels($DEBUG);
	}

	if($DEBUG==TRUE) { echo "tab_type_conges = <br>\n"; print_r($tab_type_conges); echo "<br>\n"; }

	/*********************/
	/* Ajout Utilisateur */
	/*********************/

	echo"<br><br><br><hr align=\"center\" size=\"2\" width=\"90%\"> \n";
	// TITRE
	echo "<H3><u>". _('admin_new_users_titre') ."</u></H3>\n\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n"  ;

	/****************************************/
	// tableau des infos de user

	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
	echo "<tr>\n";
	if ($_SESSION['config']['export_users_from_ldap'] == TRUE)
	   	echo "<td class=\"histo\">". _('divers_nom_maj_1') ." ". _('divers_prenom_maj_1') ."</td>\n";
	else
	{
		echo "<td class=\"histo\">". _('divers_login_maj_1') ."</td>\n";
		echo "<td class=\"histo\">". _('divers_nom_maj_1') ."</td>\n";
		echo "<td class=\"histo\">". _('divers_prenom_maj_1') ."</td>\n";
	}
	echo "<td class=\"histo\">". _('divers_quotite_maj_1') ."</td>\n";
	echo "<td class=\"histo\">". _('admin_new_users_is_resp') ."</td>\n";
	echo "<td class=\"histo\">". _('divers_responsable_maj_1') ."</td>\n";
	echo "<td class=\"histo\">". _('admin_new_users_is_admin') ."</td>\n";
	echo "<td class=\"histo\">". _('admin_new_users_is_hr') ."</td>\n";
	echo "<td class=\"histo\">". _('admin_new_users_see_all') ."</td>\n";
	if ($_SESSION['config']['export_users_from_ldap'] == FALSE)
	//if($_SESSION['config']['where_to_find_user_email']=="dbconges")
		echo "<td class=\"histo\">". _('admin_users_mail') ."</td>\n";
	if ($_SESSION['config']['how_to_connect_user'] == "dbconges")
	{
		echo "<td class=\"histo\">". _('admin_new_users_password') ."</td>\n";
		echo "<td class=\"histo\">". _('admin_new_users_password') ."</td>\n";
	}
	echo "</tr>\n";

	$text_nom="<input type=\"text\" name=\"new_nom\" size=\"10\" maxlength=\"30\" value=\"".$tab_new_user['nom']."\">" ;
	$text_prenom="<input type=\"text\" name=\"new_prenom\" size=\"10\" maxlength=\"30\" value=\"".$tab_new_user['prenom']."\">" ;
	if( (!isset($tab_new_user['quotite'])) || ($tab_new_user['quotite']=="") )
		$tab_new_user['quotite']=100;
	$text_quotite="<input type=\"text\" name=\"new_quotite\" size=\"3\" maxlength=\"3\" value=\"".$tab_new_user['quotite']."\">" ;
	$text_is_resp="<select name=\"new_is_resp\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;

	// PREPARATION DES OPTIONS DU SELECT du resp_login
	$text_resp_login="<select name=\"new_resp_login\" id=\"resp_login_id\" ><option value=\"no_resp\">Pas de resopnsable</option>" ;
	$sql2 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp = \"Y\" ORDER BY u_nom, u_prenom"  ;
	$ReqLog2 = SQL::query($sql2);

	while ($resultat2 = $ReqLog2->fetch_array()) {
		$current_resp_login=$resultat2["u_login"];
		if($tab_new_user['resp_login']==$current_resp_login)
			$text_resp_login=$text_resp_login."<option value=\"$current_resp_login\" selected>".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
		else
			$text_resp_login=$text_resp_login."<option value=\"$current_resp_login\">".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
	}
	$text_resp_login=$text_resp_login."</select>" ;

	$text_is_admin="<select name=\"new_is_admin\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	$text_is_hr="<select name=\"new_is_hr\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	$text_see_all="<select name=\"new_see_all\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	$text_email="<input type=\"text\" name=\"new_email\" size=\"10\" maxlength=\"99\" value=\"".$tab_new_user['email']."\">" ;
	$text_password1="<input type=\"password\" name=\"new_password1\" size=\"10\" maxlength=\"15\" value=\"\" autocomplete=\"off\" >" ;
	$text_password2="<input type=\"password\" name=\"new_password2\" size=\"10\" maxlength=\"15\" value=\"\" autocomplete=\"off\" >" ;
	$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"98\" value=\"".$tab_new_user['login']."\">" ;


	// AFFICHAGE DE LA LIGNE DE SAISIE D'UN NOUVEAU USER

	echo "<tr>\n";
	// Aj. D.Chabaud - Université d'Auvergne - Sept. 2005
	if ($_SESSION['config']['export_users_from_ldap'] == TRUE)
	{
		// Récupération de la liste des utilisateurs via un ldap :

		// on crée 2 tableaux (1 avec les noms + prénoms, 1 avec les login)
		// afin de pouvoir construire une liste déroulante dans le formulaire qui suit...
		$tab_ldap  = array();
		$tab_login = array();
		recup_users_from_ldap($tab_ldap, $tab_login, $DEBUG);

		// construction de la liste des users récupérés du ldap ...
		array_multisort($tab_ldap, $tab_login); // on trie les utilisateurs par le nom

		$lst_users = "<select multiple size=9 name=new_ldap_user[]><option>------------------</option>\n";
		$i = 0;

		foreach ($tab_login as $login)
		{
			$lst_users .= "<option value=$tab_login[$i]>$tab_ldap[$i]</option>\n";
			$i++;
		}
		$lst_users .= "</select>\n";
		echo "<td class=\"histo\">$lst_users</td>\n";
	}
	else
	{
		echo "<td class=\"histo\">$text_login</td>\n";
		echo "<td class=\"histo\">$text_nom</td>\n";
		echo "<td class=\"histo\">$text_prenom</td>\n";
	}

	echo "<td class=\"histo\">$text_quotite</td>\n";
	echo "<td class=\"histo\">$text_is_resp</td>\n";
	echo "<td class=\"histo\">$text_resp_login</td>\n";
	echo "<td class=\"histo\">$text_is_admin</td>\n";
	echo "<td class=\"histo\">$text_is_hr</td>\n";
	echo "<td class=\"histo\">$text_see_all</td>\n";
	//if($_SESSION['config']['where_to_find_user_email']=="dbconges")
	if ($_SESSION['config']['export_users_from_ldap'] == FALSE)
		echo "<td class=\"histo\">$text_email</td>\n";
	if ($_SESSION['config']['how_to_connect_user'] == "dbconges")
	{
		echo "<td class=\"histo\">$text_password1</td>\n";
		echo "<td class=\"histo\">$text_password2</td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";

	echo "<br>\n";


	/****************************************/
	//tableau des conges annuels et soldes

//	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
	echo "<table cellpadding=\"2\" class=\"tablo\" >\n";
	// ligne de titres
	echo "<tr>\n";
	echo "<td class=\"histo\"></td>\n";
	echo "<td class=\"histo\">". _('admin_new_users_nb_par_an') ."</td>\n";
	echo "<td class=\"histo\">". _('divers_solde') ."</td>\n";
	echo "</tr>\n";
	// ligne de saisie des valeurs
	foreach($tab_type_conges as $id_type_cong => $libelle)
	{
		echo "<tr>\n";
		$value_jours_an = ( isset($tab_new_jours_an[$id_type_cong]) ? $tab_new_jours_an[$id_type_cong] : 0 );
		$value_solde_jours = ( isset($tab_new_solde[$id_type_cong]) ? $tab_new_solde[$id_type_cong] : 0 );
		$text_jours_an="<input type=\"text\" name=\"tab_new_jours_an[$id_type_cong]\" size=\"5\" maxlength=\"5\" value=\"$value_jours_an\">" ;
		$text_solde_jours="<input type=\"text\" name=\"tab_new_solde[$id_type_cong]\" size=\"5\" maxlength=\"5\" value=\"$value_solde_jours\">" ;
		echo "<td class=\"histo\">$libelle</td>\n";
		echo "<td class=\"histo\">$text_jours_an</td>\n";
		echo "<td class=\"histo\">$text_solde_jours</td>\n";
		echo "</tr>\n";
	}
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) {
	  foreach($tab_type_conges_exceptionnels as $id_type_cong => $libelle)
	  {
	    echo "<tr>\n";
	    $value_solde_jours = ( isset($tab_new_solde[$id_type_cong]) ? $tab_new_solde[$id_type_cong] : 0 );
		$text_jours_an="<input type=\"hidden\" name=\"tab_new_jours_an[$id_type_cong]\" size=\"5\" maxlength=\"5\" value=\"0\"> &nbsp; " ;
	    $text_solde_jours="<input type=\"text\" name=\"tab_new_solde[$id_type_cong]\" size=\"5\" maxlength=\"5\" value=\"$value_solde_jours\">" ;
	    echo "<td class=\"histo\">$libelle</td>\n";
		echo "<td class=\"histo\">$text_jours_an</td>\n";
	    echo "<td class=\"histo\">$text_solde_jours</td>\n";
	    echo "</tr>\n";
	  }
	}
	echo "</table>\n";

	echo "<br>\n\n";

	// saisie de la grille des jours d'abscence ARTT ou temps partiel:
	saisie_jours_absence_temps_partiel($tab_new_user['login'],  $DEBUG);


    // si gestion des groupes :  affichage des groupe pour y affecter le user
    if($_SESSION['config']['gestion_groupes']==TRUE)
    {
		echo "<br>\n";
		affiche_tableau_affectation_user_groupes("",  $DEBUG);
    }

	echo "<br>\n";
	echo "<input type=\"hidden\" name=\"saisie_user\" value=\"ok\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;
}

/******************************************************************************************************/

function affiche_gestion_groupes($new_group_name, $new_group_libelle, $DEBUG=FALSE)
{
   $PHP_SELF=$_SERVER['PHP_SELF'];
   $session=session_id();

   echo "<H3>". _('admin_onglet_gestion_groupe') ."</H3>\n\n";

   /*********************/
   /* Etat Groupes      */
   /*********************/
   // Récuperation des informations :
   $sql_gr = "SELECT g_gid, g_groupename, g_comment, g_double_valid FROM conges_groupe ORDER BY g_groupename"  ;

   // AFFICHAGE TABLEAU
   echo "<h3>". _('admin_gestion_groupe_etat') ." :</h3>\n";
   echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
   echo "<tr>\n";
   echo "    <td class=\"titre\">". _('admin_groupes_groupe') ."</td>\n";
   echo "    <td class=\"titre\">". _('admin_groupes_libelle') ."</td>\n";
   echo "    <td class=\"titre\">". _('admin_groupes_nb_users') ."</td>\n";
   if($_SESSION['config']['double_validation_conges']==TRUE)
       echo "    <td class=\"titre\">". _('admin_groupes_double_valid') ."</td>\n";
   echo "    <td></td>\n";
   echo "    <td></td>\n";
   echo "</tr>\n";

   $ReqLog_gr = SQL::query($sql_gr);
   while ($resultat_gr = $ReqLog_gr->fetch_array())
   {

       $sql_gid=$resultat_gr["g_gid"] ;
       $sql_group=$resultat_gr["g_groupename"] ;
       $sql_comment=$resultat_gr["g_comment"] ;
       $sql_double_valid=$resultat_gr["g_double_valid"] ;
       $nb_users_groupe = get_nb_users_du_groupe($sql_gid, $DEBUG);

       $admin_modif_group="<a href=\"admin_modif_group.php?session=$session&group=$sql_gid\">". _('form_modif') ."</a>" ;
       $admin_suppr_group="<a href=\"admin_suppr_group.php?session=$session&group=$sql_gid\">". _('form_supprim') ."</a>" ;

       echo "<tr>\n";
       echo "<td class=\"histo\"><b>$sql_group</b></td>\n";
       echo "<td class=\"histo\">$sql_comment</td>\n";
       echo "<td class=\"histo\">$nb_users_groupe</td>\n";
       if($_SESSION['config']['double_validation_conges']==TRUE)
           echo "<td class=\"histo\">$sql_double_valid</td>\n";
       echo "<td class=\"histo\">$admin_modif_group</td>\n";
       echo "<td class=\"histo\">$admin_suppr_group</td>\n";
       echo "</tr>\n";
   }
   echo "</table>\n\n";


   /*********************/
   /* Ajout Groupe      */
   /*********************/

   echo "<br><br><br><hr align=\"center\" size=\"2\" width=\"90%\"> \n";
   // TITRE
   echo "<H3><u>". _('admin_groupes_new_groupe') ."</u></H3>\n\n";

   echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ;

   echo "<table cellpadding=\"2\" class=\"tablo\">\n";
   echo "<tr>\n";
   echo "<td class=\"histo\"><b>". _('admin_groupes_groupe') ."</b></td>\n";
   echo "<td class=\"histo\">". _('admin_groupes_libelle') ." / ". _('divers_comment_maj_1') ."</td>\n";
   if($_SESSION['config']['double_validation_conges']==TRUE)
       echo "    <td class=\"histo\">". _('admin_groupes_double_valid') ."</td>\n";
   echo "</tr>\n";

   $text_groupname="<input type=\"text\" name=\"new_group_name\" size=\"30\" maxlength=\"50\" value=\"".$new_group_name."\">" ;
   $text_libelle="<input type=\"text\" name=\"new_group_libelle\" size=\"50\" maxlength=\"250\" value=\"".$new_group_libelle."\">" ;

   echo "<tr>\n";
   echo "<td class=\"histo\">$text_groupname</td>\n";
   echo "<td class=\"histo\">$text_libelle</td>\n";
   if($_SESSION['config']['double_validation_conges']==TRUE)
   {
       $text_double_valid="<select name=\"new_group_double_valid\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
       echo "<td class=\"histo\">$text_double_valid</td>\n";
   }
   echo "</tr>\n";
   echo "</table><br>\n\n";

   echo "<br>\n";
   echo "<input type=\"hidden\" name=\"saisie_group\" value=\"ok\">\n";
   echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
   echo "</form>\n" ;

   echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\">\n" ;
   echo "<input type=\"submit\" value=\"". _('form_cancel') ."\">\n";
   echo "</form>\n" ;
}



function ajout_groupe($new_group_name, $new_group_libelle, $new_group_double_valid,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	if(verif_new_param_group($new_group_name, $new_group_libelle,  $DEBUG)==0)  // verif si les nouvelles valeurs sont coohérentes et n'existe pas déjà
	{
		$ngm=stripslashes($new_group_name);
		echo "$ngm --- $new_group_libelle<br>\n";

		$sql1 = "INSERT INTO conges_groupe SET g_groupename='$new_group_name', g_comment='$new_group_libelle', g_double_valid ='$new_group_double_valid' " ;
		$result = SQL::query($sql1);

		$new_gid=SQL::getVar('insert_id');
		// par défaut le responsable virtuel est resp de tous les groupes !!!
		// $sql2 = "INSERT INTO conges_groupe_resp SET gr_gid=$new_gid, gr_login='conges' " ;
		// $result = SQL::query($sql2);

		if($result==TRUE)
			echo  _('form_modif_ok') ."<br><br> \n";
		else
			echo  _('form_modif_not_ok') ."<br><br> \n";

		$comment_log = "ajout_groupe : $new_gid / $new_group_name / $new_group_libelle (double_validation : $new_group_double_valid)" ;
		log_action(0, "", "", $comment_log, $DEBUG);

		/* APPEL D'UNE AUTRE PAGE */
		echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\"> \n";
		echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
		echo " </form> \n";
	}
}


function verif_new_param_group($new_group_name, $new_group_libelle, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	// verif des parametres reçus :
	if(strlen($new_group_name)==0) {
		echo "<H3> ". _('admin_verif_param_invalides') ." </H3>\n" ;
		echo "$new_group_name --- $new_group_libelle<br>\n";
		echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"new_group_name\" value=\"$new_group_name\">\n";
		echo "<input type=\"hidden\" name=\"new_group_libelle\" value=\"$new_group_libelle\">\n";

		echo "<input type=\"hidden\" name=\"saisie_group\" value=\"faux\">\n";
		echo "<input type=\"submit\" value=\"". _('form_redo') ."\">\n";
		echo "</form>\n" ;

		return 1;
	}
	else {
		// verif si le groupe demandé n'existe pas déjà ....
		$sql_verif='select g_groupename from conges_groupe where g_groupename=\''.SQL::quote($new_group_name).'\' ';
		$ReqLog_verif = SQL::query($sql_verif);
		$num_verif = $ReqLog_verif->num_rows;
		if ($num_verif!=0)
		{
			echo "<H3> ". _('admin_verif_groupe_invalide') ." </H3>\n" ;
			echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\">\n" ;
			echo "<input type=\"hidden\" name=\"new_group_name\" value=\"$new_group_name\">\n";
			echo "<input type=\"hidden\" name=\"new_group_libelle\" value=\"$new_group_libelle\">\n";

			echo "<input type=\"hidden\" name=\"saisie_group\" value=\"faux\">\n";
			echo "<input type=\"submit\" value=\"". _('form_redo') ."\">\n";
			echo "</form>\n" ;

			return 1;
		}
		else
			return 0;
	}
}

/***************************************************************************************************/

function affiche_choix_gestion_groupes_users($choix_group, $choix_user, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];


	if( $choix_group!="" )     // si un groupe choisi : on affiche la gestion par groupe
	{
		affiche_gestion_groupes_users($choix_group, $DEBUG);
	}
	elseif( $choix_user!="" )     // si un user choisi : on affiche la gestion par user
	{
		affiche_gestion_user_groupes($choix_user, $DEBUG);
	}
	else    // si pas de groupe ou de user choisi : on affiche les choix
	{
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_groupes_users($DEBUG);
		echo "</td>\n";
		echo "<td valign=\"top\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_user_groupes($DEBUG);
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

}



function affiche_choix_groupes_users($DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_user') .":</H3>\n\n";


	/********************/
	/* Choix Groupe     */
	/********************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename"  ;

	// AFFICHAGE TABLEAU
	echo "<h3>". _('admin_aff_choix_groupe_titre') ." :</h3>\n";
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr>\n";
	echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_groupe') ."&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_libelle') ."&nbsp;</td>\n";
	echo "</tr>\n";

	$ReqLog_gr = SQL::query($sql_gr);
	while ($resultat_gr = $ReqLog_gr->fetch_array())
	{

		$sql_gid=$resultat_gr["g_gid"] ;
		$sql_group=$resultat_gr["g_groupename"] ;
		$sql_comment=$resultat_gr["g_comment"] ;

		$choix_group="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-users&choix_group=$sql_gid\"><b>&nbsp;$sql_group&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\"><b>&nbsp;$choix_group&nbsp;</b></td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_comment&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


function affiche_gestion_groupes_users($choix_group, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_user') .":</H3>\n\n";


	/************************/
	/* Affichage Groupes    */
	/************************/
	// Récuperation des informations :
	$sql_gr = 'SELECT g_groupename, g_comment FROM conges_groupe WHERE g_gid='.SQL::quote($choix_group);
	$ReqLog_gr = SQL::query($sql_gr);
	$resultat_gr = $ReqLog_gr->fetch_array();
	$sql_group=$resultat_gr["g_groupename"] ;
	$sql_comment=$resultat_gr["g_comment"] ;


	echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";

	//AFFICHAGE DU TABLEAU DES USERS DU GROUPE
	echo "<table class=\"tablo\">\n";

	// affichage TITRE
	echo "<tr align=\"center\">\n";
	echo "	<td colspan=3><h3>". _('admin_gestion_groupe_users_membres') ." &nbsp;<b>$sql_group&nbsp;:</b>&nbsp;$sql_comment&nbsp;</h3></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"titre\">&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;". _('divers_personne_maj_1') ."&nbsp;:</td>\n";
	echo "	<td class=\"titre\">&nbsp;". _('divers_login') ."&nbsp;:</td>\n";
	echo "</tr>\n";

	// affichage des users

	//on rempli un tableau de tous les users avec le login, le nom, le prenom (tableau de tableau à 3 cellules
	// Récuperation des utilisateurs :
	$tab_users=array();
	$sql_users = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom "  ;
	$ReqLog_users = SQL::query($sql_users);

	while($resultat_users=$ReqLog_users->fetch_array())
	{
		$tab_u=array();
		$tab_u["login"]=$resultat_users["u_login"];
		$tab_u["nom"]=$resultat_users["u_nom"];
		$tab_u["prenom"]=$resultat_users["u_prenom"];
		$tab_users[]=$tab_u;
	}
	// on rempli un autre tableau des users du groupe
	$tab_group=array();
	$sql_gu = 'SELECT gu_login FROM conges_groupe_users WHERE gu_gid=\''.SQL::quote($choix_group).'\' ORDER BY gu_login ';
	$ReqLog_gu = SQL::query($sql_gu);

	while($resultat_gu=$ReqLog_gu->fetch_array())
	{
		$tab_group[]=$resultat_gu["gu_login"];
	}

	// ensuite on affiche tous les users avec une case cochée si exist login dans le 2ieme tableau
	$count = count($tab_users);
	for ($i = 0; $i < $count; $i++)
	{
		$login=$tab_users[$i]["login"] ;
		$nom=$tab_users[$i]["nom"] ;
		$prenom=$tab_users[$i]["prenom"] ;

		if (in_array ($login, $tab_group))
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_users[$login]\" value=\"$login\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_users[$login]\" value=\"$login\">";
			$class="histo";
		}

		echo "<tr>\n";
		echo "	<td class=\"histo\">$case_a_cocher</td>\n";
		echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
		echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
		echo "</tr>\n";
	}

	echo "</table>\n\n";

	echo "<input type=\"hidden\" name=\"change_group_users\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_group\" value=\"$choix_group\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}


function modif_group_users($choix_group, &$checkbox_group_users,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	// on supprime tous les anciens users du groupe puis on ajoute tous ceux qui sont dans le tableau checkbox (si il n'est pas vide)
	$sql_del = 'DELETE FROM conges_groupe_users WHERE gu_gid='.SQL::quote($choix_group).' ';
	$ReqLog_del = SQL::query($sql_del);
	
	if(is_array($checkbox_group_users) && count ($checkbox_group_users)!=0)
	{
		foreach($checkbox_group_users as $login => $value)
		{
			//$login=$checkbox_group_users[$i] ;
			$sql_insert = "INSERT INTO conges_groupe_users SET gu_gid=$choix_group, gu_login='$login' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}
	else
		$result_insert=TRUE;

	if($result_insert==TRUE)
		echo  _('form_modif_ok') ."<br><br> \n";
	else
		echo  _('form_modif_not_ok') ."<br><br> \n";

	$comment_log = "mofification_users_du_groupe : $choix_group" ;
	log_action(0, "", "", $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}



function affiche_choix_user_groupes( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_user_groupe') .":</H3>\n\n";


	/********************/
	/* Choix User       */
	/********************/
	// Récuperation des informations :
	$sql_user = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom"  ;

	// AFFICHAGE TABLEAU
	echo "<h3>". _('admin_aff_choix_user_titre') ." :</h3>\n";
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr>\n";
	echo "<td class=\"titre\">&nbsp;". _('divers_nom_maj_1') ."  ". _('divers_prenom_maj_1') ."&nbsp;</td>\n";
	echo "<td class=\"titre\">&nbsp;". _('divers_login_maj_1') ."&nbsp;</td>\n";
	echo "</tr>\n";

	$ReqLog_user = SQL::query($sql_user);

	while ($resultat_user = $ReqLog_user->fetch_array())
	{

		$sql_login=$resultat_user["u_login"] ;
		$sql_nom=$resultat_user["u_nom"] ;
		$sql_prenom=$resultat_user["u_prenom"] ;

		$choix="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-users&choix_user=$sql_login\"><b>&nbsp;$sql_nom $sql_prenom&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\">&nbsp;$choix&nbsp;</td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_login&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


function affiche_gestion_user_groupes($choix_user,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_user_groupe') .":</H3>\n\n";


	/************************/
	/* Affichage Groupes    */
	/************************/

/*	// Récuperation des informations :
	$sql_u = "SELECT u_nom, u_prenom FROM conges_users WHERE u_login='$choix_user'"  ;
	$ReqLog_u = SQL::query($sql_u);

	$resultat_u = $ReqLog_u->fetch_array();
	$sql_nom=$resultat_u["u_nom"] ;
	$sql_prenom=$resultat_u["u_prenom"] ;
*/

	echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";

	affiche_tableau_affectation_user_groupes($choix_user,  $DEBUG);

	echo "<input type=\"hidden\" name=\"change_user_groups\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_user\" value=\"$choix_user\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}



function affiche_tableau_affectation_user_groupes($choix_user,  $DEBUG=FALSE)
{

	//AFFICHAGE DU TABLEAU DES GROUPES DU USER
	echo "<table class=\"tablo\">\n";

	// affichage TITRE
	echo "<tr align=\"center\">\n";
	if($choix_user=="")
		echo "	<td colspan=3><h3>". _('admin_gestion_groupe_users_group_of_new_user') ." :</h3></td>\n";
	else
		echo "	<td colspan=3><h3>". _('admin_gestion_groupe_users_group_of_user') ." <b> $choix_user </b> :</h3></td>\n";

	echo "</tr>\n";

	echo "<tr>\n";
	echo "	<td class=\"titre\">&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_groupe') ."&nbsp;:</td>\n";
	echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_libelle') ."&nbsp;:</td>\n";
	echo "</tr>\n";

	// affichage des groupes

	//on rempli un tableau de tous les groupes avec le nom et libellé (tableau de tableau à 3 cellules)
	$tab_groups=array();
	$sql_g = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename "  ;
	$ReqLog_g = SQL::query($sql_g);

	while($resultat_g=$ReqLog_g->fetch_array())
	{
		$tab_gg=array();
		$tab_gg["gid"]=$resultat_g["g_gid"];
		$tab_gg["groupename"]=$resultat_g["g_groupename"];
		$tab_gg["comment"]=$resultat_g["g_comment"];
		$tab_groups[]=$tab_gg;
	}

	$tab_user="";
	// si le user est connu
	// on rempli un autre tableau des groupes du user
	if($choix_user!="")
	{
		$tab_user=array();
		$sql_gu = 'SELECT gu_gid FROM conges_groupe_users WHERE gu_login=\''.SQL::quote($choix_user).'\' ORDER BY gu_gid ';
		$ReqLog_gu = SQL::query($sql_gu);

		while($resultat_gu=$ReqLog_gu->fetch_array())
		{
			$tab_user[]=$resultat_gu["gu_gid"];
		}
	}

	// ensuite on affiche tous les groupes avec une case cochée si existe le gid dans le 2ieme tableau
	$count = count($tab_groups);
	for ($i = 0; $i < $count; $i++)
	{
		$gid=$tab_groups[$i]["gid"] ;
		$group=$tab_groups[$i]["groupename"] ;
		$libelle=$tab_groups[$i]["comment"] ;

		if ( ($tab_user!="") && (in_array ($gid, $tab_user)) )
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_user_groups[$gid]\" value=\"$gid\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_user_groups[$gid]\" value=\"$gid\">";
			$class="histo";
		}

		echo "<tr>\n";
		echo "	<td class=\"histo\">$case_a_cocher</td>\n";
		echo "	<td class=\"$class\">&nbsp;$group&nbsp</td>\n";
		echo "	<td class=\"$class\">&nbsp;$libelle&nbsp;</td>\n";
		echo "</tr>\n";
	}

	echo "</table>\n\n";
}



function modif_user_groups($choix_user, &$checkbox_user_groups,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$result_insert=commit_modif_user_groups($choix_user, $checkbox_user_groups,  $DEBUG);

	if($result_insert==TRUE)
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	$comment_log = "mofification_des groupes auxquels $choix_user appartient" ;
	log_action(0, "", $choix_user, $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}


function commit_modif_user_groups($choix_user, &$checkbox_user_groups,  $DEBUG=FALSE)
{


	$result_insert=FALSE;
	// on supprime tous les anciens groupes du user, puis on ajoute tous ceux qui sont dans la tableau checkbox (si il n'est pas vide)
	$sql_del = 'DELETE FROM conges_groupe_users WHERE gu_login=\''.SQL::quote($choix_user).'\'';
	$ReqLog_del = SQL::query($sql_del);

	if( ($checkbox_user_groups!="") && (count ($checkbox_user_groups)!=0) )
	{
		foreach($checkbox_user_groups as $gid => $value)
		{
			$sql_insert = "INSERT INTO conges_groupe_users SET gu_gid=$gid, gu_login='$choix_user' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}
	else
		$result_insert=TRUE;

	return $result_insert;
}



/*****************************************************************************************/

// affichage des pages de gestion des responsables des groupes
function affiche_choix_gestion_groupes_responsables($choix_group, $choix_resp,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();


	if( $choix_group!="" )    // si un groupe choisi : on affiche la gestion par groupe
	{
		affiche_gestion_groupes_responsables($choix_group, $DEBUG);
	}
	elseif( $choix_resp!="" )     // si un resp choisi : on affiche la gestion par resp
	{
		affiche_gestion_responsable_groupes($choix_resp, $DEBUG);
	}
	else    // si pas de groupe ou de resp choisi : on affiche les choix
	{
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_groupes_responsables( $DEBUG);
		echo "</td>\n";
		echo "<td valign=\"top\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_responsable_groupes( $DEBUG);
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

}


// affiche le tableau des groupes pour choisir sur quel groupe on va gerer les responsables
function affiche_choix_groupes_responsables( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_resp') .":</H3>\n\n";

	/********************/
	/* Choix Groupe     */
	/********************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename"  ;

	// AFFICHAGE TABLEAU
	echo "<h3>". _('admin_aff_choix_groupe_titre') ." :</h3>\n";
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr>\n";
	echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_groupe') ."&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_libelle') ."&nbsp;</td>\n";
	echo "</tr>\n";

	$ReqLog_gr = SQL::query($sql_gr);

	while ($resultat_gr = $ReqLog_gr->fetch_array())
	{
		$sql_gid=$resultat_gr["g_gid"] ;
		$sql_groupename=$resultat_gr["g_groupename"] ;
		$sql_comment=$resultat_gr["g_comment"] ;

		$text_choix_group="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_group=$sql_gid\"><b>&nbsp;$sql_groupename&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\">&nbsp;$text_choix_group&nbsp;</td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_comment&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


// affiche pour un groupe des cases à cocher devant les resp et grand_resp possibles pour les selectionner.
function affiche_gestion_groupes_responsables($choix_group, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_resp') .":</H3>\n";


	/***********************/
	/* Affichage Groupe    */
	/***********************/
	// Récuperation des informations :
	$sql_gr = 'SELECT g_groupename, g_comment, g_double_valid FROM conges_groupe WHERE g_gid='.SQL::quote($choix_group);
	$ReqLog_gr = SQL::query($sql_gr);

	$resultat_gr = $ReqLog_gr->fetch_array();
	$sql_groupename=$resultat_gr["g_groupename"] ;
	$sql_comment=$resultat_gr["g_comment"] ;
	$sql_double_valid=$resultat_gr["g_double_valid"] ;

	// AFFICHAGE NOM DU GROUPE
	echo "<b>$sql_groupename</b><br><br>\n\n";

	//on rempli un tableau de tous les responsables avec le login, le nom, le prenom (tableau de tableau à 3 cellules
	// Récuperation des responsables :
	$tab_resp=array();
	$sql_resp = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_is_resp='Y' ORDER BY u_nom, u_prenom "  ;
	$ReqLog_resp = SQL::query($sql_resp);

	while($resultat_resp=$ReqLog_resp->fetch_array())
	{
		$tab_r=array();
		$tab_r["login"]=$resultat_resp["u_login"];
		$tab_r["nom"]=$resultat_resp["u_nom"];
		$tab_r["prenom"]=$resultat_resp["u_prenom"];
		$tab_resp[]=$tab_r;
	}
	/*****************************************************************************/

	echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
	echo "<table>\n";
	echo "<tr align=\"center\">\n";
	echo "	<td>\n";

		/*******************************************/
		//AFFICHAGE DU TABLEAU DES RESPONSBLES DU GROUPE
		echo "<table class=\"tablo\" width=\"300\">\n";

		// affichage TITRE
		echo "<tr align=\"center\">\n";
		echo "	<td colspan=3><h3>". _('admin_gestion_groupe_resp_responsables') ."</h3></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td class=\"titre\">&nbsp;</td>\n";
		echo "	<td class=\"titre\">&nbsp;". _('divers_personne_maj_1') ."&nbsp;:</td>\n";
		echo "	<td class=\"titre\">&nbsp;". _('divers_login') ."&nbsp;:</td>\n";
		echo "</tr>\n";

		// on rempli un autre tableau des responsables du groupe
		$tab_group=array();
		$sql_gr = 'SELECT gr_login FROM conges_groupe_resp WHERE gr_gid='.SQL::quote($choix_group).' ORDER BY gr_login ';
		$ReqLog_gr = SQL::query($sql_gr);

		while($resultat_gr=$ReqLog_gr->fetch_array())
		{
			$tab_group[]=$resultat_gr["gr_login"];
		}

		// ensuite on affiche tous les responsables avec une case cochée si exist login dans le 2ieme tableau
		$count = count($tab_resp);
		for ($i = 0; $i < $count; $i++)
		{
			$login=$tab_resp[$i]["login"] ;
			$nom=$tab_resp[$i]["nom"] ;
			$prenom=$tab_resp[$i]["prenom"] ;

			if (in_array ($login, $tab_group))
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_resp[$login]\" value=\"$login\" checked>";
				$class="histo-big";
			}
			else
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_resp[$login]\" value=\"$login\">";
				$class="histo";
			}

			echo "<tr>\n";
			echo "	<td class=\"histo\">$case_a_cocher</td>\n";
			echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
			echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n\n";
		/*******************************************/

	// si on a configuré la double validation et que le groupe considéré est a double valid
	if( ($_SESSION['config']['double_validation_conges']==TRUE) && ($sql_double_valid=="Y") )
	{
		echo "	</td>\n";
		echo "	<td width=\"50\">&nbsp;</td>\n";
		echo "	<td>\n";

			/*******************************************/
			//AFFICHAGE DU TABLEAU DES GRANDS RESPONSBLES DU GROUPE
			echo "<table class=\"tablo\" width=\"300\">\n";

			// affichage TITRE
			echo "<tr align=\"center\">\n";
			echo "	<td colspan=3><h3>". _('admin_gestion_groupe_grand_resp_responsables') ."</h3></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"titre\">&nbsp;</td>\n";
			echo "	<td class=\"titre\">&nbsp;". _('divers_personne_maj_1') ."&nbsp;:</td>\n";
			echo "	<td class=\"titre\">&nbsp;". _('divers_login') ."&nbsp;:</td>\n";
			echo "</tr>\n";

			// on rempli un autre tableau des grands responsables du groupe
			$tab_group_grd=array();
			$sql_ggr = 'SELECT ggr_login FROM conges_groupe_grd_resp WHERE ggr_gid='.SQL::quote($choix_group).' ORDER BY ggr_login ';
			$ReqLog_ggr = SQL::query($sql_ggr);

			while($resultat_ggr=$ReqLog_ggr->fetch_array())
			{
				$tab_group_grd[]=$resultat_ggr["ggr_login"];
			}

			// ensuite on affiche tous les grands responsables avec une case cochée si exist login dans le 3ieme tableau
			$count = count($tab_resp);
			for ($i = 0; $i < $count; $i++)
			{
				$login=$tab_resp[$i]["login"] ;
				$nom=$tab_resp[$i]["nom"] ;
				$prenom=$tab_resp[$i]["prenom"] ;

				if (in_array ($login, $tab_group_grd))
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_grd_resp[$login]\" value=\"$login\" checked>";
					$class="histo-big";
				}
				else
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_grd_resp[$login]\" value=\"$login\">";
					$class="histo";
				}

				echo "<tr>\n";
				echo "	<td class=\"histo\">$case_a_cocher</td>\n";
				echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
				echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
				echo "</tr>\n";
			}
			echo "</table>\n\n";
			/*******************************************/
	}

	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>\n\n";


	echo "<input type=\"hidden\" name=\"change_group_responsables\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_group\" value=\"$choix_group\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=group-resp\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}


// modifie, pour un groupe donné,  ses resp et grands_resp
function modif_group_responsables($choix_group, &$checkbox_group_resp, &$checkbox_group_grd_resp,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$result_insert=TRUE;
	$result_insert_2=TRUE;

	//echo "groupe : $choix_group<br>\n";
	// on supprime tous les anciens resp du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del = 'DELETE FROM conges_groupe_resp WHERE gr_gid='.SQL::quote($choix_group);
	$ReqLog_del = SQL::query($sql_del);

	// on supprime tous les anciens grand resp du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del_2 = 'DELETE FROM conges_groupe_grd_resp WHERE ggr_gid='.SQL::quote($choix_group);
	$ReqLog_del_2 = SQL::query($sql_del_2);


	// ajout des resp qui sont dans la checkbox
	if($checkbox_group_resp!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_group_resp as $login => $value)
		{
			$sql_insert = "INSERT INTO conges_groupe_resp SET gr_gid=$choix_group, gr_login='$login' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}

	// ajout des grands resp qui sont dans la checkbox
	if($checkbox_group_grd_resp!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_group_grd_resp as $grd_login => $grd_value)
		{
			$sql_insert_2 = "INSERT INTO conges_groupe_grd_resp SET ggr_gid=$choix_group, ggr_login='$grd_login' "  ;
			$result_insert_2 = SQL::query($sql_insert_2);
		}
	}

	if( ($result_insert==TRUE) && ($result_insert_2==TRUE) )
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	$comment_log = "mofification_responsables_du_groupe : $choix_group" ;
	log_action(0, "", "", $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=group-resp\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}


// affiche le tableau des responsables pour choisir sur lequel on va gerer les groupes dont il est resp
function affiche_choix_responsable_groupes( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_resp_groupe') .":</H3>\n\n";


	// Récuperation des informations :
	$sql_resp = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp='Y' AND u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom"  ;
	$ReqLog_resp = SQL::query($sql_resp);

	/*************************/
	/* Choix Responsable     */
	/*************************/
	// AFFICHAGE TABLEAU
	echo "<h3>". _('admin_aff_choix_resp_titre') ." :</h3>\n";
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr>\n";
	echo "	<td class=\"titre\">&nbsp;". _('divers_responsable_maj_1') ."&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;". _('divers_login') ."&nbsp;</td>\n";
	echo "</tr>\n";

	while ($resultat_resp = $ReqLog_resp->fetch_array())
	{

		$sql_login=$resultat_resp["u_login"] ;
		$sql_nom=$resultat_resp["u_nom"] ;
		$sql_prenom=$resultat_resp["u_prenom"] ;

		$text_choix_resp="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_resp=$sql_login\"><b>&nbsp;$sql_nom&nbsp;$sql_prenom&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\">&nbsp;$text_choix_resp&nbsp;</td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_login&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


// affiche pour un resp des cases à cocher devant les groupes possibles pour les selectionner.
function affiche_gestion_responsable_groupes($choix_resp, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_resp_groupe') .":</H3>\n\n";

	//echo "resp = $choix_resp<br>\n";
	/****************************/
	/* Affichage Responsable    */
	/****************************/
	// Récuperation des informations :
	$sql_r = 'SELECT u_nom, u_prenom FROM conges_users WHERE u_login=\''.SQL::quote($choix_resp).'\'';
	$ReqLog_r = SQL::query($sql_r);

	$resultat_r = $ReqLog_r->fetch_array();
	$sql_nom=$resultat_r["u_nom"] ;
	$sql_prenom=$resultat_r["u_prenom"] ;

	echo "<b>$sql_prenom $sql_nom</b><br><br>\n";

	//on rempli un tableau de tous les groupe avec le groupename, le commentaire (tableau de tableaux à 3 cellules)
	// Récuperation des groupes :
	$tab_groupe=array();
	$sql_groupe = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename "  ;
	$ReqLog_groupe = SQL::query($sql_groupe);

	while($resultat_groupe=$ReqLog_groupe->fetch_array())
	{
		$tab_g=array();
		$tab_g["gid"]=$resultat_groupe["g_gid"];
		$tab_g["group"]=$resultat_groupe["g_groupename"];
		$tab_g["comment"]=$resultat_groupe["g_comment"];
		$tab_groupe[]=$tab_g;
	}

	//on rempli un tableau de tous les groupes a double validation avec le groupename, le commentaire (tableau de tableau à 3 cellules)
	$tab_groupe_dbl_valid=array();
	$sql_g2 = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe WHERE g_double_valid='Y' ORDER BY g_groupename "  ;
	$ReqLog_g2 = SQL::query($sql_g2);

	while($resultat_groupe_2=$ReqLog_g2->fetch_array())
	{
		$tab_g_2=array();
		$tab_g_2["gid"]=$resultat_groupe_2["g_gid"];
		$tab_g_2["group"]=$resultat_groupe_2["g_groupename"];
		$tab_g_2["comment"]=$resultat_groupe_2["g_comment"];
		$tab_groupe_dbl_valid[]=$tab_g_2;
	}

	/*****************************************************************************/

	echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "	<td>\n";

		/*******************************************/
		//AFFICHAGE DU TABLEAU DES GROUPES DONT RESP EST RESPONSABLE
		echo "<table class=\"tablo\" width=\"300\">\n";

		// affichage TITRE
		echo "<tr align=\"center\">\n";
		echo "	<td colspan=3><h3>". _('divers_responsable_maj_1') ."</h3></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td class=\"titre\">&nbsp;</td>\n";
		echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_groupe') ."&nbsp;:</td>\n";
		echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_libelle') ."&nbsp;:</td>\n";
		echo "</tr>\n";

		// on rempli un autre tableau des groupes dont resp est responsables
		$tab_resp=array();
		$sql_r = 'SELECT gr_gid FROM conges_groupe_resp WHERE gr_login=\''.SQL::quote($choix_resp).'\' ORDER BY gr_gid ';
		$ReqLog_r = SQL::query($sql_r);

		while($resultat_r=$ReqLog_r->fetch_array())
		{
			$tab_resp[]=$resultat_r["gr_gid"];
		}

		// ensuite on affiche tous les groupes avec une case cochée si exist groupename dans le 2ieme tableau
		$count = count($tab_groupe);
		for ($i = 0; $i < $count; $i++)
		{
			$gid=$tab_groupe[$i]["gid"] ;
			$group=$tab_groupe[$i]["group"] ;
			$comment=$tab_groupe[$i]["comment"] ;

			if (in_array ($gid, $tab_resp))
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_resp_group[$gid]\" value=\"$gid\" checked>";
				$class="histo-big";
			}
			else
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_resp_group[$gid]\" value=\"$gid\">";
				$class="histo";
			}

			echo "<tr>\n";
			echo "	<td class=\"histo\">$case_a_cocher</td>\n";
			echo "	<td class=\"$class\"> $group </td>\n";
			echo "	<td class=\"$class\"> $comment </td>\n";
			echo "</tr>\n";
		}

		echo "</table>\n\n";
		/*******************************************/

	// si on a configuré la double validation
	if($_SESSION['config']['double_validation_conges']==TRUE)
	{
		echo "	</td>\n";
		echo "	<td width=\"50\">&nbsp;</td>\n";
		echo "	<td>\n";

			/*******************************************/
			//AFFICHAGE DU TABLEAU DES GROUPES DONT RESP EST GRAND RESPONSABLE
			echo "<table class=\"tablo\" width=\"300\">\n";

			// affichage TITRE
			echo "<tr align=\"center\">\n";
			echo "	<td colspan=3><h3>". _('divers_grand_responsable_maj_1') ."</h3></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"titre\">&nbsp;</td>\n";
			echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_groupe') ."&nbsp;:</td>\n";
			echo "	<td class=\"titre\">&nbsp;". _('admin_groupes_libelle') ."&nbsp;:</td>\n";
			echo "</tr>\n";

			// on rempli un autre tableau des groupes dont resp est GRAND responsables
			$tab_grd_resp=array();
			$sql_gr = 'SELECT ggr_gid FROM conges_groupe_grd_resp WHERE ggr_login=\''.SQL::quote($choix_resp).'\' ORDER BY ggr_gid ';
			$ReqLog_gr = SQL::query($sql_gr);

			while($resultat_gr=$ReqLog_gr->fetch_array())
			{
				$tab_grd_resp[]=$resultat_gr["ggr_gid"];
			}

			// ensuite on affiche tous les groupes avec une case cochée si exist groupename dans le 2ieme tableau
			$count = count($tab_groupe_dbl_valid);
			for ($i = 0; $i < $count; $i++)
			{
				$gid=$tab_groupe_dbl_valid[$i]["gid"] ;
				$group=$tab_groupe_dbl_valid[$i]["group"] ;
				$comment=$tab_groupe_dbl_valid[$i]["comment"] ;

				if (in_array($gid, $tab_grd_resp))
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_grd_resp_group[$gid]\" value=\"$gid\" checked>";
					$class="histo-big";
				}
				else
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_grd_resp_group[$gid]\" value=\"$gid\">";
					$class="histo";
				}

				echo "<tr>\n";
				echo "	<td class=\"histo\">$case_a_cocher</td>\n";
				echo "	<td class=\"$class\"> $group </td>\n";
				echo "	<td class=\"$class\"> $comment </td>\n";
				echo "</tr>\n";
			}

			echo "</table>\n\n";
			/*******************************************/
	}

	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>\n\n";


	echo "<input type=\"hidden\" name=\"change_responsable_group\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_resp\" value=\"$choix_resp\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=resp-group\" method=\"POST\">\n"  ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}


// modifie, pour un resp donné,  les groupes dont il est resp et grands_resp
function modif_resp_groupes($choix_resp, &$checkbox_resp_group, &$checkbox_grd_resp_group,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();


	$result_insert=TRUE;
	$result_insert_2=TRUE;

	//echo "responsable : $choix_resp<br>\n";
	// on supprime tous les anciens resps du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del = 'DELETE FROM conges_groupe_resp WHERE gr_login=\''.SQL::quote($choix_resp).'\'';
	$ReqLog_del = SQL::query($sql_del);

	// on supprime tous les anciens grands resps du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del_2 = 'DELETE FROM conges_groupe_grd_resp WHERE ggr_login=\''.SQL::quote($choix_resp).'\'';
	$ReqLog_del_2 = SQL::query($sql_del_2);

	// ajout des resp qui sont dans la checkbox
	if($checkbox_resp_group!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_resp_group as $gid => $value)
		{
			$sql_insert = "INSERT INTO conges_groupe_resp SET gr_gid=$gid, gr_login='$choix_resp' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}

	// ajout des grands resp qui sont dans la checkbox
	if($checkbox_grd_resp_group!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_grd_resp_group as $grd_gid => $value)
		{
			$sql_insert_2 = "INSERT INTO conges_groupe_grd_resp SET ggr_gid=$grd_gid, ggr_login='$choix_resp' "  ;
			$result_insert_2 = SQL::query($sql_insert_2);
		}
	}

	if(($result_insert==TRUE) && ($result_insert_2==TRUE) )
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	$comment_log = "mofification groupes dont $choix_resp est responsable ou grand responsable" ;
	log_action(0, "", $choix_resp, $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=resp-group\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}


// on a créé 2 tableaux (1 avec les noms + prénoms, 1 avec les login) passés en parametre
// recup_users_from_ldap interroge le ldap et rempli les 2 tableaux (passés par reference)
function recup_users_from_ldap(&$tab_ldap, &$tab_login, $DEBUG=FALSE)
{
	// cnx à l'annuaire ldap :
	$ds = ldap_connect($_SESSION['config']['ldap_server']);
	if($_SESSION['config']['ldap_protocol_version'] != 0)
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, $_SESSION['config']['ldap_protocol_version']) ;
	if ($_SESSION['config']['ldap_user'] == "")
		$bound = ldap_bind($ds);  // connexion anonyme au serveur
	else
		$bound = ldap_bind($ds, $_SESSION['config']['ldap_user'], $_SESSION['config']['ldap_pass']);

	// recherche des entrées :
	if ($_SESSION['config']['ldap_filtre_complet'] != "")
		$filter = $_SESSION['config']['ldap_filtre_complet'];
	else
		$filter = "(&(".$_SESSION['config']['ldap_nomaff']."=*)(".$_SESSION['config']['ldap_filtre']."=".$_SESSION['config']['ldap_filrech']."))";

	$sr   = ldap_search($ds, $_SESSION['config']['searchdn'], $filter);
	$data = ldap_get_entries($ds,$sr);

	foreach ($data as $info)
	{
		$ldap_libelle_login=$_SESSION['config']['ldap_login'];
		$ldap_libelle_nom=$_SESSION['config']['ldap_nom'];
		$ldap_libelle_prenom=$_SESSION['config']['ldap_prenom'];
		$login = $info[$ldap_libelle_login][0];
		$nom = strtoupper(utf8_decode($info[$ldap_libelle_nom][0]))." ".utf8_decode($info[$ldap_libelle_prenom][0]);
		// concaténation NOM Prénom
		// utf8_decode permet de supprimer les caractères accentués mal interprêtés...
		array_push($tab_ldap, $nom);
		array_push($tab_login, $login);
	}
}

