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
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['group'])) { $group=$_GET['group']; }
	if(isset($_GET['group_to_delete'])) { $group_to_delete=$_GET['group_to_delete']; }
	// POST
	if(!isset($group))
		if(isset($_POST['group'])) { $group=$_POST['group']; }
	if(!isset($group_to_delete))
		if(isset($_POST['group_to_delete'])) { $group_to_delete=$_POST['group_to_delete']; }
	/*************************************/
	
	// TITRE
	if(isset($group))
		printf("<H1>Suppression Groupe : %s .</H1>\n\n", $group);
	elseif(isset($group_to_delete))
		printf("<H1>Suppression Groupe : %s .</H1>\n\n", $group_to_delete);

		
	if(isset($group)) {
		confirmer($group);
	}
	else {
		if(isset($group_to_delete)) {
			suppression($group_to_delete);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session&onglet=admin-group");
		}
	}

function confirmer($group) {
	global $PHP_SELF;
	global $session;
	
	//connexion mysql
	$link = connexion_mysql() ;

	/*******************/
	/* Groupe en cours */
	/*******************/
	// R�cup�ration des informations
	$sql1 = "SELECT g_comment FROM conges_groupe WHERE g_groupename = '$group' "  ;
	// AFFICHAGE TABLEAU

	printf("<form action=\"$PHP_SELF?session=$session&group_to_delete=$group\" method=\"POST\">\n" ) ;
	printf("<table cellpadding=\"2\" class=\"tablo\">\n");
	printf("<tr align=\"center\"><td class=\"histo\"><b>Groupe</b></td><td class=\"histo\"><b>Libell� / Commentaire</b></td></tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	printf("<tr>\n");
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		printf("<td class=\"histo\">&nbsp;$group&nbsp;</td><td class=\"histo\">&nbsp;%s&nbsp;</td>\n", 
				$resultat1["g_comment"]);
	}
	printf("</tr>\n");
	printf("</table><br>\n\n");
	printf("<input type=\"submit\" value=\"Supprimer\">\n");
	printf("</form>\n" ) ;
	
	printf("<form action=\"admin_index.php?session=$session&onglet=admin-group\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;

	mysql_close($link);
}

function suppression($group_to_delete) {
	global $PHP_SELF;
	global $session;

	//connexion mysql
	$link = connexion_mysql() ;
	
	$sql1 = "DELETE FROM conges_groupe WHERE g_groupename = '$group_to_delete' " ;
	$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error()) ;

	$sql2 = "DELETE FROM conges_groupe_users WHERE gu_groupename = '$group_to_delete' " ;
	$result2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error()) ;
	
	$sql3 = "DELETE FROM conges_groupe_resp WHERE gr_groupename = '$group_to_delete' " ;
	$result3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error()) ;
	
	mysql_close($link);

	if($result==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-group\">";

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>