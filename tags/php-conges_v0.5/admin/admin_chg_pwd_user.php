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
<?php 
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<TITLE> CONGES : Administrateur</TITLE>\n";
	echo "</head>\n";
	
	echo "<body text=#000000 bgcolor=$config_bgcolor link=#000080 vlink=#800080 alink=#FF0000 background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";
	echo "<CENTER>\n";
	
	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$HTTP_SERVER_VARS['PHP_SELF'];
	// GET
	$u_login=$HTTP_GET_VARS['u_login'];
	$u_login_to_update=$HTTP_GET_VARS['u_login_to_update'];
	// POST
	if(!isset($u_login_to_update))
		$u_login_to_update=$HTTP_POST_VARS['u_login_to_update'];
	$new_pwd1=$HTTP_POST_VARS['new_pwd1'];
	$new_pwd2=$HTTP_POST_VARS['new_pwd2'];
	/*************************************/
	
	// TITRE
	printf("<H1>Modification Password utilisateur : %s .</H1>\n\n", $u_login);

	if(isset($u_login)) {
		modifier($u_login);
	}
	else {
		if(isset($u_login_to_update)) {
			commit_update($u_login_to_update);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session");
		}
	}

function modifier($u_login) {
	global $PHP_SELF;
	global $session;
	
	//connexion mysql
	$link = connexion_mysql() ;
	//connexion mysql

	/********************/
	/* Etat utilisateur */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_is_resp, u_resp_login, u_passwd FROM conges_users WHERE u_login = '$u_login' " ;
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	
	// AFFICHAGE TABLEAU
	printf("<form action=$PHP_SELF?session=$session&u_login_to_update=".$u_login." method=\"POST\">\n" ) ;
	printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"histo\">login</td><td class=\"histo\">Nom</td><td class=\"histo\">Prenom</td><td class=\"histo\">password1</td><td class=\"histo\">password2</td></tr>\n");
	printf("<tr align=\"center\">\n");
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
			$text_pwd1="<input type=\"password\" name=\"new_pwd1\" size=\"10\" maxlength=\"30\" value=\"\">" ;
			$text_pwd2="<input type=\"password\" name=\"new_pwd2\" size=\"10\" maxlength=\"30\" value=\"\">" ;
			printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n", $resultat1["u_login"], $resultat1["u_nom"], $resultat1["u_prenom"], $text_pwd1, $text_pwd2);
		}
	printf("<tr>\n");
	printf("</table>\n\n");
	printf("<input type=\"submit\" value=\"Valider\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"admin_index.php?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;
}

function commit_update($u_login_to_update) {
	global $PHP_SELF;
	global $session;
	global $new_pwd1, $new_pwd2;
	
	if( (strlen($new_pwd1)!=0) && (strlen($new_pwd2)!=0) && (strcmp($new_pwd1, $new_pwd2)==0) ) {
		//connexion mysql
		$link = connexion_mysql() ;
		//connexion mysql

		$sql1 = "UPDATE conges_users  SET u_passwd=password('$new_pwd1') WHERE u_login='$u_login_to_update'" ;
		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session\">";

	}
	else {
	 	printf("<H3> ATTENTION : certain champs saisis ne sont pas valides ...... </H3>\n" ) ;
		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"u_login\" value=\"$u_login_to_update\">\n");
		
		printf("<input type=\"submit\" value=\"Recommencer\">\n");
		printf("</form>\n" ) ;
	}

}

?>
<hr align="center" size="2" width="95%">

</CENTER>
</body>
</html>
