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

session_start();
if(isset($_GET['session'])) { $session=$_GET['session']; }
if(isset($_POST['session'])) { $session=$_POST['session']; }

//include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($_SESSION['config']['verif_droits']==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php 
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
		if($_SESSION['config']['user_saisie_demande']==TRUE) 
		{
			bouton_traite_demandes();
			printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");
		}

		/*************/
		/* MENU USER */
		/*************/
		affiche_select_user_resp($mysql_link);
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
		if(is_admin($_SESSION['userlogin'], $mysql_link)==TRUE)
		{
			bouton_mode_administrateur();
			printf("<hr align=\"center\" size=\"2\" width=\"95%%\"> \n");
		}
		
		/*************************/
		/* BOUTON DE DECONNEXION */
		/*************************/
		if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
			bouton_deconnexion();

		echo "</center>\n";
	}
	elseif($_SESSION['config']['resp_vertical_menu']==FALSE)  // menu horizontal
	{
		echo "<center>\n";
		echo "<b>MODE RESPONSABLE :</b>\n";
		echo "</center>\n";
		
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
		echo "   <td align=\"center\" valign=\"bottom\">\n";
		bouton_ajout_conges();
		echo "   </td>\n";

		/*************/
		/* MENU USER */
		/*************/
		echo "   <td align=\"center\" valign=\"bottom\">\n";
		affiche_select_user_resp($mysql_link);
		echo "   </td>\n";

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
		if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
		{
			echo "   <td align=\"center\" valign=\"top\">\n";
			bouton_deconnexion();
			echo "   </td>\n";
		}

		echo "</tr>\n";
		echo "</table>\n";
	}
	
	
/*************************************************************************/
/*************************************************************************/

/* BOUTON RETOUR MAIN */
function bouton_retour_main()
{
	$session=session_id();

	printf("<form action=\"resp_main.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n");
	printf("<input type=\"submit\" value=\"Retour page Principale\">\n");
	printf("</form>\n\n");
}

/* BOUTON TRAITE DEMANDES */
function bouton_traite_demandes()
{
	$session=session_id();

	printf("<form action=\"resp_traite_demande_all.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n");
	printf("<input type=\"submit\" value=\"Traiter toutes les Demandes\">\n");
	printf("</form>\n\n");
}

/* MENU USER */
function affiche_select_user_resp($mysql_link)
{
	$session=session_id();

	// Récupération des informations
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
	
	$ReqLog = mysql_query($sql, $mysql_link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());

	// AFFICHAGE LISTE USER
	printf("<form action=\"resp_traite_user.php?session=$session\" method=\"POST\" target=\"MainFrame\">\nsélection d'une personne :<br>\n");
	printf("<select name=\"user_login\">\n") ;
		$ReqLog = mysql_query($sql, $mysql_link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());
		while ($resultat = mysql_fetch_array($ReqLog)) {
			echo "<OPTION VALUE=\"".$resultat["u_login"]."\">".$resultat["u_nom"]." ".$resultat["u_prenom"]."</OPTION>\n" ;
		}
	printf("</select><br>\n");
	printf("<input type=\"submit\" value=\"afficher personne\">\n");
	printf("</form>\n\n");

}

/* BOUTON AJOUT CONGES */
function bouton_ajout_conges()
{
	$session=session_id();

	printf("<form action=\"resp_ajout_conges_all.php?session=$session\" method=\"POST\" target=\"MainFrame\">\n");
	printf("<input type=\"submit\" value=\"Ajout Jours Conges\">\n");
	printf("</form>\n\n");
}

/* BOUTON MODE UTILISATEUR */
function bouton_mode_utilisateur()
{
	$session=session_id();

	if($_SESSION['config']['responsable_virtuel']==FALSE) // teste si resp virtuel ou pas
	{
		printf("<center><b>Mode Utilisateur:</center></b>\n");

		printf("<form action=\"../utilisateur/user_index.php?session=$session\" method=\"POST\" target=\"_blank\">\n");
		printf("<input type=\"submit\" value=\"Mode Utilisateur\">\n");
		printf("</form>\n\n");
	}
}

/* BOUTON MODE ADMINISTRATEUR */
function bouton_mode_administrateur()
{
	$session=session_id();

	printf("<center><b>Mode Administrateur:</center></b>\n");
	
	printf("<form action=\"../admin/admin_index.php?session=$session\" method=\"POST\" target=\"_blank\">\n");
	printf("<input type=\"submit\" value=\"Mode Administrateur\">\n");
	printf("</form>\n\n");
}


?>
</body>
</html>
