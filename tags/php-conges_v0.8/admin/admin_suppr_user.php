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
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
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
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['u_login'])) { $u_login=$_GET['u_login']; }
	if(isset($_GET['u_login_to_delete'])) { $u_login_to_delete=$_GET['u_login_to_delete']; }
	// POST
	if(!isset($u_login_to_delete))
		if(isset($_POST['u_login_to_delete'])) { $u_login_to_delete=$_POST['u_login_to_delete']; }
	if(isset($_POST['new_login'])) { $new_login=$_POST['new_login']; }
	if(isset($_POST['new_nom'])) { $new_nom=$_POST['new_nom']; }
	if(isset($_POST['new_prenom'])) { $new_prenom=$_POST['new_prenom']; }
	if(isset($_POST['new_quotite'])) { $new_quotite=$_POST['new_quotite']; }
	if(isset($_POST['new_nb_j_an'])) { $new_nb_j_an=$_POST['new_nb_j_an']; }
	if(isset($_POST['new_solde_jours'])) { $new_solde_jours=$_POST['new_solde_jours']; }
	if(isset($_POST['new_is_resp'])) { $new_is_resp=$_POST['new_is_resp']; }
	if(isset($_POST['new_resp_login'])) { $new_resp_login=$_POST['new_resp_login']; }
	if(isset($_POST['tab_checkbox_sem_imp'])) { $tab_checkbox_sem_imp=$_POST['tab_checkbox_sem_imp']; }
	if(isset($_POST['tab_checkbox_sem_p'])) { $tab_checkbox_sem_p=$_POST['tab_checkbox_sem_p']; }
	/*************************************/
	
	// TITRE
	if(isset($u_login))
		printf("<H1>Suppression Utilisateur : %s .</H1>\n\n", $u_login);
	elseif(isset($u_login_to_delete))
		printf("<H1>Suppression Utilisateur : %s .</H1>\n\n", $u_login_to_delete);

		
	if(isset($u_login)) {
		confirmer($u_login);
	}
	else {
		if(isset($u_login_to_delete)) {
			suppression($u_login_to_delete);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session&onglet=admin-users");
		}
	}

function confirmer($u_login) {
	global $PHP_SELF;
	global $session;
	
	//connexion mysql
	$link = connexion_mysql() ;

	/*****************************/
	/* Etat Utilisateur en cours */
	/*****************************/
	// Récupération des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login = '$u_login' "  ;
	// AFFICHAGE TABLEAU

	printf("<form action=\"$PHP_SELF?session=$session&u_login_to_delete=$u_login\" method=\"POST\">\n" ) ;
	printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"histo\">login</td><td class=\"histo\">Nom</td><td class=\"histo\">Prenom</td></tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	printf("<tr>\n");
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n", 
				$resultat1["u_login"], $resultat1["u_nom"], $resultat1["u_prenom"]);
	}
	printf("</tr>\n");
	printf("</table><br>\n\n");
	printf("<input type=\"submit\" value=\"Supprimer\">\n");
	printf("</form>\n" ) ;
	
	printf("<form action=\"admin_index.php?session=$session&onglet=admin-users\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;

	mysql_close($link);
}

function suppression($u_login_to_delete) {
	global $PHP_SELF;
	global $session;
	//echo($u_login_to_delete."---".$u_login_to_delete."<br>");

	//connexion mysql
	$link = connexion_mysql() ;
	
	$sql1 = "DELETE FROM conges_users WHERE u_login = '$u_login_to_delete' " ;
	$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error()) ;

	$sql2 = "DELETE FROM conges_periode WHERE p_login = '$u_login_to_delete' " ;
	$result2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error()) ;
	
	$sql3 = "DELETE FROM conges_artt WHERE a_login = '$u_login_to_delete' " ;
	$result3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error()) ;
		
	mysql_close($link);

	if($result==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-users\">";

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>
