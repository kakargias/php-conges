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
define('ROOT_PATH', '../');
include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include ROOT_PATH .'fonctions_conges.php' ;
include INCLUDE_PATH .'fonction.php';
include INCLUDE_PATH .'session.php';


$DEBUG=FALSE;
//$DEBUG=TRUE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_admin", $DEBUG);


	header_popup();	
	
	echo "<CENTER>\n";

	/*************************************/
	// recup des parametres reçus 

	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$group = getpost_variable("group");
	$group_to_delete = getpost_variable("group_to_delete");
	/*************************************/

	// TITRE
	echo "<H1>". _('admin_suppr_groupe_titre') ."</H1>\n";


	if($group!="")
	{
		confirmer($group, $DEBUG);
	}
	elseif($group_to_delete!="")
	{
		suppression_group($group_to_delete,  $DEBUG);
	}
	else
	{
		// renvoit sur la page principale .
		redirect( ROOT_PATH .'admin/admin_index.php?session='.$session.'&onglet=admin-group', false);
	}


echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";

echo "</CENTER>\n";
echo "</body>\n";
echo "</html>\n";



/**************************************************************************************/
/**********  FONCTIONS  ***************************************************************/

function confirmer($group, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	/*******************/
	/* Groupe en cours */
	/*******************/
	// Récupération des informations
	$sql1 = 'SELECT g_groupename, g_comment, g_double_valid FROM conges_groupe WHERE g_gid = \''.SQL::quote($group).'\'';
	$ReqLog1 = SQL::query($sql1);

	// AFFICHAGE TABLEAU

	echo "<form action=\"$PHP_SELF?session=$session&group_to_delete=$group\" method=\"POST\">\n"  ;
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\"><b>". _('admin_groupes_groupe') ."</b></td>\n";
	echo "<td class=\"histo\"><b>". _('admin_groupes_libelle') ." / ". _('divers_comment_maj_1') ."</b></td>\n";
	if($_SESSION['config']['double_validation_conges']==TRUE)
		echo "	<td class=\"histo\"><b>". _('admin_groupes_double_valid') ."</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	while ($resultat1 = $ReqLog1->fetch_array()) {
		$sql_groupname=$resultat1["g_groupename"];
		$sql_comment=$resultat1["g_comment"];
		$sql_double_valid=$resultat1["g_double_valid"] ;
		echo "<td class=\"histo\">&nbsp;$sql_groupname&nbsp;</td>\n"  ;
		echo "<td class=\"histo\">&nbsp;$sql_comment&nbsp;</td>\n" ;
		if($_SESSION['config']['double_validation_conges']==TRUE)
			echo "<td class=\"histo\">$sql_double_valid</td>\n";
	}
	echo "</tr>\n";
	echo "</table><br>\n\n";
	echo "<input type=\"submit\" value=\"". _('form_supprim') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"admin_index.php?session=$session&onglet=admin-group\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_cancel') ."\">\n";
	echo "</form>\n" ;

}

function suppression_group($group_to_delete,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$sql1 = 'DELETE FROM conges_groupe WHERE g_gid = '.SQL::quote($group_to_delete);
	$result = SQL::query($sql1);

	$sql2 = 'DELETE FROM conges_groupe_users WHERE gu_gid = '.SQL::quote($group_to_delete);
	$result2 = SQL::query($sql2);

	$sql3 = 'DELETE FROM conges_groupe_resp WHERE gr_gid = '.SQL::quote($group_to_delete);
	$result3 = SQL::query($sql3);

	if($_SESSION['config']['double_validation_conges']==TRUE)
	{
		$sql4 = 'DELETE FROM conges_groupe_grd_resp WHERE ggr_gid = '.SQL::quote($group_to_delete);
        	$result4 = SQL::query($sql4);
	}

	$comment_log = "suppression_groupe ($group_to_delete)";
	log_action(0, "", "", $comment_log,  $DEBUG);

	if($result==TRUE)
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-group\">";

}

