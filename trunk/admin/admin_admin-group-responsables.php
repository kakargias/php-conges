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


	$choix_group    = getpost_variable('choix_group') ;
	$choix_resp     = getpost_variable('choix_resp') ;
	
	$change_group_responsables	= getpost_variable('change_group_responsables') ;
	$change_responsable_group	= getpost_variable('change_responsable_group') ;


	if($change_group_responsables=="ok")
	{
		$checkbox_group_resp		= getpost_variable('checkbox_group_resp') ;
		$checkbox_group_grd_resp	= getpost_variable('checkbox_group_grd_resp') ;
		modif_group_responsables($choix_group, $checkbox_group_resp, $checkbox_group_grd_resp, $DEBUG);
	}
	elseif($change_responsable_group=="ok")
	{
		$checkbox_resp_group		= getpost_variable('checkbox_resp_group') ;
		$checkbox_grd_resp_group	= getpost_variable('checkbox_grd_resp_group') ;
		
		modif_resp_groupes($choix_resp, $checkbox_resp_group, $checkbox_grd_resp_group, $DEBUG);
	}
	else
	{
		affiche_choix_gestion_groupes_responsables($choix_group, $choix_resp, $onglet);
	}

	
/*********************************************************************************/
/*  FONCTIONS   */
/*********************************************************************************/


/*****************************************************************************************/

// affichage des pages de gestion des responsables des groupes
function affiche_choix_gestion_groupes_responsables($choix_group, $choix_resp, $onglet, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();


	if( $choix_group!="" )    // si un groupe choisi : on affiche la gestion par groupe
	{
		affiche_gestion_groupes_responsables($choix_group, $onglet, $DEBUG);
	}
	elseif( $choix_resp!="" )     // si un resp choisi : on affiche la gestion par resp
	{
		affiche_gestion_responsable_groupes($choix_resp, $onglet, $DEBUG);
	}
	else    // si pas de groupe ou de resp choisi : on affiche les choix
	{
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_groupes_responsables( $DEBUG);
		echo "</td>\n";
		echo "<td valign=\"top\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_responsable_groupes( $DEBUG);
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

}


// affiche le tableau des groupes pour choisir sur quel groupe on va gerer les responsables
function affiche_choix_groupes_responsables( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_resp') .":</H3>\n\n";

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
		$sql_groupename=$resultat_gr["g_groupename"] ;
		$sql_comment=$resultat_gr["g_comment"] ;

		$text_choix_group="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_group=$sql_gid\"><b>&nbsp;$sql_groupename&nbsp;</b></a>" ;

		echo '<tr class="'.($i?'i':'p').'">';
		echo "<td>&nbsp;$text_choix_group&nbsp;</td>\n";
		echo "<td>&nbsp;$sql_comment&nbsp;</td>\n";
		echo "</tr>\n";
		$i = !$i;
	}
	echo "<tbody>\n";
	echo "</table>\n\n";

}


// affiche pour un groupe des cases à cocher devant les resp et grand_resp possibles pour les selectionner.
function affiche_gestion_groupes_responsables($choix_group, $onglet, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_groupe_resp') .":</H3>\n";


	/***********************/
	/* Affichage Groupe    */
	/***********************/
	// Récuperation des informations :
	$sql_gr = 'SELECT g_groupename, g_comment, g_double_valid FROM conges_groupe WHERE g_gid='.SQL::quote($choix_group);
	$ReqLog_gr = SQL::query($sql_gr);

	$resultat_gr = $ReqLog_gr->fetch_array();
	$sql_groupename=$resultat_gr["g_groupename"] ;
	$sql_comment=$resultat_gr["g_comment"] ;
	$sql_double_valid=$resultat_gr["g_double_valid"] ;

	// AFFICHAGE NOM DU GROUPE
	echo "<b>$sql_groupename</b><br><br>\n\n";

	//on rempli un tableau de tous les responsables avec le login, le nom, le prenom (tableau de tableau à 3 cellules
	// Récuperation des responsables :
	$tab_resp=array();
	$sql_resp = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_is_resp='Y' ORDER BY u_nom, u_prenom "  ;
	$ReqLog_resp = SQL::query($sql_resp);

	while($resultat_resp=$ReqLog_resp->fetch_array())
	{
		$tab_r=array();
		$tab_r["login"]=$resultat_resp["u_login"];
		$tab_r["nom"]=$resultat_resp["u_nom"];
		$tab_r["prenom"]=$resultat_resp["u_prenom"];
		$tab_resp[]=$tab_r;
	}
	/*****************************************************************************/

	echo '<form action="'.$PHP_SELF.'?session='.$session.'&onglet='.$onglet.'" method="POST">';
	echo "<table>\n";
	echo "<tr>\n";
	echo "	<td>\n";

		/*******************************************/
		//AFFICHAGE DU TABLEAU DES RESPONSBLES DU GROUPE
		echo "<table class=\"tablo\" width=\"300\">\n";
		echo "<thead>\n";

		// affichage TITRE
		echo "<tr>\n";
		echo "	<td colspan=3><h3>". _('admin_gestion_groupe_resp_responsables') ."</h3></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td>&nbsp;</td>\n";
		echo "	<td>&nbsp;". _('divers_personne_maj_1') ."&nbsp;:</td>\n";
		echo "	<td>&nbsp;". _('divers_login') ."&nbsp;:</td>\n";
		echo "</tr>\n";
		echo "</thead>\n";
		echo "<tbody>\n";

		// on rempli un autre tableau des responsables du groupe
		$tab_group=array();
		$sql_gr = 'SELECT gr_login FROM conges_groupe_resp WHERE gr_gid='.SQL::quote($choix_group).' ORDER BY gr_login ';
		$ReqLog_gr = SQL::query($sql_gr);

		while($resultat_gr=$ReqLog_gr->fetch_array())
		{
			$tab_group[]=$resultat_gr["gr_login"];
		}

		// ensuite on affiche tous les responsables avec une case cochée si exist login dans le 2ieme tableau
		$count = count($tab_resp);
		for ($i = 0; $i < $count; $i++)
		{
			$login=$tab_resp[$i]["login"] ;
			$nom=$tab_resp[$i]["nom"] ;
			$prenom=$tab_resp[$i]["prenom"] ;

			if (in_array ($login, $tab_group))
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_resp[$login]\" value=\"$login\" checked>";
				$class="histo-big";
			}
			else
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_resp[$login]\" value=\"$login\">";
				$class="histo";
			}

			echo '<tr class="'.(!($i%2)?'i':'p').'">';
			echo "	<td>$case_a_cocher</td>\n";
			echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
			echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
			echo "</tr>\n";
		}
		echo "</tbody>\n\n";
		echo "</table>\n\n";
		/*******************************************/

	// si on a configuré la double validation et que le groupe considéré est a double valid
	if( ($_SESSION['config']['double_validation_conges']) && ($sql_double_valid=="Y") )
	{
		echo "	</td>\n";
		echo "	<td width=\"50\">&nbsp;</td>\n";
		echo "	<td>\n";

			/*******************************************/
			//AFFICHAGE DU TABLEAU DES GRANDS RESPONSBLES DU GROUPE
			echo "<table class=\"tablo\" width=\"300\">\n";
			echo "<thead>\n";

			// affichage TITRE
			echo "<tr>\n";
			echo "	<td colspan=3><h3>". _('admin_gestion_groupe_grand_resp_responsables') ."</h3></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;". _('divers_personne_maj_1') ."&nbsp;:</td>\n";
			echo "	<td>&nbsp;". _('divers_login') ."&nbsp;:</td>\n";
			echo "</tr>\n";
			echo "</thead>\n";
			echo "<tbody>\n";

			// on rempli un autre tableau des grands responsables du groupe
			$tab_group_grd=array();
			$sql_ggr = 'SELECT ggr_login FROM conges_groupe_grd_resp WHERE ggr_gid='.SQL::quote($choix_group).' ORDER BY ggr_login ';
			$ReqLog_ggr = SQL::query($sql_ggr);

			while($resultat_ggr=$ReqLog_ggr->fetch_array())
			{
				$tab_group_grd[]=$resultat_ggr["ggr_login"];
			}

			// ensuite on affiche tous les grands responsables avec une case cochée si exist login dans le 3ieme tableau
			$count = count($tab_resp);
			for ($i = 0; $i < $count; $i++)
			{
				$login=$tab_resp[$i]["login"] ;
				$nom=$tab_resp[$i]["nom"] ;
				$prenom=$tab_resp[$i]["prenom"] ;

				if (in_array ($login, $tab_group_grd))
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_grd_resp[$login]\" value=\"$login\" checked>";
					$class="histo-big";
				}
				else
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_grd_resp[$login]\" value=\"$login\">";
					$class="histo";
				}

				echo '<tr class="'.(!($i%2)?'i':'p').'">';
				echo "	<td>$case_a_cocher</td>\n";
				echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
				echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
				echo "</tr>\n";
			}
			echo "</tbody>\n\n";
			echo "</table>\n\n";
			/*******************************************/
	}

	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>\n\n";


	echo "<input type=\"hidden\" name=\"change_group_responsables\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_group\" value=\"$choix_group\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=group-resp\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}


// modifie, pour un groupe donné,  ses resp et grands_resp
function modif_group_responsables($choix_group, &$checkbox_group_resp, &$checkbox_group_grd_resp,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$result_insert=TRUE;
	$result_insert_2=TRUE;

	//echo "groupe : $choix_group<br>\n";
	// on supprime tous les anciens resp du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del = 'DELETE FROM conges_groupe_resp WHERE gr_gid='.SQL::quote($choix_group);
	$ReqLog_del = SQL::query($sql_del);

	// on supprime tous les anciens grand resp du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del_2 = 'DELETE FROM conges_groupe_grd_resp WHERE ggr_gid='.SQL::quote($choix_group);
	$ReqLog_del_2 = SQL::query($sql_del_2);


	// ajout des resp qui sont dans la checkbox
	if($checkbox_group_resp!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_group_resp as $login => $value)
		{
			$sql_insert = "INSERT INTO conges_groupe_resp SET gr_gid=$choix_group, gr_login='$login' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}

	// ajout des grands resp qui sont dans la checkbox
	if($checkbox_group_grd_resp!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_group_grd_resp as $grd_login => $grd_value)
		{
			$sql_insert_2 = "INSERT INTO conges_groupe_grd_resp SET ggr_gid=$choix_group, ggr_login='$grd_login' "  ;
			$result_insert_2 = SQL::query($sql_insert_2);
		}
	}

	if( ($result_insert) && ($result_insert_2) )
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	$comment_log = "mofification_responsables_du_groupe : $choix_group" ;
	log_action(0, "", "", $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=group-resp\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}


// affiche le tableau des responsables pour choisir sur lequel on va gerer les groupes dont il est resp
function affiche_choix_responsable_groupes( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_resp_groupe') .":</H3>\n\n";


	// Récuperation des informations :
	$sql_resp = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp='Y' AND u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom"  ;
	$ReqLog_resp = SQL::query($sql_resp);

	/*************************/
	/* Choix Responsable     */
	/*************************/
	// AFFICHAGE TABLEAU
	echo "<h3>". _('admin_aff_choix_resp_titre') ." :</h3>\n";
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "	<td>&nbsp;". _('divers_responsable_maj_1') ."&nbsp;</td>\n";
	echo "	<td>&nbsp;". _('divers_login') ."&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";

	$i = true;
	while ($resultat_resp = $ReqLog_resp->fetch_array())
	{

		$sql_login=$resultat_resp["u_login"] ;
		$sql_nom=$resultat_resp["u_nom"] ;
		$sql_prenom=$resultat_resp["u_prenom"] ;

		$text_choix_resp="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_resp=$sql_login\"><b>&nbsp;$sql_nom&nbsp;$sql_prenom&nbsp;</b></a>" ;

		echo '<tr class="'.($i?'i':'p').'">';
		echo "<td>&nbsp;$text_choix_resp&nbsp;</td>\n";
		echo "<td>&nbsp;$sql_login&nbsp;</td>\n";
		echo "</tr>\n";
		$i = !$i;
	}
	echo "</tbody>\n\n";
	echo "</table>\n\n";

}


// affiche pour un resp des cases à cocher devant les groupes possibles pour les selectionner.
function affiche_gestion_responsable_groupes($choix_resp, $onglet, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<H3>". _('admin_onglet_resp_groupe') .":</H3>\n\n";

	//echo "resp = $choix_resp<br>\n";
	/****************************/
	/* Affichage Responsable    */
	/****************************/
	// Récuperation des informations :
	$sql_r = 'SELECT u_nom, u_prenom FROM conges_users WHERE u_login=\''.SQL::quote($choix_resp).'\'';
	$ReqLog_r = SQL::query($sql_r);

	$resultat_r = $ReqLog_r->fetch_array();
	$sql_nom=$resultat_r["u_nom"] ;
	$sql_prenom=$resultat_r["u_prenom"] ;

	echo "<b>$sql_prenom $sql_nom</b><br><br>\n";

	//on rempli un tableau de tous les groupe avec le groupename, le commentaire (tableau de tableaux à 3 cellules)
	// Récuperation des groupes :
	$tab_groupe=array();
	$sql_groupe = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename "  ;
	$ReqLog_groupe = SQL::query($sql_groupe);

	while($resultat_groupe=$ReqLog_groupe->fetch_array())
	{
		$tab_g=array();
		$tab_g["gid"]=$resultat_groupe["g_gid"];
		$tab_g["group"]=$resultat_groupe["g_groupename"];
		$tab_g["comment"]=$resultat_groupe["g_comment"];
		$tab_groupe[]=$tab_g;
	}

	//on rempli un tableau de tous les groupes a double validation avec le groupename, le commentaire (tableau de tableau à 3 cellules)
	$tab_groupe_dbl_valid=array();
	$sql_g2 = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe WHERE g_double_valid='Y' ORDER BY g_groupename "  ;
	$ReqLog_g2 = SQL::query($sql_g2);

	while($resultat_groupe_2=$ReqLog_g2->fetch_array())
	{
		$tab_g_2=array();
		$tab_g_2["gid"]=$resultat_groupe_2["g_gid"];
		$tab_g_2["group"]=$resultat_groupe_2["g_groupename"];
		$tab_g_2["comment"]=$resultat_groupe_2["g_comment"];
		$tab_groupe_dbl_valid[]=$tab_g_2;
	}

	/*****************************************************************************/

	echo '<form action="'.$PHP_SELF.'?session='.$session.'&onglet='.$onglet.'" method="POST">';
	echo "<table>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\">\n";

		/*******************************************/
		//AFFICHAGE DU TABLEAU DES GROUPES DONT RESP EST RESPONSABLE
		echo "<table class=\"tablo\">\n";
		echo "<thead>\n";

		// affichage TITRE
		echo "<tr>\n";
		echo "	<td colspan=3><h3>". _('divers_responsable_maj_1') ."</h3></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td>&nbsp;</td>\n";
		echo "	<td>&nbsp;". _('admin_groupes_groupe') ."&nbsp;:</td>\n";
		echo "	<td>&nbsp;". _('admin_groupes_libelle') ."&nbsp;:</td>\n";
		echo "</tr>\n";
		echo "</thead>\n";
		echo "<tbody>\n";

		// on rempli un autre tableau des groupes dont resp est responsables
		$tab_resp=array();
		$sql_r = 'SELECT gr_gid FROM conges_groupe_resp WHERE gr_login=\''.SQL::quote($choix_resp).'\' ORDER BY gr_gid ';
		$ReqLog_r = SQL::query($sql_r);

		while($resultat_r=$ReqLog_r->fetch_array())
		{
			$tab_resp[]=$resultat_r["gr_gid"];
		}

		// ensuite on affiche tous les groupes avec une case cochée si exist groupename dans le 2ieme tableau
		$count = count($tab_groupe);
		for ($i = 0; $i < $count; $i++)
		{
			$gid=$tab_groupe[$i]["gid"] ;
			$group=$tab_groupe[$i]["group"] ;
			$comment=$tab_groupe[$i]["comment"] ;

			if (in_array ($gid, $tab_resp))
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_resp_group[$gid]\" value=\"$gid\" checked>";
				$class="histo-big";
			}
			else
			{
				$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_resp_group[$gid]\" value=\"$gid\">";
				$class="histo";
			}

			echo '<tr class="'.(!($i%2)?'i':'p').'">';
			echo "	<td>$case_a_cocher</td>\n";
			echo "	<td class=\"$class\"> $group </td>\n";
			echo "	<td class=\"$class\"> $comment </td>\n";
			echo "</tr>\n";
		}

		echo "</tbody>\n\n";
		echo "</table>\n\n";
		/*******************************************/

	// si on a configuré la double validation
	if($_SESSION['config']['double_validation_conges'])
	{
		echo "	</td>\n";
		echo "	<td width=\"50\">&nbsp;</td>\n";
		echo "<td valign=\"top\">\n";

			/*******************************************/
			//AFFICHAGE DU TABLEAU DES GROUPES DONT RESP EST GRAND RESPONSABLE
			echo "<table class=\"tablo\">\n";
			echo "<thead>\n";

			// affichage TITRE
			echo "<tr>\n";
			echo "	<td colspan=3><h3>". _('divers_grand_responsable_maj_1') ."</h3></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;". _('admin_groupes_groupe') ."&nbsp;:</td>\n";
			echo "	<td>&nbsp;". _('admin_groupes_libelle') ."&nbsp;:</td>\n";
			echo "</tr>\n";
			echo "</thead>\n";
			echo "<tbody>\n";

			// on rempli un autre tableau des groupes dont resp est GRAND responsables
			$tab_grd_resp=array();
			$sql_gr = 'SELECT ggr_gid FROM conges_groupe_grd_resp WHERE ggr_login=\''.SQL::quote($choix_resp).'\' ORDER BY ggr_gid ';
			$ReqLog_gr = SQL::query($sql_gr);

			while($resultat_gr=$ReqLog_gr->fetch_array())
			{
				$tab_grd_resp[]=$resultat_gr["ggr_gid"];
			}

			// ensuite on affiche tous les groupes avec une case cochée si exist groupename dans le 2ieme tableau
			$count = count($tab_groupe_dbl_valid);
			for ($i = 0; $i < $count; $i++)
			{
				$gid=$tab_groupe_dbl_valid[$i]["gid"] ;
				$group=$tab_groupe_dbl_valid[$i]["group"] ;
				$comment=$tab_groupe_dbl_valid[$i]["comment"] ;

				if (in_array($gid, $tab_grd_resp))
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_grd_resp_group[$gid]\" value=\"$gid\" checked>";
					$class="histo-big";
				}
				else
				{
					$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_grd_resp_group[$gid]\" value=\"$gid\">";
					$class="histo";
				}

				echo '<tr class="'.(!($i%2)?'i':'p').'">';
				echo "	<td>$case_a_cocher</td>\n";
				echo "	<td class=\"$class\"> $group </td>\n";
				echo "	<td class=\"$class\"> $comment </td>\n";
				echo "</tr>\n";
			}

			echo "</tbody>\n\n";
			echo "</table>\n\n";
			/*******************************************/
	}

	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>\n\n";


	echo "<input type=\"hidden\" name=\"change_responsable_group\" value=\"ok\">\n";
	echo "<input type=\"hidden\" name=\"choix_resp\" value=\"$choix_resp\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=resp-group\" method=\"POST\">\n"  ;
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\">\n";
	echo "</form>\n" ;

}


// modifie, pour un resp donné,  les groupes dont il est resp et grands_resp
function modif_resp_groupes($choix_resp, &$checkbox_resp_group, &$checkbox_grd_resp_group,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();


	$result_insert=TRUE;
	$result_insert_2=TRUE;

	//echo "responsable : $choix_resp<br>\n";
	// on supprime tous les anciens resps du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del = 'DELETE FROM conges_groupe_resp WHERE gr_login=\''.SQL::quote($choix_resp).'\'';
	$ReqLog_del = SQL::query($sql_del);

	// on supprime tous les anciens grands resps du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del_2 = 'DELETE FROM conges_groupe_grd_resp WHERE ggr_login=\''.SQL::quote($choix_resp).'\'';
	$ReqLog_del_2 = SQL::query($sql_del_2);

	// ajout des resp qui sont dans la checkbox
	if($checkbox_resp_group!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_resp_group as $gid => $value)
		{
			$sql_insert = "INSERT INTO conges_groupe_resp SET gr_gid=$gid, gr_login='$choix_resp' "  ;
			$result_insert = SQL::query($sql_insert);
		}
	}

	// ajout des grands resp qui sont dans la checkbox
	if($checkbox_grd_resp_group!="") // si la checkbox contient qq chose
	{
		foreach($checkbox_grd_resp_group as $grd_gid => $value)
		{
			$sql_insert_2 = "INSERT INTO conges_groupe_grd_resp SET ggr_gid=$grd_gid, ggr_login='$choix_resp' "  ;
			$result_insert_2 = SQL::query($sql_insert_2);
		}
	}

	if(($result_insert) && ($result_insert_2) )
		echo  _('form_modif_ok') ." !<br><br> \n";
	else
		echo  _('form_modif_not_ok') ." !<br><br> \n";

	$comment_log = "mofification groupes dont $choix_resp est responsable ou grand responsable" ;
	log_action(0, "", $choix_resp, $comment_log,  $DEBUG);

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=resp-group\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_retour') ."\">\n";
	echo " </form> \n";

}

