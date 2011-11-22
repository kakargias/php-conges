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

include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==1){ include("../INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<link href="../style.css" rel="stylesheet" type="text/css">
</head>
<body text=#000000 bgcolor=#88A7EF link=#000080 vlink=#800080 alink=#FF0000 >
<?php

/** MAIN **/

	if($config_resp_vertical_menu==1)  // menu vertical
	{
		echo "<BR><BR>\n";
		echo "<center>\n";
		echo "<H3>MENU:</H3>\n";
		echo "<b>Mode Responsable:</b>\n";
		printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");
		
		/**********************/
		/* BOUTON RETOUR MAIN */
		/**********************/
		bouton_retour_main();

		printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");

		/**************************/
		/* BOUTON TRAITE DEMANDES */
		/**************************/
		if($config_user_saisie_demande==1) {
			bouton_traite_demandes();

			printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");
		}

		/*************/
		/* MENU USER */
		/*************/
		affiche_select_user_resp();

		printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");

		/***********************/
		/* BOUTON AJOUT CONGES */
		/***********************/
		bouton_ajout_conges();

		printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");

		/***************************/
		/* BOUTON MODE UTILISATEUR */
		/***************************/
		bouton_mode_utilisateur();

		printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");

		/******************************/
		/* BOUTON MODE ADMINISTRATEUR */
		/******************************/
		bouton_mode_administrateur();

		printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");
		
		/*************************/
		/* BOUTON DE DECONNEXION */
		/*************************/
		if(($config_auth==1)&&($config_verif_droits!=1))
			bouton_deconnexion();

		echo "</center>\n";
	}
	elseif($config_resp_vertical_menu==0)  // menu horizontal
	{
		echo "<center>\n";
		echo "<b>MODE RESPONSABLE :</b>\n";
		echo "</center>\n";
		
		echo "<table cellpadding=\"0\" cellspacing=\"6\" border=\"0\" align=\"center\">\n";
		echo "<tr>\n";
		echo "   <td align=\"center\" valign=\"bottom\">\n";
		/**********************/
		/* BOUTON RETOUR MAIN */
		/**********************/
		bouton_retour_main();

		echo "   </td>\n";
		echo "   <td align=\"center\" valign=\"bottom\">\n";

		/**************************/
		/* BOUTON TRAITE DEMANDES */
		/**************************/
		if($config_user_saisie_demande==1) {
			bouton_traite_demandes();

		echo "   </td>\n";
		echo "   <td align=\"center\" valign=\"bottom\">\n";
		}

		/***********************/
		/* BOUTON AJOUT CONGES */
		/***********************/
		bouton_ajout_conges();

		echo "   </td>\n";
		echo "   <td align=\"center\" valign=\"bottom\">\n";

		/*************/
		/* MENU USER */
		/*************/
		affiche_select_user_resp();

		echo "   </td>\n";
		echo "   <td align=\"center\" valign=\"bottom\">\n";

		/***************************/
		/* BOUTON MODE UTILISATEUR */
		/***************************/
		bouton_mode_utilisateur();

		echo "   </td>\n";
		echo "   <td align=\"center\" valign=\"bottom\">\n";

		/******************************/
		/* BOUTON MODE ADMINISTRATEUR */
		/******************************/
		bouton_mode_administrateur();
	
		echo "   </td>\n";
		echo "   <td align=\"center\" valign=\"bottom\">\n";

		/*************************/
		/* BOUTON DE DECONNEXION */
		/*************************/
		if(($config_auth==1)&&($config_verif_droits!=1))
			bouton_deconnexion();

		echo "   </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
	
	
/*************************************************************************/
/*************************************************************************/

/* BOUTON RETOUR MAIN */
function bouton_retour_main()
{
global $session;

	printf("<form action=\"resp_main.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n<br>\n");
	printf("<input type=\"submit\" value=\"Retour page Principale\">\n");
	printf("</form>\n\n");
}

/* BOUTON TRAITE DEMANDES */
function bouton_traite_demandes()
{
global $session;

	printf("<form action=\"resp_traite_demande_all.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n<br>\n");
	printf("<input type=\"submit\" value=\"Traiter toutes les Demandes\">\n");
	printf("</form>\n\n");
}

/* MENU USER */
function affiche_select_user_resp()
{
global $session, $session_username;
global $config_responsable_virtuel ;

	//connexion mysql
	$link = connexion_mysql() ;

	// Récupération des informations
	if($config_responsable_virtuel==0)
		$sql = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste FROM conges_users WHERE u_resp_login = '$session_username' ORDER BY u_nom";
	else
		$sql = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste FROM conges_users WHERE u_login != 'conges' ORDER BY u_nom";
	
	$ReqLog = mysql_query($sql, $link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());

	// AFFICHAGE LISTE USER
	printf("<form action=\"resp_traite_user.php?session=$session\" method=\"POST\" target=\"MainFrame\">\nsélection d'une personne :<br>\n");
	printf("<select name=\"user_login\">\n") ;
		$ReqLog = mysql_query($sql, $link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());
		while ($resultat = mysql_fetch_array($ReqLog)) {
			printf("<OPTION VALUE=%s>%s %s</OPTION>\n", $resultat["u_login"], $resultat["u_nom"], $resultat["u_prenom"]);
		}
	printf("</select><br><br>\n");
	printf("<input type=\"submit\" value=\"afficher personne\">\n");
	printf("</form>\n\n");

	mysql_close($link);
}

/* BOUTON AJOUT CONGES */
function bouton_ajout_conges()
{
global $session, $session_username;

		printf("<form action=\"resp_ajout_conges_all.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n<br>\n");
		printf("<input type=\"submit\" value=\"Ajout Jours Conges\">\n");
		printf("</form>\n\n");
}

/* BOUTON MODE UTILISATEUR */
function 	bouton_mode_utilisateur()
{
global $session, $session_username;
global $config_responsable_virtuel;

	if($config_responsable_virtuel==0) // teste si resp virtuel ou pas
	{
		printf("<center><b>Mode Utilisateur:</center></b>\n");

		printf("<form action=\"../utilisateur/user_index.php?session=$session\" method=\"POST\" target=\"_blank\">\n<br>\n");
		printf("<input type=\"submit\" value=\"Mode Utilisateur\">\n");
		printf("</form>\n\n");
	}
}

/* BOUTON MODE ADMINISTRATEUR */
function bouton_mode_administrateur()
{
global $session, $session_username;

	printf("<center><b>Mode Administrateur:</center></b>\n");
	
	printf("<form action=\"../admin/admin_index.php?session=$session\" method=\"POST\" target=\"_blank\">\n<br>\n");
	printf("<input type=\"submit\" value=\"Mode Administrateur\">\n");
	printf("</form>\n\n");
}


?>
</body>
</html>
