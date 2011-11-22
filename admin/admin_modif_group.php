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
	if(isset($_GET['group'])) { $group=$_GET['group']; }
	if(isset($_GET['group_to_update'])) { $group_to_update=$_GET['group_to_update']; }
	// POST
	if(!isset($group_to_update))
		if(isset($_GET['group_to_update'])) { $group_to_update=$_POST['group_to_update']; }
	if(isset($_POST['new_groupname'])) { $new_groupname=$_POST['new_groupname']; }
	if(isset($_POST['new_comment'])) { $new_comment=$_POST['new_comment']; }
	/*************************************/
	
	// TITRE
	if(isset($group))
		printf("<H1>Modification utilisateur : %s .</H1>\n\n", $group);
	elseif(isset($group_to_update))
		printf("<H1>Modification utilisateur : %s .</H1>\n\n", $group_to_update);

		
	if(isset($group)) {
		modifier($group);
	}
	else {
		if(isset($group_to_update)) {
			commit_update($group_to_update);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session&onglet=admin-group");
		}
	}

function modifier($group) {
	global $PHP_SELF;
	global $session;
	
	//connexion mysql
	$link = connexion_mysql() ;
	
	/********************/
	/* Etat utilisateur */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT g_comment FROM conges_groupe WHERE g_groupename = '$group' " ;
	// AFFICHAGE TABLEAU
	printf("<form action=$PHP_SELF?session=$session&group_to_update=".$group." method=\"POST\">\n" ) ;
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\">Groupe</td>\n";
	echo "<td class=\"histo\">Libellé / Commentaire</td>\n";
	echo "</tr>\n";
	
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$sql_comment=$resultat1["g_comment"];
	}
	
	// AFICHAGE DE LA LIGNE DES VALEURS ACTUELLES A MOFIDIER
	echo "<tr>\n";
	echo "<td class=\"histo\">$group</td>\n";
	echo "<td class=\"histo\">$sql_comment</td>\n";
	echo "</tr>\n";
	
	// contruction des champs de saisie 
	$text_group="<input type=\"text\" name=\"new_groupname\" size=\"30\" maxlength=\"50\" value=\"".$group."\">" ;
	$text_comment="<input type=\"text\" name=\"new_comment\" size=\"50\" maxlength=\"200\" value=\"".$sql_comment."\">" ;
	
	// AFFICHAGE ligne de saisie
	echo "<tr>\n";
	echo "<td class=\"histo\">$text_group</td>\n";
	echo "<td class=\"histo\">$text_comment</td>\n";
	echo "</tr>\n";
	
	printf("</table><br>\n\n");
	
	
	printf("<br><input type=\"submit\" value=\"Valider\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"admin_index.php?session=$session&onglet=admin-group\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;

	mysql_close($link);

	}
	
function commit_update($group_to_update) {
	global $PHP_SELF;
	global $session;
	global $new_groupname, $new_comment;
	
	//connexion mysql
	$link = connexion_mysql() ;
	$result=TRUE;
	
	echo "$group_to_update---$new_groupname---$new_comment<br>\n";
	
	
	// UPDATE de la table conges_groupe
	$sql1 = "UPDATE conges_groupe  SET g_groupename='$new_groupname', g_comment='$new_comment' WHERE g_groupename='$group_to_update' " ;
	$result1 = mysql_query($sql1, $link) or die("ERREUR : commit_update() : ".mysql_error());
	if($result1==FALSE)
		$result==FALSE;
	
	
	// Si changement du groupname, (on a dèja updaté la table groupe) on update toutes les autres tables
	// (groupe_users) avec le nouveau groupename
	if($new_group!=$group_to_update)
	{
		// update table conges_groupe_users
		$sql_upd_group_users = "UPDATE conges_groupe_users SET gu_groupename='$new_groupname' WHERE gu_groupename='$group_to_update'" ;
		//echo "sql_upd_group_users = $sql_upd_group_users<br>\n";
		$result4 = mysql_query($sql_upd_group_users, $link) or die("ERREUR : commit_update() : ".mysql_error());
		if($result4==FALSE)
			$result==FALSE;

	}
	
	if($result==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	mysql_close($link);
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-group\">";

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>

