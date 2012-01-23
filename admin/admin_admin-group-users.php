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


defined( '_PHP_CONGES' ) or die( 'Restricted access' );

	$change_group_users	= getpost_variable('change_group_users') ;
	$change_user_groups	= getpost_variable('change_user_groups') ;
	$choix_group		= getpost_variable('choix_group') ;
	$choix_user			= getpost_variable('choix_user') ;

	if($change_group_users=="ok")
	{
		$checkbox_group_users	= getpost_variable('checkbox_group_users');
		modif_group_users($choix_group, $checkbox_group_users, $DEBUG);
	}
	elseif($change_user_groups=="ok")
	{
		$checkbox_user_groups	= getpost_variable('checkbox_user_groups');
		modif_user_groups($choix_user, $checkbox_user_groups,  $DEBUG);
	}
	else
	{
		affiche_choix_gestion_groupes_users($choix_group, $choix_user, $DEBUG);
	}

	
/*********************************************************************************/
/*  FONCTIONS   */
/*********************************************************************************/


function affiche_choix_gestion_groupes_users($choix_group, $choix_user, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];


	if( $choix_group!="" )     // si un groupe choisi : on affiche la gestion par groupe
	{
		affiche_gestion_groupes_users($choix_group, $DEBUG);
	}
	elseif( $choix_user!="" )     // si un user choisi : on affiche la gestion par user
	{
		affiche_gestion_user_groupes($choix_user, $DEBUG);
	}
	else    // si pas de groupe ou de user choisi : on affiche les choix
	{
		echo "<table>\n";
		echo "<tr>\n";
			echo "<td valign=\"top\">\n";
			affiche_choix_groupes_users($DEBUG);
			echo "</td>\n";
			echo "<td valign=\"top\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
			echo "<td valign=\"top\">\n";
			affiche_choix_user_groupes($DEBUG);
			echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

}


function affiche_tableau_affectation_user_groupes($choix_user,  $DEBUG=FALSE)
{

	//AFFICHAGE DU TABLEAU DES GROUPES DU USER
	echo "<table class=\"tablo\">\n";

	// affichage TITRE
	echo "<thead>\n";
	echo "<tr>\n";
	if($choix_user=="")
		echo "	<td colspan=3><h3>". _('admin_gestion_groupe_users_group_of_new_user') ." :</h3></td>\n";
	else
		echo "	<td colspan=3><h3>". _('admin_gestion_groupe_users_group_of_user') ." <b> $choix_user </b> :</h3></td>\n";

	echo "</tr>\n";

	echo "<tr>\n";
	echo "	<td>&nbsp;</td>\n";
	echo "	<td>&nbsp;". _('admin_groupes_groupe') ."&nbsp;:</td>\n";
	echo "	<td>&nbsp;". _('admin_groupes_libelle') ."&nbsp;:</td>\n";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";

	// affichage des groupes

	//on rempli un tableau de tous les groupes avec le nom et libellé (tableau de tableau à 3 cellules)
	$tab_groups=array();
	$sql_g = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename "  ;
	$ReqLog_g = SQL::query($sql_g);

	while($resultat_g=$ReqLog_g->fetch_array())
	{
		$tab_gg=array();
		$tab_gg["gid"]=$resultat_g["g_gid"];
		$tab_gg["groupename"]=$resultat_g["g_groupename"];
		$tab_gg["comment"]=$resultat_g["g_comment"];
		$tab_groups[]=$tab_gg;
	}

	$tab_user="";
	// si le user est connu
	// on rempli un autre tableau des groupes du user
	if($choix_user!="")
	{
		$tab_user=array();
		$sql_gu = 'SELECT gu_gid FROM conges_groupe_users WHERE gu_login=\''.SQL::quote($choix_user).'\' ORDER BY gu_gid ';
		$ReqLog_gu = SQL::query($sql_gu);

		while($resultat_gu=$ReqLog_gu->fetch_array())
		{
			$tab_user[]=$resultat_gu["gu_gid"];
		}
	}

	// ensuite on affiche tous les groupes avec une case cochée si existe le gid dans le 2ieme tableau
	$count = count($tab_groups);
	for ($i = 0; $i < $count; $i++)
	{
		$gid=$tab_groups[$i]["gid"] ;
		$group=$tab_groups[$i]["groupename"] ;
		$libelle=$tab_groups[$i]["comment"] ;

		if ( ($tab_user!="") && (in_array ($gid, $tab_user)) )
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_user_groups[$gid]\" value=\"$gid\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_user_groups[$gid]\" value=\"$gid\">";
			$class="histo";
		}

		echo '<tr class="'.(!($i%2)?'i':'p').'">';
		echo "	<td>$case_a_cocher</td>\n";
		echo "	<td class=\"$class\">&nbsp;$group&nbsp</td>\n";
		echo "	<td class=\"$class\">&nbsp;$libelle&nbsp;</td>\n";
		echo "</tr>\n";
	}

	echo "<tbody>\n";
	echo "</table>\n\n";
}


function affiche_choix_user_groupes( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_user_groupe') .":</H3>\n\n";


	/********************/
	/* Choix User       */
	/********************/
	// Récuperation des informations :
	$sql_user = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom"  ;

	// AFFICHAGE TABLEAU
	echo "<h3>". _('admin_aff_choix_user_titre') ." :</h3>\n";
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "<td>&nbsp;". _('divers_nom_maj_1') ."  ". _('divers_prenom_maj_1') ."&nbsp;</td>\n";
	echo "<td>&nbsp;". _('divers_login_maj_1') ."&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";

	$i = true;
	$ReqLog_user = SQL::query($sql_user);
	while ($resultat_user = $ReqLog_user->fetch_array())
	{

		$sql_login=$resultat_user["u_login"] ;
		$sql_nom=$resultat_user["u_nom"] ;
		$sql_prenom=$resultat_user["u_prenom"] ;

		$choix="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-users&choix_user=$sql_login\"><b>&nbsp;$sql_nom $sql_prenom&nbsp;</b></a>" ;

		echo '<tr class="'.($i?'i':'p').'">';
		echo "<td>&nbsp;$choix&nbsp;</td>\n";
		echo "<td>&nbsp;$sql_login&nbsp;</td>\n";
		echo "</tr>\n";
		$i = !$i;
	}
	echo "</tbody>\n\n";
	echo "</table>\n\n";

}


function affiche_gestion_user_groupes($choix_user,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_user_groupe') .":</H3>\n\n";


	/************************/
	/* Affichage Groupes    */
	/************************/

/*	// Récuperation des informations :
	$sql_u = "SELECT u_nom, u_prenom FROM conges_users WHERE u_login='$choix_user'"  ;
	$ReqLog_u = SQL::query($sql_u);

	$resultat_u = $ReqLog_u->fetch_array();
	$sql_nom=$resultat_u["u_nom"] ;
	$sql_prenom=$resultat_u["u_prenom"] ;
*/

	echo '<form action="'.$PHP_SELF.'?session='.$session.'&onglet='.$onglet.'" method="POST">';

	affiche_tableau_affectation_user_groupes($choix_user,  $DEBUG);

	echo "<input type=\"hidden\" name=\"change_user_groups\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_user\" value=\"$choix_user\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}



function affiche_choix_groupes_users($DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_user') .":</H3>\n\n";


	/********************/
	/* Choix Groupe     */
	/********************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename"  ;

	// AFFICHAGE TABLEAU
	echo "<h3>". _('admin_aff_choix_groupe_titre') ." :</h3>\n";
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "	<td>&nbsp;". _('admin_groupes_groupe') ."&nbsp;</td>\n";
	echo "	<td>&nbsp;". _('admin_groupes_libelle') ."&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";

	$i = true;
	$ReqLog_gr = SQL::query($sql_gr);
	while ($resultat_gr = $ReqLog_gr->fetch_array())
	{

		$sql_gid=$resultat_gr["g_gid"] ;
		$sql_group=$resultat_gr["g_groupename"] ;
		$sql_comment=$resultat_gr["g_comment"] ;

		$choix_group="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-users&choix_group=$sql_gid\"><b>&nbsp;$sql_group&nbsp;</b></a>" ;

		echo '<tr class="'.($i?'i':'p').'">';
		echo "<td><b>&nbsp;$choix_group&nbsp;</b></td>\n";
		echo "<td>&nbsp;$sql_comment&nbsp;</td>\n";
		echo "</tr>\n";
		$i = !$i;
	}
	echo "</tbody>\n";
	echo "</table>\n\n";

}


function affiche_gestion_groupes_users($choix_group, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_user') .":</H3>\n\n";


	/************************/
	/* Affichage Groupes    */
	/************************/
	// Récuperation des informations :
	$sql_gr = 'SELECT g_groupename, g_comment FROM conges_groupe WHERE g_gid='.SQL::quote($choix_group);
	$ReqLog_gr = SQL::query($sql_gr);
	$resultat_gr = $ReqLog_gr->fetch_array();
	$sql_group=$resultat_gr["g_groupename"] ;
	$sql_comment=$resultat_gr["g_comment"] ;


	echo '<form action="'.$PHP_SELF.'?session='.$session.'&onglet='.$onglet.'" method="POST">';

	//AFFICHAGE DU TABLEAU DES USERS DU GROUPE
	echo "<table class=\"tablo\">\n";

	// affichage TITRE
	echo "<thead>\n";
	echo "<tr>\n";
	echo "	<td colspan=3><h3>". _('admin_gestion_groupe_users_membres') ." &nbsp;<b>$sql_group&nbsp;:</b>&nbsp;$sql_comment&nbsp;</h3></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td>&nbsp;</td>\n";
	echo "	<td>&nbsp;". _('divers_personne_maj_1') ."&nbsp;:</td>\n";
	echo "	<td>&nbsp;". _('divers_login') ."&nbsp;:</td>\n";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";

	// affichage des users

	//on rempli un tableau de tous les users avec le login, le nom, le prenom (tableau de tableau à 3 cellules
	// Récuperation des utilisateurs :
	$tab_users=array();
	$sql_users = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom "  ;
	$ReqLog_users = SQL::query($sql_users);

	while($resultat_users=$ReqLog_users->fetch_array())
	{
		$tab_u=array();
		$tab_u["login"]=$resultat_users["u_login"];
		$tab_u["nom"]=$resultat_users["u_nom"];
		$tab_u["prenom"]=$resultat_users["u_prenom"];
		$tab_users[]=$tab_u;
	}
	// on rempli un autre tableau des users du groupe
	$tab_group=array();
	$sql_gu = 'SELECT gu_login FROM conges_groupe_users WHERE gu_gid=\''.SQL::quote($choix_group).'\' ORDER BY gu_login ';
	$ReqLog_gu = SQL::query($sql_gu);

	while($resultat_gu=$ReqLog_gu->fetch_array())
	{
		$tab_group[]=$resultat_gu["gu_login"];
	}

	// ensuite on affiche tous les users avec une case cochée si exist login dans le 2ieme tableau
	$count = count($tab_users);
	for ($i = 0; $i < $count; $i++)
	{
		$login=$tab_users[$i]["login"] ;
		$nom=$tab_users[$i]["nom"] ;
		$prenom=$tab_users[$i]["prenom"] ;

		if (in_array ($login, $tab_group))
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_users[$login]\" value=\"$login\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_users[$login]\" value=\"$login\">";
			$class="histo";
		}

		echo '<tr class="'.(!($i%2)?'i':'p').'">';
		echo "	<td>$case_a_cocher</td>\n";
		echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
		echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
		echo "</tr>\n";
	}

	echo "<tbody>\n";
	echo "</table>\n\n";

	echo "<input type=\"hidden\" name=\"change_group_users\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_group\" value=\"$choix_group\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}



function modif_group_users($choix_group, &$checkbox_group_users,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	// on supprime tous les anciens users du groupe puis on ajoute tous ceux qui sont dans le tableau checkbox (si il n'est pas vide)
	$sql_del = 'DELETE FROM conges_groupe_users WHERE gu_gid='.SQL::quote($choix_group).' ';
	$ReqLog_del = SQL::query($sql_del);
	
	if(is_array($checkbox_group_users) && count ($checkbox_group_users)!=0)
	{
		foreach($checkbox_group_users as $login => $value)
		{
			//$login=$checkbox_group_users[$i] ;
			$sql_insert = "INSERT INTO conges_groupe_users SET gu_gid=$choix_group, gu_login='$login' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}
	else
		$result_insert=TRUE;

	if($result_insert)
		echo  _('form_modif_ok') ."<br><br> \n";
	else
		echo  _('form_modif_not_ok') ."<br><br> \n";

	$comment_log = "mofification_users_du_groupe : $choix_group" ;
	log_action(0, "", "", $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}


function modif_user_groups($choix_user, &$checkbox_user_groups,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$result_insert=commit_modif_user_groups($choix_user, $checkbox_user_groups,  $DEBUG);

	if($result_insert)
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	$comment_log = "mofification_des groupes auxquels $choix_user appartient" ;
	log_action(0, "", $choix_user, $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}


function commit_modif_user_groups($choix_user, &$checkbox_user_groups,  $DEBUG=FALSE)
{


	$result_insert=FALSE;
	// on supprime tous les anciens groupes du user, puis on ajoute tous ceux qui sont dans la tableau checkbox (si il n'est pas vide)
	$sql_del = 'DELETE FROM conges_groupe_users WHERE gu_login=\''.SQL::quote($choix_user).'\'';
	$ReqLog_del = SQL::query($sql_del);

	if( ($checkbox_user_groups!="") && (count ($checkbox_user_groups)!=0) )
	{
		foreach($checkbox_user_groups as $gid => $value)
		{
			$sql_insert = "INSERT INTO conges_groupe_users SET gu_gid=$gid, gu_login='$choix_user' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}
	else
		$result_insert=TRUE;

	return $result_insert;
}

