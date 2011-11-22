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

//session_start();
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
	/*** initialisation des variables ***/
	$ajout_conges="";
	$ajout_global="";
	$ajout_groupe="";
	/************************************/
	
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	
	echo "<body text=#000000 bgcolor=#FFFFFF link=#000080 vlink=#800080 alink=#FF0000 background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";
	echo "<CENTER>\n";
	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
//	if(isset($_GET['p_num'])) { $p_num=$_GET['p_num']; }
	// POST
	if(isset($_POST['ajout_conges'])) { $ajout_conges=$_POST['ajout_conges']; }
	if(isset($_POST['tab_champ_saisie_conges'])) { $tab_champ_saisie_conges=$_POST['tab_champ_saisie_conges']; }
	if(isset($_POST['tab_champ_saisie_rtt'])) { $tab_champ_saisie_rtt=$_POST['tab_champ_saisie_rtt']; }
	if(isset($_POST['ajout_global'])) { $ajout_global=$_POST['ajout_global']; }
	if(isset($_POST['ajout_groupe'])) { $ajout_groupe=$_POST['ajout_groupe']; }
	if(isset($_POST['choix_groupe'])) { $choix_groupe=$_POST['choix_groupe']; }
	if(isset($_POST['new_nb_conges_all'])) { $new_nb_conges_all=$_POST['new_nb_conges_all']; }
	if(isset($_POST['new_nb_rtt_all']))    { $new_nb_rtt_all=$_POST['new_nb_rtt_all']; }
	if(isset($_POST['calcul_new_conges_proportionnel'])) { $calcul_new_conges_proportionnel=$_POST['calcul_new_conges_proportionnel']; }
	if(isset($_POST['calcul_new_rtt_proportionnel']))    { $calcul_new_rtt_proportionnel=$_POST['calcul_new_rtt_proportionnel']; }
	
	/*************************************/
	
	// titre
	printf("<H2>Ajout de congès :</H2>\n\n");
	//connexion mysql
	$link = connexion_mysql() ;
	
	if($ajout_conges=="TRUE") {
		ajout_conges();
	}
	elseif($ajout_global=="TRUE") {
		ajout_global($new_nb_conges_all, $new_nb_rtt_all, $calcul_new_conges_proportionnel, $calcul_new_rtt_proportionnel);
	}
	elseif($ajout_groupe=="TRUE") {
		ajout_global_groupe($choix_groupe, $new_nb_conges_all, $new_nb_rtt_all, $calcul_new_conges_proportionnel, $calcul_new_rtt_proportionnel);
	}
	else
	{
		saisie();
	}
	
	mysql_close($link);

	
		
/************************************************************************/
/*** FONCTIONS ***/

function saisie() {
	global $PHP_SELF, $link;
	global $session, $session_username ;
	global $config_responsable_virtuel, $config_rtt_comme_conges, $config_gestion_groupes ;
	
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
	
	// Récupération des informations
		$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_quotite FROM conges_users WHERE ";
	if($config_responsable_virtuel==TRUE)
		$sql1 = $sql1." u_login != 'conges' ";
	else
	{
		$sql1 = $sql1." u_resp_login = '$session_username' ";
		if($config_gestion_groupes==TRUE)
		{
			$list_users_group=get_list_users_des_groupes_du_resp($session_username);
			if($list_users_group!="")  //si la liste n'est pas vide ( serait le cas si n'est responsable d'aucun groupe)
				$sql1=$sql1." OR u_login IN ($list_users_group) ";
		}
	}
	$sql1 = $sql1." ORDER BY u_nom ";
		
	$ReqLog1 = mysql_query( $sql1, $link) or die("ERREUR : mysql_query : ".$sql." --> ".mysql_error());


	// AFFICHAGE TABLEAU
	//printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<table cellpadding=\"2\" class=\"tablo\" width=\"700\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">nom</td><td class=\"titre\">prenom</td><td class=\"titre\">Quotité</td><td class=\"titre\">Congés <i>(solde)</i></td><td class=\"titre\">NB jours de congés a ajouter</td>");
	if($config_rtt_comme_conges==1)
		printf("<td class=\"titre\">RTT <i>(solde)</i></td><td class=\"titre\">NB jours de RTT a ajouter</td>");
	printf("</tr>\n");
	$cpt_lignes=0 ;
	$tab_champ_saisie_conges=array();
	while ($resultat1 = mysql_fetch_array($ReqLog1))
	{
		$sql_login=$resultat1["u_login"];
		$sql_nom=$resultat1["u_nom"];
		$sql_prenom=$resultat1["u_prenom"];
		$sql_quotite=$resultat1["u_quotite"];
		$sql_nb_j_an=affiche_decimal($resultat1["u_nb_jours_an"]);
		$sql_solde_j=affiche_decimal($resultat1["u_solde_jours"]);
		$sql_nb_rtt_an=affiche_decimal($resultat1["u_nb_rtt_an"]);
		$sql_solde_rtt=affiche_decimal($resultat1["u_solde_rtt"]);
	
		/** sur la ligne ,   **/
		/** le champ de saisie est <input type="text" name="tab_champ_saisie_conges[valeur de u_login]" value="[valeur du nb de jours ajouté saisi]"> */
		$champ_saisie_conges="<input type=\"text\" name=\"tab_champ_saisie_conges[$sql_login]\" size=\"6\" maxlength=\"6\" value=\"0\">";
		if($config_rtt_comme_conges==1)
			$champ_saisie_rtt="<input type=\"text\" name=\"tab_champ_saisie_rtt[$sql_login]\" size=\"6\" maxlength=\"6\" value=\"0\">";

		if($config_rtt_comme_conges==1)
			echo "<td class=\"titre\">$sql_nom</td><td class=\"titre\">$sql_prenom</td><td class=\"histo\">$sql_quotite%</td>
				<td class=\"histo\">$sql_nb_j_an <i>($sql_solde_j)</i></td><td align=\"center\" class=\"histo\">$champ_saisie_conges</td>
				<td class=\"histo\">$sql_nb_rtt_an <i>($sql_solde_rtt)</i></td><td align=\"center\" class=\"histo\">$champ_saisie_rtt</td>\n" ; 
		else
			echo "<td class=\"titre\">$sql_nom</td><td class=\"titre\">$sql_prenom</td><td class=\"histo\">$sql_quotite%</td>
				<td class=\"histo\">$sql_nb_j_an <i>($sql_solde_j)</i></td><td align=\"center\" class=\"histo\">$champ_saisie_conges</td>\n" ; 
		printf("</tr>\n");
		$cpt_lignes++ ;
	}
	printf("</table>\n\n");

	echo "<input type=\"hidden\" name=\"ajout_conges\" value=\"TRUE\">\n";
	printf("<input type=\"submit\" value=\"Valider les saisies\">\n");
	printf(" </form> \n");

	/************************************************************/
	/* SAISIE GLOBALE pour tous les utilisateurs du responsable */
	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
	echo "<table>\n";
	echo "<tr><td align=\"center\">\n";
	echo "	<fieldset class=\"cal_saisie\">\n";
	echo "	<legend class=\"boxlogin\">Ajout global pour Tous :</legend>\n";
	echo "	<table>\n";
	echo "	<tr>\n";
	echo "		<td class=\"big\">Nombre de jours de CONGES à ajouter à tous : </td>\n";
	echo "		<td><input type=\"text\" name=\"new_nb_conges_all\" size=\"6\" maxlength=\"6\" value=\"0\"></td>\n";
	echo "		<td> ( Calcul proportionnel à la quotité de chaque personne : </td>\n";
	echo "		<td>OUI <input type=\"checkbox\" name=\"calcul_new_conges_proportionnel\" value=\"TRUE\" checked> )</td>\n";
	echo "	<tr>\n";
	if($config_rtt_comme_conges==1)
	{
		echo "	</tr>\n";
		echo "		<td class=\"big\">Nombre de jours de RTT à ajouter à tous : </td>\n";
		echo "		<td><input type=\"text\" name=\"new_nb_rtt_all\" size=\"6\" maxlength=\"6\" value=\"0\"></td>\n";
		echo "		<td> ( Calcul proportionnel à la quotité de chaque personne : </td>\n";
		echo "		<td>OUI <input type=\"checkbox\" name=\"calcul_new_rtt_proportionnel\" value=\"TRUE\" checked> )</td>\n";
		echo "	</tr>\n";
	}
	echo "	</tr>\n";
	echo "		<td class=\"big\">&nbsp;</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "		<td colspan=\"2\"> (le calcul proportionnel est arrondi au 1/2 le plus proche !) </td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"Valider la saisie globale\"></td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	</fieldset>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "<input type=\"hidden\" name=\"ajout_global\" value=\"TRUE\">\n";
	echo "</form> \n";
	
	echo "<br>\n";
	
	/***********************************************************************/
	/* SAISIE GROUPE pour tous les utilisateurs d'un groupe du responsable */
	if( $config_gestion_groupes==TRUE )
	{
		$list_group=get_list_groupes_du_resp($session_username);
		if($list_group!="")  //si la liste n'est pas vide ( serait le cas si n'est responsable d'aucun groupe)
		{
			echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
			echo "<table>\n";
			echo "<tr><td align=\"center\">\n";
			echo "	<fieldset class=\"cal_saisie\">\n";
			echo "	<legend class=\"boxlogin\">Ajout par Groupe : (ajout à tous les membres d'un groupe)</legend>\n";
			echo "	<table>\n";
			echo "	<tr>\n";
			echo "		<td class=\"big\">choix du groupe : </td>\n";
				// création du select pour le choix du groupe
				$text_choix_group="<select name=\"choix_groupe\" >";
//				$sql_group = "SELECT g_gid, g_groupename FROM conges_groupe, conges_groupe_resp WHERE g_gid=gr_gid AND gr_login='$session_username' ORDER BY g_groupename "  ;
				$sql_group = "SELECT g_gid, g_groupename FROM conges_groupe WHERE g_gid IN ($list_group) ORDER BY g_groupename "  ;
				$ReqLog_group = mysql_query($sql_group, $link) or die("ERREUR : mysql_query : $sql_group :\n".mysql_error());
				while ($resultat_group = mysql_fetch_array($ReqLog_group)) {
					$current_group_id=$resultat_group["g_gid"];
					$current_group_name=$resultat_group["g_groupename"];
					$text_choix_group=$text_choix_group."<option value=\"$current_group_id\" >$current_group_name</option>";
				}
				$text_choix_group=$text_choix_group."</select>" ;

			echo "		<td colspan=\"3\">$text_choix_group</td>\n";
			echo "	<tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"big\">Nombre de jours de CONGES à ajouter au groupe : </td>\n";
			echo "		<td><input type=\"text\" name=\"new_nb_conges_all\" size=\"6\" maxlength=\"6\" value=\"0\"></td>\n";
			echo "		<td> ( Calcul proportionnel à la quotité de chaque personne : </td>\n";
			echo "		<td>OUI <input type=\"checkbox\" name=\"calcul_new_conges_proportionnel\" value=\"TRUE\" checked> )</td>\n";
			echo "	<tr>\n";
			if($config_rtt_comme_conges==1)
			{
				echo "	</tr>\n";
				echo "		<td class=\"big\">Nombre de jours de RTT à ajouter au groupe : </td>\n";
				echo "		<td><input type=\"text\" name=\"new_nb_rtt_all\" size=\"6\" maxlength=\"6\" value=\"0\"></td>\n";
				echo "		<td> ( Calcul proportionnel à la quotité de chaque personne : </td>\n";
				echo "		<td>OUI <input type=\"checkbox\" name=\"calcul_new_rtt_proportionnel\" value=\"TRUE\" checked> )</td>\n";
				echo "	</tr>\n";
			}
			echo "	</tr>\n";
			echo "		<td class=\"big\">&nbsp;</td>\n";
			echo "		<td>&nbsp;</td>\n";
			echo "		<td colspan=\"2\"> (le calcul proportionnel est arrondi au 1/2 le plus proche !) </td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"Valider la saisie pour le Groupe\"></td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</fieldset>\n";
			echo "</td></tr>\n";
			echo "</table>\n";
			echo "<input type=\"hidden\" name=\"ajout_groupe\" value=\"TRUE\">\n";
			echo "</form> \n";
		}
	}
	
	echo "<br>\n";
	
	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"resp_main.php?session=$session\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"Retour Page Principale\">\n";
	echo " </form> \n";

}

function ajout_conges() {
	global $PHP_SELF, $link;
	global $config_rtt_comme_conges;
	global $session, $session_username, $tab_champ_saisie_conges, $tab_champ_saisie_rtt ;

	while($elem_tableau = each($tab_champ_saisie_conges))
	{
		$user_nb_jours_ajout = $elem_tableau['value'] ;
		$valid=verif_saisie_decimal($user_nb_jours_ajout);   //verif la bonne saisie du nombre décimal
		$user_nb_jours_ajout_float =(float) $user_nb_jours_ajout ;
		$user_name=$elem_tableau['key'];
		//echo($user_name."---".$user_nb_jours_ajout_float."<br>");

		if($user_nb_jours_ajout_float!=0)
		{
			/* Modification de la table conges_users */
			$sql1 = "UPDATE conges_users SET u_solde_jours=u_solde_jours+$user_nb_jours_ajout_float WHERE u_login='$user_name' " ;
			/* On valide l'UPDATE dans la table ! */
			$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : $sql1 :\n".mysql_error());

			// on insert l'ajout de conges dans la table periode
			$commentaire="ajout jour";
			insert_ajout_dans_periode($user_name, $user_nb_jours_ajout_float, "conges", $commentaire, $link);
		}
	}

	if($config_rtt_comme_conges==TRUE)
	{
		while($elem_tableau_rtt = each($tab_champ_saisie_rtt))
		{
			$user_nb_jours_ajout = $elem_tableau_rtt['value'] ;
			$valid=verif_saisie_decimal($user_nb_jours_ajout);   //verif la bonne saisie du nombre décimal
			$user_nb_jours_ajout_float =(float) $user_nb_jours_ajout ;
			$user_name=$elem_tableau_rtt['key'];
			//echo($user_name."---".$user_nb_jours_ajout_float."<br>");
			
			if($user_nb_jours_ajout_float!=0)
			{
				/* Modification de la table conges_users */
				$sql2 = "UPDATE conges_users SET u_solde_rtt=u_solde_rtt+$user_nb_jours_ajout_float WHERE u_login='$user_name' " ;
				/* On valide l'UPDATE dans la table ! */
				$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : $sql2 :\n".mysql_error());

				// on insert l'ajout de conges dans la table periode
				$commentaire="ajout rtt";
				insert_ajout_dans_periode($user_name, $user_nb_jours_ajout_float, "conges", $commentaire, $link);
			}
		}
	}
	
	
	
	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";

}


function ajout_global($new_nb_conges_all, $new_nb_rtt_all, $calcul_new_conges_proportionnel, $calcul_new_rtt_proportionnel)
{
	global $PHP_SELF, $link;
	global $config_rtt_comme_conges, $config_responsable_virtuel, $config_gestion_groupes;
	global $session, $session_username ;
	
	if(!isset($new_nb_conges_all))
		$new_nb_conges_all=0;
	if(!isset($new_nb_rtt_all))
		$new_nb_rtt_all=0;
	
	if( ($new_nb_conges_all!=0)||($new_nb_rtt_all!=0) ) // s'il sont tous les 2 à 0, ont ne fait rien
	{
		$req_update = "UPDATE conges_users ";
		if($calcul_new_conges_proportionnel!=TRUE)
			$req_update = $req_update."SET u_solde_jours=u_solde_jours+$new_nb_conges_all ";
		else
			// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
			$req_update = $req_update."SET u_solde_jours=u_solde_jours+( (ROUND(($new_nb_conges_all*(u_quotite/100))*2))/2 ) ";

		if($calcul_new_rtt_proportionnel!=TRUE) 
			$req_update = $req_update.", u_solde_rtt=u_solde_rtt+$new_nb_rtt_all ";
		else
			// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
			$req_update = $req_update.", u_solde_rtt=u_solde_rtt+( (ROUND(($new_nb_rtt_all*(u_quotite/100))*2))/2 ) ";

		// si resp virtuel, on update tout le monde, sinon, seulement ceux dont on est responsables
		if($config_responsable_virtuel==FALSE)
		{
			$req_update = $req_update." WHERE  u_resp_login='$session_username' " ;
			if($config_gestion_groupes == TRUE)
			{
				$list_users_group=get_list_users_des_groupes_du_resp($session_username);
				if($list_users_group!="")
					$req_update=$req_update." OR u_login IN ($list_users_group)  ";
			}
		}	

		//echo "$req_update<br>\n";
		/* On valide l'UPDATE dans la table ! */
		$ReqLog1 = mysql_query($req_update, $link) or die("ERREUR : mysql_query : ".$req_update." --> ".mysql_error());

		// on insert l'ajout de conges GLOBAL (pour tous les users) dans la table periode
		$commentaire="ajout pour tous les personnels";
		insert_ajout_global_dans_periode($new_nb_conges_all, $new_nb_rtt_all, $commentaire, $calcul_new_conges_proportionnel, $calcul_new_rtt_proportionnel,$link);
	}
	
	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";
}

function ajout_global_groupe($choix_groupe, $new_nb_conges_all, $new_nb_rtt_all, $calcul_new_conges_proportionnel, $calcul_new_rtt_proportionnel)
{
	global $PHP_SELF, $link;
	global $config_rtt_comme_conges, $config_responsable_virtuel,$config_rtt_comme_conges ;
	global $session, $session_username ;
	
	if(!isset($new_nb_conges_all))
		$new_nb_conges_all=0;
	if(!isset($new_nb_rtt_all))
		$new_nb_rtt_all=0;
		
	if( ($new_nb_conges_all!=0)||($new_nb_rtt_all!=0) ) // s'il sont tous les 2 à 0, ont ne fait rien
	{
		// recup du nom du groupe
		$req_name="SELECT g_groupename FROM conges_groupe WHERE g_gid=$choix_groupe ";
		$ReqLog_name = mysql_query($req_name, $link) or die("ERREUR : ajout_global_groupe() : $req_name :\n".mysql_error());
		$resultat_name = mysql_fetch_array($ReqLog_name);
			$groupename=$resultat_name["g_groupename"];
	
		// recup des users du groupe et pour chacun : on update
		$req_g="SELECT gu_login, u_quotite FROM conges_groupe_users, conges_users 
				WHERE gu_gid='$choix_groupe' 
				AND gu_login=u_login ";
		$ReqLog_g = mysql_query($req_g, $link) or die("ERREUR : ajout_global_groupe() : $req_g :\n".mysql_error());
		while ($resultat_g = mysql_fetch_array($ReqLog_g)) 
		{
			$current_login=$resultat_g["gu_login"];
			$current_qutite=$resultat_g["u_quotite"];

			$req_update = "UPDATE conges_users ";
			if($calcul_new_conges_proportionnel!=TRUE)
				$req_update = $req_update."SET u_solde_jours=u_solde_jours+$new_nb_conges_all ";
			else
				// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
				$req_update = $req_update."SET u_solde_jours=u_solde_jours+( (ROUND(($new_nb_conges_all*(u_quotite/100))*2))/2 ) ";

			if($calcul_new_rtt_proportionnel!=TRUE) 
				$req_update = $req_update.", u_solde_rtt=u_solde_rtt+$new_nb_rtt_all ";
			else
				// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
				$req_update = $req_update.", u_solde_rtt=u_solde_rtt+( (ROUND(($new_nb_rtt_all*(u_quotite/100))*2))/2 ) ";

			// si resp virtuel, on update tout le monde, sinon, seulement ceux dont on est responsables
			if($config_responsable_virtuel==FALSE) 
				$req_update = $req_update." WHERE u_login='$current_login' " ;

			//echo "$req_update<br>\n";
			/* On valide l'UPDATE dans la table ! */
			$ReqLog1 = mysql_query($req_update, $link) or die("ERREUR : ajout_global_groupe() : $req_update :\n".mysql_error());
		
		
			// on insert l'ajout de conges dans la table periode
			$commentaire="ajout pour le groupe $groupename";
			// ajout conges
			if($new_nb_conges_all!=0)
			{
				if($calcul_new_conges_proportionnel!=TRUE)
					$nb_conges=$new_nb_conges_all;
				else
					// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
					$nb_conges = (ROUND(($new_nb_conges_all*($current_qutite/100))*2))/2  ;
				insert_ajout_dans_periode($current_login, $nb_conges, "conges", $commentaire, $link);
			}

			if( ($config_rtt_comme_conges==TRUE) && ($new_nb_rtt_all!=0) )
			{
				// ajout rtt
				if($calcul_new_rtt_proportionnel!=TRUE)
					$nb_rtt=$new_nb_rtt_all;
				else
					// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
					$nb_rtt = (ROUND(($new_nb_rtt_all*($current_qutite/100))*2))/2  ;
				insert_ajout_dans_periode($current_login, $nb_rtt, "rtt", $commentaire, $link);
			}
		}
	}

	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";
}



// on insert l'ajout de conges dans la table periode
function insert_ajout_dans_periode($login, $nb_jours, $type, $commentaire, $mysql_link)
{
	$date_today=date("Y-m-d");
	
	$result=insert_dans_periode($login, $date_today, "am", $date_today, "am", $nb_jours, $commentaire, $type, "ajout", $mysql_link);
}

// on insert l'ajout de conges GLOBAL (pour tous les users) dans la table periode
function insert_ajout_global_dans_periode($nb_jours_conges, $nb_jours_rtt, $commentaire, $calcul_conges_proportionnel, $calcul_rtt_proportionnel, $mysql_link)
{
	global $config_rtt_comme_conges, $config_responsable_virtuel, $config_gestion_groupes;
	global $session, $session_username ;
	
	$date_today=date("Y-m-d");
		
	// recup des login de tous les user pour qui on doit faire un insert dan sla table periode
	$req_select="SELECT u_login, u_quotite FROM conges_users";
	// si resp virtuel, on select tout le monde, sinon, seulement ceux dont on est responsables
	if($config_responsable_virtuel==FALSE)
	{
		$req_select = $req_select." WHERE  u_resp_login='$session_username' " ;
		if($config_gestion_groupes == TRUE)
		{
			$list_users_group=get_list_users_des_groupes_du_resp($session_username);
			if($list_users_group!="")
				$req_select=$req_select." OR u_login IN ($list_users_group)  ";
		}
	}
	
	$ReqLog2 = mysql_query($req_select, $mysql_link) or die("ERREUR : insert_ajout_global_dans_periode() : $req_select :\n".mysql_error());
	
	while ($resultat = mysql_fetch_array($ReqLog2)) 
	{
		$login=$resultat["u_login"];
		$quotite=$resultat["u_quotite"];
		
		// ajout conges
		if($nb_jours_conges!=0)
		{
			if($calcul_conges_proportionnel!=TRUE)
				$nb_conges=$nb_jours_conges;
			else
				// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
				$nb_conges = (ROUND(($nb_jours_conges*($quotite/100))*2))/2  ;
			insert_ajout_dans_periode($login, $nb_conges, "conges", $commentaire, $mysql_link);
		}
		
		// ajout rtt
		if( ($config_rtt_comme_conges==TRUE) && ($nb_jours_rtt!=0) )
		{
			if($calcul_rtt_proportionnel!=TRUE)
				$nb_rtt=$nb_jours_rtt;
			else
				// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
				$nb_rtt = (ROUND(($nb_jours_rtt*($quotite/100))*2))/2  ;
			insert_ajout_dans_periode($login, $nb_rtt, "rtt", $commentaire, $mysql_link);
		}
	}

}

?>

</CENTER>
</body>
</html>
