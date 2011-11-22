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

<?php
	echo "<body text=\"#000000\" bgcolor=$config_bgcolor link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";
	echo "<CENTER>\n";

/** MAIN **/
	// TITRE
	printf("<H1>Modification demande de conges.</H1>\n\n", $p_num);
	printf("<br><br>\n");

	if(isset($p_num)) {
		confirmer($p_num);
	}
	else {
		if(isset($p_num_to_update)) {
			modifier($p_num_to_update);
		}
		else {
			// renvoit sur la page principale .
			header("Location: user_index.php");
		}
	}
	
	
function confirmer($p_num) {
	global $PHP_SELF;
	global $session, $session_username ;
	
	//connexion mysql
	$link = connexion_mysql() ;
	
	// Récupération des informations
	$sql1 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_num FROM conges_periode where p_num = ".$p_num  ;
	// AFFICHAGE TABLEAU

	printf("<form action=\"$PHP_SELF\" method=\"POST\">\n" ) ;
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td></tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	printf("<tr align=\"center\">\n");
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", 
				$resultat1["p_date_deb"], $resultat1["p_date_fin"], affiche_decimal($resultat1["p_nb_jours"]), $resultat1["p_commentaire"]);
		$text_debut="<input type=\"text\" name=\"new_debut\" size=\"10\" maxlength=\"30\" value=\"".$resultat1["p_date_deb"]."\">" ;
		$text_fin="<input type=\"text\" name=\"new_fin\" size=\"10\" maxlength=\"30\" value=\"".$resultat1["p_date_fin"]."\">" ;
		$text_nb_jours="<input type=\"text\" name=\"new_nb_jours\" size=\"10\" maxlength=\"30\" value=\"".$resultat1["p_nb_jours"]."\">" ;
		$text_commentaire="<input type=\"text\" name=\"new_comment\" size=\"10\" maxlength=\"30\" value=\"".$resultat1["p_commentaire"]."\">" ;
	}
	printf("</tr>\n");

	printf("<tr align=\"center\">\n");
	printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", $text_debut, $text_fin, $text_nb_jours, $text_commentaire);
	printf("</tr>\n");

	printf("</table><br>\n\n");
	printf("<input type=\"hidden\" name=\"p_num_to_update\" value=\"$p_num\">\n");
	printf("<input type=\"hidden\" name=\"session\" value=\"$session\">\n");
	printf("<input type=\"submit\" value=\"Valider\">\n");
	printf("</form>\n" ) ;

	//$session="hello";
	printf("<form action=\"user_index.php?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;

	mysql_close($link);
}


function modifier($p_num_to_update) {
	global $PHP_SELF;
	global $session, $session_username ;
	global $new_debut, $new_fin, $new_nb_jours, $new_comment ;
	
	//echo($login."---".$new_debut."---".$new_fin."---".$new_nb_jours."---".$new_comment."<br>");
	echo($new_debut."---".$new_fin."---".$new_nb_jours."---".$new_comment."<br>");
	
	//connexion mysql
	$link = connexion_mysql() ;
	$etat="demande" ;
	$sql1 = "UPDATE conges_periode  SET p_date_deb='$new_debut', p_date_fin='$new_fin', p_nb_jours='$new_nb_jours', p_commentaire='$new_comment' WHERE p_num='$p_num_to_update'" ;

	$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

	mysql_close($link);

	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"user_index.php?session=$session\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");

}
?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>
