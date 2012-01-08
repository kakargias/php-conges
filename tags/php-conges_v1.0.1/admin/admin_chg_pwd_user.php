<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Cong�s
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les 
termes de la Licence Publique G�n�rale GNU publi�e par la Free Software Foundation.
Ce programme est distribu� car potentiellement utile, mais SANS AUCUNE GARANTIE, 
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation 
dans un but sp�cifique. Reportez-vous � la Licence Publique G�n�rale GNU pour plus de d�tails.
Vous devez avoir re�u une copie de la Licence Publique G�n�rale GNU en m�me temps 
que ce programme ; si ce n'est pas le cas, �crivez � la Free Software Foundation, 
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, �tats-Unis.
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
	echo "<TITLE> ".$_SESSION['config']['titre_admin_index']." </TITLE>\n";
	echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";
	
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['u_login'])) { $u_login=$_GET['u_login']; }
	if(isset($_GET['u_login_to_update'])) { $u_login_to_update=$_GET['u_login_to_update']; }
	// POST
	if(!isset($u_login_to_update))
		if(isset($_GET['u_login_to_update'])) { $u_login_to_update=$_POST['u_login_to_update']; }
	if(isset($_POST['new_pwd1'])) { $new_pwd1=$_POST['new_pwd1']; }
	if(isset($_POST['new_pwd2'])) { $new_pwd2=$_POST['new_pwd2']; }
	/*************************************/
	
	
	if(isset($u_login)) {
		printf("<H1>Modification Password utilisateur : %s .</H1>\n\n", $u_login);
		modifier($u_login);
	}
	else {
		if(isset($u_login_to_update)) {
			printf("<H1>Modification Password utilisateur : %s .</H1>\n\n", $u_login_to_update);
			commit_update($u_login_to_update, $new_pwd1, $new_pwd2);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session&onglet=admin-users");
		}
	}

function modifier($u_login) {
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	//connexion mysql

	/********************/
	/* Etat utilisateur */
	/********************/
	// R�cup�ration des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_is_resp, u_resp_login, u_passwd FROM conges_users WHERE u_login = '$u_login' " ;
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	
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

	printf("<form action=\"admin_index.php?session=$session&onglet=admin-users\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;
	
	mysql_close($mysql_link);
}

function commit_update($u_login_to_update, $new_pwd1, $new_pwd2) {
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	if( (strlen($new_pwd1)!=0) && (strlen($new_pwd2)!=0) && (strcmp($new_pwd1, $new_pwd2)==0) ) {
		//connexion mysql
		$mysql_link = connexion_mysql() ;

		$passwd_md5=md5($new_pwd1);
		$sql1 = "UPDATE conges_users  SET u_passwd='$passwd_md5' WHERE u_login='$u_login_to_update'" ;
		$result = mysql_query($sql1, $mysql_link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-users\">";

	}
	else {
	 	printf("<H3> ATTENTION : certain champs saisis ne sont pas valides ...... </H3>\n" ) ;
		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"u_login\" value=\"$u_login_to_update\">\n");
		
		printf("<input type=\"submit\" value=\"Recommencer\">\n");
		printf("</form>\n" ) ;
	}

	mysql_close($mysql_link);
}

?>
<hr align="center" size="2" width="95%">

</CENTER>
</body>
</html>