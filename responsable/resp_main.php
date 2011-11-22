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
<body text=#000000 bgcolor=#FFFFFF link=#000080 vlink=#800080 alink=#FF0000 background="../img/watback.jpg">
<CENTER>

<?php
/** MAIN **/

	//connexion mysql
	$link = connexion_mysql() ;
	
	$sql1 = "SELECT u_nom, u_prenom FROM conges_users where u_login = '$session_username' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : user_main.php : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$NOM=$resultat1["u_nom"];
		$PRENOM=$resultat1["u_prenom"];
	}
	
	// TITRE
	if($config_responsable_virtuel==0)
		printf("<H1>%s %s</H1>\n\n", $PRENOM, $NOM);
	else
		printf("<H1>responsable</H1>\n\n");
	
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");

	/****************/
	/* calendrier */
	/****************/
	echo "<br>\n";
	printf("<form action=\"../calendrier.php?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Afficher Calendrier\">\n");
	printf("</form>\n" ) ;
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	
	
	/***********************************/
	// AFFICHAGE ETAT CONGES TOUS USERS
	
	// Récupération des informations
	if($config_responsable_virtuel==0)
		$sql = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_quotite FROM conges_users WHERE u_resp_login = '$session_username' ORDER BY u_nom, u_prenom";
	else
		$sql = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_quotite FROM conges_users WHERE u_login != 'conges' ORDER BY u_nom, u_prenom";	
	$ReqLog = mysql_query($sql, $link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());

	// AFFICHAGE TABLEAU
	printf("<H2>Etat des congès:</H2>\n\n");
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">NOM</td><td class=\"titre\">PRENOM</td><td class=\"titre\">Quotité</td><td class=\"titre\">NB_JOURS_AN</td><td class=\"titre\">SOLDE</td><td class=\"titre\"></td></tr>\n");
	while ($resultat = mysql_fetch_array($ReqLog)) {
			$resp_affich_user="<a href=\"resp_traite_user.php?session=$session&user_login=".$resultat["u_login"]."\">Afficher</a>" ;
			printf("<tr align=\"center\">\n");
			printf("<td>%s</td><td>%s</td><td>%d%%</td><td>%s</td><td>%s</td><td>%s</td>\n", 
					$resultat["u_nom"], $resultat["u_prenom"], $resultat["u_quotite"], affiche_decimal($resultat["u_nb_jours_an"]), affiche_decimal($resultat["u_nb_jours_reste"]), $resp_affich_user);
			printf("</tr>\n");
		}
	printf("</table><br><br>\n\n");

	mysql_close($link);
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");

	echo "</CENTER>\n";
	
	// affichage URL de deconnexion:
	if($config_auth==1) 
	{
		echo "<table width=\"100%\"><tr><td align=\"right\">";
		bouton_deconnexion();
		echo "</td></tr></table>";
	}

?>

</body>
</html>
