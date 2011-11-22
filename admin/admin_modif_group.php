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
	/*** initialisation des variables ***/
	$group=0;
	$group_to_update=0;
	/************************************/
	
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<TITLE> ".$_SESSION['config']['titre_admin_index']." </TITLE>\n";
	echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";
	
	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['group'])) { $group=$_GET['group']; }
	if(isset($_GET['group_to_update'])) { $group_to_update=$_GET['group_to_update']; }
	// POST
	if( (!isset($group_to_update)) || ($group_to_update==0) )
		if(isset($_POST['group_to_update'])) { $group_to_update=$_POST['group_to_update']; }
	if(isset($_POST['new_groupname'])) { $new_groupname=addslashes($_POST['new_groupname']); }
	if(isset($_POST['new_comment'])) { $new_comment=addslashes($_POST['new_comment']); }
	/*************************************/
	
	// TITRE
	printf("<H1>Modification de Groupe.</H1>\n\n", $group);

		
	if( (isset($group)) && ($group!=0) ) {
		modifier($group);
	}
	else {
		if( (isset($group_to_update)) && ($group_to_update!=0) ) {
			commit_update($group_to_update, $new_groupname, $new_comment);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session&onglet=admin-group");
		}
	}

function modifier($group) {
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	/********************/
	/* Etat utilisateur */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT g_groupename, g_comment FROM conges_groupe WHERE g_gid = '$group' " ;
	// AFFICHAGE TABLEAU
	printf("<form action=$PHP_SELF?session=$session&group_to_update=".$group." method=\"POST\">\n" ) ;
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\">Groupe</td>\n";
	echo "<td class=\"histo\">Libellé / Commentaire</td>\n";
	echo "</tr>\n";
	
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$sql_groupename=$resultat1["g_groupename"];
		$sql_comment=$resultat1["g_comment"];
	}
	
	// AFICHAGE DE LA LIGNE DES VALEURS ACTUELLES A MOFIDIER
	echo "<tr>\n";
	echo "<td class=\"histo\">$sql_groupename</td>\n";
	echo "<td class=\"histo\">$sql_comment</td>\n";
	echo "</tr>\n";
	
	// contruction des champs de saisie 
	$text_group="<input type=\"text\" name=\"new_groupname\" size=\"30\" maxlength=\"50\" value=\"".$sql_groupename."\">" ;
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

	mysql_close($mysql_link);

	}
	
function commit_update($group_to_update, $new_groupname, $new_comment) {
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	$result=TRUE;
	
	echo "$group_to_update---$new_groupname---$new_comment<br>\n";
	
	
	// UPDATE de la table conges_groupe
	$sql1 = "UPDATE conges_groupe  SET g_groupename='$new_groupname', g_comment='$new_comment' WHERE g_gid=$group_to_update " ;
	$result1 = mysql_query($sql1, $mysql_link) or die("ERREUR : commit_update() : ".mysql_error());
	if($result1==FALSE)
		$result==FALSE;
	
		
	if($result==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	mysql_close($mysql_link);
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-group\">";

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>

