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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}
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
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$group = getpost_variable("group");
	$group_to_delete = getpost_variable("group_to_delete");
	/*************************************/
	
	$DEBUG=FALSE;
	//$DEBUG=TRUE;	
	
	// TITRE
	echo "<H1>Suppression de Groupe.</H1>\n";

		
	if($group!="") 
	{
		confirmer($group, $DEBUG);
	}
	elseif($group_to_delete!="") 
	{
		suppression_group($group_to_delete, $DEBUG);
	}
	else 
	{
		// renvoit sur la page principale .
		header("Location: admin_index.php?session=$session&onglet=admin-group");
	}

function confirmer($group, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;

	/*******************/
	/* Groupe en cours */
	/*******************/
	// Récupération des informations
	$sql1 = "SELECT g_groupename, g_comment FROM conges_groupe WHERE g_gid = '$group' "  ;
	$ReqLog1 = requete_mysql($sql1, $mysql_link, "confirmer", $DEBUG);

	// AFFICHAGE TABLEAU

	echo "<form action=\"$PHP_SELF?session=$session&group_to_delete=$group\" method=\"POST\">\n"  ;
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\"><td class=\"histo\"><b>Groupe</b></td><td class=\"histo\"><b>Libellé / Commentaire</b></td></tr>\n";
	echo "<tr>\n";
	echo "<tr>\n";
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$sql_groupname=$resultat1["g_groupename"];
		$sql_comment=$resultat1["g_comment"];
		echo "<td class=\"histo\">&nbsp;$sql_groupname&nbsp;</td><td class=\"histo\">&nbsp;$sql_comment&nbsp;</td>\n" ; 
	}
	echo "</tr>\n";
	echo "</table><br>\n\n";
	echo "<input type=\"submit\" value=\"Supprimer\">\n";
	echo "</form>\n" ;
	
	echo "<form action=\"admin_index.php?session=$session&onglet=admin-group\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"Cancel\">\n";
	echo "</form>\n" ;

	mysql_close($mysql_link);
}

function suppression_group($group_to_delete, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	$sql1 = "DELETE FROM conges_groupe WHERE g_gid = $group_to_delete " ;
	$result = requete_mysql($sql1, $mysql_link, "suppression_group", $DEBUG);

	$sql2 = "DELETE FROM conges_groupe_users WHERE gu_gid = $group_to_delete " ;
	$result2 = requete_mysql($sql2, $mysql_link, "suppression_group", $DEBUG);
	
	$sql3 = "DELETE FROM conges_groupe_resp WHERE gr_gid = $group_to_delete " ;
	$result3 = requete_mysql($sql3, $mysql_link, "suppression_group", $DEBUG);
	
	mysql_close($mysql_link);

	if($result==TRUE)
		echo " Changements pris en compte avec succes !<br><br> \n";
	else
		echo " ERREUR ! Changements NON pris en compte !<br><br> \n";
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-group\">";

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>
