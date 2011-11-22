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

//session_start();
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
<body text=#000000 bgcolor=#FFFFFF link=#000080 vlink=#800080 alink=#FF0000 background="../img/watback.jpg">
<CENTER>
<?php

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$HTTP_SERVER_VARS['PHP_SELF'];
	// GET
	$p_num=$HTTP_GET_VARS['p_num'];
	// POST
	$tab_champ_saisie=$HTTP_POST_VARS['tab_champ_saisie'];
	/*************************************/
	
	// titre
	printf("<H2>Ajout de congès :</H2>\n\n");
	//connexion mysql
	$link = connexion_mysql() ;
	
	if(!isset($tab_champ_saisie)) {
		saisie();
	}
	else {
		ajout_conges();
	}
	
	mysql_close($link);
	
/*** FONCTIONS ***/

function saisie() {
	global $PHP_SELF, $link;
	global $session, $session_username ;
	global $config_responsable_virtuel ;
	
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
	// Récupération des informations
	if($config_responsable_virtuel==0)
		$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_reste, u_quotite FROM conges_users WHERE u_resp_login = '$session_username' ORDER BY u_nom ";
	else
		$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_reste, u_quotite FROM conges_users WHERE u_login != 'conges' ORDER BY u_nom ";
		
	$ReqLog1 = mysql_query( $sql1, $link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());


	// AFFICHAGE TABLEAU
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">nom</td><td class=\"titre\">prenom</td><td class=\"titre\">Quotité</td><td class=\"titre\">Solde</td><td class=\"titre\">NB jours a ajouter</td></tr>\n");
	$cpt_lignes=0 ;
	$tab_champ_saisie=array();
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		/** sur la ligne ,   **/
		/** le champ de saisie est <input type="text" name="tab_champ_saisie[valeur de u_login]" value="[valeur du nb de jours ajouté saisi]"> */
		$champ_saisie=sprintf("<input type=\"text\" name=\"tab_champ_saisie[%s]\" size=\"6\" maxlength=\"6\" value=\"0\">", $resultat1["u_login"] );
		printf("<td>%s</td><td>%s</td><td>%d%%</td><td>%s</td><td align=\"center\">%s</td>\n", 
				$resultat1["u_nom"], $resultat1["u_prenom"], $resultat1["u_quotite"], affiche_decimal($resultat1["u_nb_jours_reste"]), $champ_saisie );
		printf("</tr>\n");
		$cpt_lignes++ ;
	}
	printf("</table>\n\n");

	printf("<input type=\"submit\" value=\"Valider\">\n");
	printf(" </form> \n");

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"resp_main.php?session=$session\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour Page Principale\">\n");
	printf(" </form> \n");
}

function ajout_conges() {
	global $PHP_SELF, $link;
	global $session, $session_username, $tab_champ_saisie ;

	while($elem_tableau = each($tab_champ_saisie))
	{
		$user_nb_jours_ajout = $elem_tableau['value'] ;
		$valid=verif_saisie_decimal($user_nb_jours_ajout);   //verif la bonne saisie du nombre décimal
		$user_nb_jours_ajout_float =(float) $user_nb_jours_ajout ;
		$user_name=$elem_tableau['key'];
		//echo($user_name."---".$user_nb_jours_ajout_float."<br>");

		/* Modification de la table conges_users */
		$sql1 = "UPDATE conges_users SET u_nb_jours_reste=u_nb_jours_reste+$user_nb_jours_ajout_float WHERE u_login='$user_name' " ;
		/* On valide l'UPDATE dans la table ! */
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());
	}

	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";

}

?>

</CENTER>
</body>
</html>
