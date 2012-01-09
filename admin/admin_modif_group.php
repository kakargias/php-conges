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

define('_PHP_CONGES', 1);
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");


$DEBUG=FALSE;
//$DEBUG=TRUE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_admin", $DEBUG);


	// => html sans menu
	
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
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
	// GET // POST
	$group 				= getpost_variable("group");
	$group_to_update 	= getpost_variable("group_to_update");
	$new_groupname 		= getpost_variable("new_groupname");
	$new_comment 		= getpost_variable("new_comment");
	$new_double_valid	= getpost_variable("new_double_valid");
	/*************************************/

	// TITRE
	echo "<H1>". _('admin_modif_groupe_titre') ."</H1>\n\n";


	if($group!="" )
	{
		modifier($group,  $DEBUG);
	}
	elseif($group_to_update!="")
	{
		commit_update($group_to_update, $new_groupname, $new_comment, $new_double_valid,  $DEBUG);
	}
	else
	{
		// renvoit sur la page principale .
		header("Location: admin_index.php?session=$session&onglet=admin-group");
	}


echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";

echo "</CENTER>\n";
echo "</body>\n";
echo "</html>\n";



/**************************************************************************************/
/**********  FONCTIONS  ***************************************************************/

function modifier($group,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	// Récupération des informations
	$sql1 = 'SELECT g_groupename, g_comment, g_double_valid FROM conges_groupe WHERE g_gid = \''.SQL::quote($group).'\'';

	// AFFICHAGE TABLEAU
	echo "<form action=$PHP_SELF?session=$session&group_to_update=".$group." method=\"POST\">\n" ;
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\">". _('admin_groupes_groupe') ."</td>\n";
	echo "<td class=\"histo\">". _('admin_groupes_libelle') ." / ". _('divers_comment_maj_1') ."</td>\n";
	if($_SESSION['config']['double_validation_conges']==TRUE)
		echo "	<td class=\"histo\">". _('admin_groupes_double_valid') ."</td>\n";
	echo "</tr>\n";

	$ReqLog1 = SQL::query($sql1);
	while ($resultat1 = $ReqLog1->fetch_array())
	{
		$sql_groupename=$resultat1["g_groupename"];
		$sql_comment=$resultat1["g_comment"];
		$sql_double_valid=$resultat1["g_double_valid"] ;
	}


	// AFICHAGE DE LA LIGNE DES VALEURS ACTUELLES A MOFIDIER
	echo "<tr>\n";
	echo "<td class=\"histo\">$sql_groupename</td>\n";
	echo "<td class=\"histo\">$sql_comment</td>\n";
	if($_SESSION['config']['double_validation_conges']==TRUE)
			echo "<td class=\"histo\">$sql_double_valid</td>\n";
	echo "</tr>\n";

	// contruction des champs de saisie
	$text_group="<input type=\"text\" name=\"new_groupname\" size=\"30\" maxlength=\"50\" value=\"".$sql_groupename."\">" ;
	$text_comment="<input type=\"text\" name=\"new_comment\" size=\"50\" maxlength=\"200\" value=\"".$sql_comment."\">" ;

	// AFFICHAGE ligne de saisie
	echo "<tr>\n";
	echo "<td class=\"histo\">$text_group</td>\n";
	echo "<td class=\"histo\">$text_comment</td>\n";
	if($_SESSION['config']['double_validation_conges']==TRUE)
	{
		$text_double_valid="<select name=\"new_double_valid\" ><option value=\"N\" ";
		if($sql_double_valid=="N")
			$text_double_valid=$text_double_valid."SELECTED";
		$text_double_valid=$text_double_valid.">N</option><option value=\"Y\" ";
		if($sql_double_valid=="Y")
			$text_double_valid=$text_double_valid."SELECTED";
		$text_double_valid=$text_double_valid.">Y</option></select>" ;
		echo "<td class=\"histo\">$text_double_valid</td>\n";
	}
	echo "</tr>\n";

	echo "</table><br>\n\n";


	echo "<br><input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"admin_index.php?session=$session&onglet=admin-group\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_cancel') ."\">\n";
	echo "</form>\n" ;

}

function commit_update($group_to_update, $new_groupname, $new_comment, $new_double_valid,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$result=TRUE;

	$new_comment=addslashes($new_comment);
	echo "$group_to_update---$new_groupname---$new_comment---$new_double_valid<br>\n";


	// UPDATE de la table conges_groupe
	$sql1 = 'UPDATE conges_groupe  SET g_groupename=\''.$new_groupname.'\', g_comment=\''.$new_comment.'\' , g_double_valid=\''.$new_double_valid.'\' WHERE g_gid=\''.SQL::quote($group_to_update).'\''  ;
	$result1 = SQL::query($sql1);
	if($result1==FALSE)
		$result==FALSE;


	$comment_log = "modif_groupe ($group_to_update) : $new_groupname , $new_comment (double_valid = $new_double_valid)";
	log_action(0, "", "", $comment_log,  $DEBUG);

	if($result==TRUE)
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-group\">";

}


