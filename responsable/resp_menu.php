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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}


$DEBUG = FALSE ;
//$DEBUG = TRUE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_resp", $DEBUG);


	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	
	echo "<body text=#000000 bgcolor=".$_SESSION['config']['bgcolor']." link=#000080 vlink=#800080 alink=#FF0000>\n";

/** MAIN **/

	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	if($_SESSION['config']['resp_vertical_menu']==TRUE)  // menu vertical
	{
		echo "<BR><BR>\n";
		echo "<center>\n";
		//echo "<H3>MENU:</H3>\n";
		echo "<b>".$_SESSION['lang']['resp_menu_titre']."</b>\n";
		echo "<br><br>\n";
		
		/**********************/
		/* BOUTON RETOUR MAIN */
		/**********************/
		bouton_retour_main();
		echo "<br> \n";
		
		/**************************/
		/* BOUTON TRAITE DEMANDES */
		/**************************/
		if($_SESSION['config']['user_saisie_demande']==TRUE) 
		{
			bouton_traite_demandes();
			echo "<br> \n";
		}

		/*************/
		/* MENU USER */
		/*************/
/*		affiche_select_user_resp($mysql_link, $DEBUG);
		echo "<hr align=\"center\" size=\"2\" width=\"95%\"> \n";
*/
		/***********************/
		/* BOUTON AJOUT CONGES */
		/***********************/
		if($_SESSION['config']['resp_ajoute_conges']==TRUE)  // si le resp peut ajouter des conges
		{
			bouton_ajout_conges();
			echo "<br> \n";
			echo "<hr align=\"center\" size=\"2\" width=\"95%\"> \n";
		}
		
		/***************************/
		/* BOUTON MODE UTILISATEUR */
		/***************************/
		bouton_mode_utilisateur();
		echo "<br> \n";
		
		/******************************/
		/* BOUTON MODE ADMINISTRATEUR */
		/******************************/
		if(is_admin($_SESSION['userlogin'], $mysql_link)==TRUE)
		{
			bouton_mode_administrateur();
			echo "<br> \n";
		}
		
		/*************************/
		/* BOUTON DE DECONNEXION */
		/*************************/
		if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
		{
			echo "<hr align=\"center\" size=\"2\" width=\"95%\"> \n";
			bouton_deconnexion();
		}

		echo "</center>\n";
	}
	elseif($_SESSION['config']['resp_vertical_menu']==FALSE)  // menu horizontal
	{
		/*************************************/
		/*** affichage titre et bouton "deconnexion" ***/
		/*************************************/
		echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>\n";
	
		/*** cellule gauche vide  ***/
		echo "<td width=\"120\" valign=\"middle\" align=\"left\">\n";
		echo "&nbsp;\n";
		echo "</td>\n";
		
		/*** cellule centrale titre  ***/
		echo "<td valign=\"middle\" align=\"center\">\n";
		echo "<h2>".$_SESSION['lang']['resp_menu_titre']."</h2>\n";
		echo "</td>\n";
		
		/*** bouton deconnexion  ***/
		if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
		{
			echo "<td width=\"120\" valign=\"top\" align=\"right\">\n";
			bouton_deconnexion($DEBUG);
			echo "</td>\n";
		}
		
		echo "</tr></table>\n";
	
		/*************************************/
		/*** affichage des bouton ADMIN    ***/
		/*************************************/
		echo "<table cellpadding=\"0\" cellspacing=\"6\" border=\"0\" align=\"center\">\n";
		echo "<tr>\n";
		/**********************/
		/* BOUTON RETOUR MAIN */
		/**********************/
		echo "   <td align=\"center\" valign=\"bottom\">\n";
		bouton_retour_main();
		echo "   </td>\n";

		/**************************/
		/* BOUTON TRAITE DEMANDES */
		/**************************/
		if($_SESSION['config']['user_saisie_demande']==TRUE) 
		{
			echo "   <td align=\"center\" valign=\"bottom\">\n";
			bouton_traite_demandes();
			echo "   </td>\n";
		}

		/***********************/
		/* BOUTON AJOUT CONGES */
		/***********************/
		if($_SESSION['config']['resp_ajoute_conges']==TRUE)  // si le resp peut ajouter des conges
		{
			echo "   <td align=\"center\" valign=\"bottom\">\n";
			bouton_ajout_conges();
			echo "   </td>\n";
		}

		/*************/
		/* MENU USER */
		/*************/
/*		echo "   <td align=\"center\" valign=\"bottom\">\n";
		affiche_select_user_resp($mysql_link, $DEBUG);
		echo "   </td>\n";
*/
		/*** cellule separatrice vide  ***/
		echo "<td width=\"10\">\n";
		echo "&nbsp;\n";
		echo "</td>\n";
		
		/***************************/
		/* BOUTON MODE UTILISATEUR */
		/***************************/
		echo "   <td align=\"center\" valign=\"bottom\">\n";
		bouton_mode_utilisateur();
		echo "   </td>\n";

		/******************************/
		/* BOUTON MODE ADMINISTRATEUR */
		/******************************/
		if(is_admin($_SESSION['userlogin'], $mysql_link)==TRUE)
		{
			echo "   <td align=\"center\" valign=\"bottom\">\n";
			bouton_mode_administrateur();
			echo "   </td>\n";
		}
	
		/*************************/
		/* BOUTON DE DECONNEXION */
		/*************************/
/*		if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
		{
			echo "   <td align=\"center\" valign=\"top\">\n";
			bouton_deconnexion();
			echo "   </td>\n";
		}
*/
		echo "</tr>\n";
		echo "</table>\n";
	}
	
	mysql_close($mysql_link);
	
	echo "</body>\n";
	echo "</html>\n";
	
	
/*************************************************************************/
/*************************************************************************/

/* BOUTON RETOUR MAIN */
function bouton_retour_main()
{
	$session=session_id();

	echo "<form action=\"resp_main.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['resp_menu_button_retour_main']."\">\n";
	echo "</form>\n\n";
}

/* BOUTON TRAITE DEMANDES */
function bouton_traite_demandes()
{
	$session=session_id();

	echo "<form action=\"resp_traite_demande_all.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['resp_menu_button_traite_demande']."\">\n";
	echo "</form>\n\n";
}

/* MENU USER */
function affiche_select_user_resp($mysql_link, $DEBUG=FALSE)
{
	$session=session_id();

	/***********************************/
	// Récup dans un tableau de tableau des informations de tous les users dont $_SESSION['userlogin'] est responsable
	$tab_all_users=recup_infos_all_users_du_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
	
	if($DEBUG==TRUE)
		{ print_r($tab_all_users); echo "<br>\n"; }

	// AFFICHAGE LISTE USER
	echo "<form action=\"resp_traite_user.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n";
	echo "<select name=\"user_login\">\n" ;
	foreach($tab_all_users as $current_login => $tab_current_user)
	{		
		echo "<OPTION VALUE=\"$current_login\">".$tab_current_user['nom']." ".$tab_current_user['prenom']."</OPTION>\n" ;
	}
	echo "</select><br>\n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['resp_menu_button_affiche_user']."\">\n";
	echo "</form>\n\n";


/*	// Récupération des informations
	$sql = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE ";
	if($_SESSION['config']['responsable_virtuel']==TRUE)
		$sql = $sql." u_login != 'conges' ";
	else
	{
		$sql = $sql." u_resp_login = '".$_SESSION['userlogin']."' ";
		if($_SESSION['config']['gestion_groupes'] == TRUE)
		{
			$list_users_group=get_list_users_des_groupes_du_resp($_SESSION['userlogin'], $mysql_link);
			if($list_users_group!="")  //si la liste n'est pas vide ( serait le cas si n'est responsable d'aucun groupe)
				$sql=$sql." OR u_login IN ($list_users_group) ";
		}
	}
	$sql = $sql." ORDER BY u_nom";
	
	$ReqLog = requete_mysql($sql, $mysql_link, "affiche_select_user_resp", $DEBUG);

	// AFFICHAGE LISTE USER
	echo "<form action=\"resp_traite_user.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n";
	echo "<select name=\"user_login\">\n" ;
		while ($resultat = mysql_fetch_array($ReqLog)) 
		{
			echo "<OPTION VALUE=\"".$resultat["u_login"]."\">".$resultat["u_nom"]." ".$resultat["u_prenom"]."</OPTION>\n" ;
		}
	echo "</select><br>\n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['resp_menu_button_affiche_user']."\">\n";
	echo "</form>\n\n";
*/
}

/* BOUTON AJOUT CONGES */
function bouton_ajout_conges()
{
	$session=session_id();

	echo "<form action=\"resp_ajout_conges_all.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['resp_menu_button_ajout_jours']."\">\n";
	echo "</form>\n\n";
}

/* BOUTON MODE UTILISATEUR */
function bouton_mode_utilisateur()
{
	$session=session_id();

	if($_SESSION['config']['responsable_virtuel']==FALSE) // teste si resp virtuel ou pas
	{
//		echo "<center><b>".$_SESSION['lang']['resp_menu_button_mode_user'].":</center></b>\n";

		echo "<form action=\"../utilisateur/user_index.php?session=$session\" method=\"POST\" target=\"_blank\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['resp_menu_button_mode_user']."\">\n";
		echo "</form>\n\n";
	}
}

/* BOUTON MODE ADMINISTRATEUR */
function bouton_mode_administrateur()
{
	$session=session_id();

//	echo "<center><b>".$_SESSION['lang']['resp_menu_button_mode_admin'].":</center></b>\n";
	
	echo "<form action=\"../admin/admin_index.php?session=$session\" method=\"POST\" target=\"_blank\">\n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['resp_menu_button_mode_admin']."\">\n";
	echo "</form>\n\n";
}


?>
