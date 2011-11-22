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
	$DEBUG = FALSE ;
	//$DEBUG = TRUE ;
	
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$ajout_conges            = getpost_variable("ajout_conges") ;
	$tab_champ_saisie        = getpost_variable("tab_champ_saisie") ;
	//$tab_champ_saisie_rtt    = getpost_variable("tab_champ_saisie_rtt") ;
	$ajout_global            = getpost_variable("ajout_global") ;
	$ajout_groupe            = getpost_variable("ajout_groupe") ;
	$choix_groupe            = getpost_variable("choix_groupe") ;
	$tab_new_nb_conges_all   = getpost_variable("tab_new_nb_conges_all") ;
	$tab_calcul_proportionnel = getpost_variable("tab_calcul_proportionnel") ;

	/*************************************/
	
	if($DEBUG==TRUE) { echo "tab_new_nb_conges_all = <br>"; print_r($tab_new_nb_conges_all); echo "<br>\n" ;}
	if($DEBUG==TRUE) { echo "tab_calcul_proportionnel = <br>"; print_r($tab_calcul_proportionnel); echo "<br>\n" ;}
	
	
	// titre
	printf("<H2>Ajout de cong�s :</H2>\n\n");
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	if($ajout_conges=="TRUE") {
		ajout_conges($DEBUG, $tab_champ_saisie, $mysql_link);
	}
	elseif($ajout_global=="TRUE") {
		ajout_global($DEBUG, $tab_new_nb_conges_all, $tab_calcul_proportionnel, $mysql_link);
	}
	elseif($ajout_groupe=="TRUE") {
		ajout_global_groupe($DEBUG, $choix_groupe, $tab_new_nb_conges_all, $tab_calcul_proportionnel, $mysql_link);
	}
	else
	{
		saisie($DEBUG, $mysql_link);
	}
	
	mysql_close($mysql_link);

	
		
/************************************************************************/
/*** FONCTIONS ***/

function saisie($DEBUG, $mysql_link)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;

	// recup du tableau des types de conges (seulement les conges)
	$tab_type_conges = recup_tableau_types_conges($mysql_link);

	echo " <form action=\"$PHP_SELF\" method=\"POST\"> \n";
	
	// R�cup�ration des informations
	// R�cup dans un tableau de tableau des informations de tous les users dont $_SESSION['userlogin'] est responsable
	$tab_all_users=recup_infos_all_users_du_resp($_SESSION['userlogin'], $mysql_link);


	// AFFICHAGE TABLEAU
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"700\">\n";
	echo "<tr align=\"center\"><td class=\"titre\">nom</td><td class=\"titre\">prenom</td><td class=\"titre\">Quotit�</td>";
	foreach($tab_type_conges as $id_conges => $libelle)
	{
		echo "<td class=\"titre\">$libelle<br><i>(solde)</i></td><td class=\"titre\">$libelle<br>NB jours &agrave; ajouter</td>" ;
	}
	echo"</tr>\n";
	
	$cpt_lignes=0 ;
	$tab_champ_saisie_conges=array();
	foreach($tab_all_users as $current_login => $tab_current_user)
	{		
		//tableau de tableaux les nb et soldes de conges d'un user (indic� par id de conges)
		$tab_conges=$tab_current_user['conges']; 

		/** sur la ligne ,   **/
		echo "<td class=\"titre\">".$tab_current_user['nom']."</td><td class=\"titre\">".$tab_current_user['prenom']."</td><td class=\"histo\">".$tab_current_user['quotite']."%</td>\n";

		foreach($tab_type_conges as $id_conges => $libelle)
		{
			/** le champ de saisie est <input type="text" name="tab_champ_saisie[valeur de u_login][id_du_type_de_conges]" value="[valeur du nb de jours ajout� saisi]"> */
			$champ_saisie_conges="<input type=\"text\" name=\"tab_champ_saisie[$current_login][$id_conges]\" size=\"6\" maxlength=\"6\" value=\"0\">";
			echo "<td class=\"histo\">".$tab_conges[$id_conges]['nb_an']." <i>(".$tab_conges[$id_conges]['solde'].")</i></td><td align=\"center\" class=\"histo\">$champ_saisie_conges</td>\n" ;
		}
		echo "</tr>\n";
		$cpt_lignes++ ;
	}
	
	echo "</table>\n\n";

	echo "<input type=\"hidden\" name=\"ajout_conges\" value=\"TRUE\">\n";
	echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
	echo "<input type=\"submit\" value=\"Valider les saisies\">\n";
	echo " </form> \n";



	/************************************************************/
	/* SAISIE GLOBALE pour tous les utilisateurs du responsable */
	
	echo "<form action=\"$PHP_SELF\" method=\"POST\"> \n";
	echo "<table>\n";
	echo "<tr><td align=\"center\">\n";
	echo "	<fieldset class=\"cal_saisie\">\n";
	echo "	<legend class=\"boxlogin\">Ajout global pour Tous :</legend>\n";
	echo "	<table>\n";
	foreach($tab_type_conges as $id_conges => $libelle)
	{
		echo "	<tr>\n";
		echo "		<td class=\"big\">Nombre de jours de <font color=\"red\" size=\"+1\">$libelle</font> � ajouter � tous : </td>\n";
		echo "		<td><input type=\"text\" name=\"tab_new_nb_conges_all[$id_conges]\" size=\"6\" maxlength=\"6\" value=\"0\"></td>\n";
		echo "		<td> ( Calcul proportionnel � la quotit� de chaque personne : </td>\n";
		echo "		<td>OUI <input type=\"checkbox\" name=\"tab_calcul_proportionnel[$id_conges]\" value=\"TRUE\" checked> )</td>\n";
		echo "	</tr>\n";
	}
	echo "	<tr>\n";
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
	echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
	echo "</form> \n";
	
	echo "<br>\n";
	
	/***********************************************************************/
	/* SAISIE GROUPE pour tous les utilisateurs d'un groupe du responsable */
	if( $_SESSION['config']['gestion_groupes']==TRUE )
	{
		$list_group=get_list_groupes_du_resp($_SESSION['userlogin'], $mysql_link);
		if($list_group!="")  //si la liste n'est pas vide ( serait le cas si n'est responsable d'aucun groupe)
		{
			echo "<form action=\"$PHP_SELF\" method=\"POST\"> \n";
			echo "<table>\n";
			echo "<tr><td align=\"center\">\n";
			echo "	<fieldset class=\"cal_saisie\">\n";
			echo "	<legend class=\"boxlogin\">Ajout par Groupe : (ajout � tous les membres d'un groupe)</legend>\n";
			echo "	<table>\n";
			echo "	<tr>\n";
			echo "		<td class=\"big\">choix du groupe : </td>\n";
				// cr�ation du select pour le choix du groupe
				$text_choix_group="<select name=\"choix_groupe\" >";
				$sql_group = "SELECT g_gid, g_groupename FROM conges_groupe WHERE g_gid IN ($list_group) ORDER BY g_groupename "  ;
				$ReqLog_group = requete_mysql($sql_group, $mysql_link, "saisie", $DEBUG) ;
				
				while ($resultat_group = mysql_fetch_array($ReqLog_group)) {
					$current_group_id=$resultat_group["g_gid"];
					$current_group_name=$resultat_group["g_groupename"];
					$text_choix_group=$text_choix_group."<option value=\"$current_group_id\" >$current_group_name</option>";
				}
				$text_choix_group=$text_choix_group."</select>" ;

			echo "		<td colspan=\"3\">$text_choix_group</td>\n";
			echo "	</tr>\n";
			foreach($tab_type_conges as $id_conges => $libelle)
			{
				echo "	<tr>\n";
				echo "		<td class=\"big\">Nombre de jours de <font color=\"red\" size=\"+1\">$libelle</font> � ajouter au groupe : </td>\n";
				echo "		<td><input type=\"text\" name=\"tab_new_nb_conges_all[$id_conges]\" size=\"6\" maxlength=\"6\" value=\"0\"></td>\n";
				echo "		<td> ( Calcul proportionnel � la quotit� de chaque personne : </td>\n";
				echo "		<td>OUI <input type=\"checkbox\" name=\"tab_calcul_proportionnel[$id_conges]\" value=\"TRUE\" checked> )</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
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
			echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
			echo "</form> \n";
		}
	}
	
	echo "<br>\n";
	
	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"resp_main.php\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"Retour Page Principale\">\n";
	echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
	echo " </form> \n";

}

function ajout_conges($DEBUG, $tab_champ_saisie, $mysql_link) 
{
	$session=session_id(); 

	foreach($tab_champ_saisie as $user_name => $tab_conges)   // tab_champ_saisie[$current_login][$id_conges]=valeur du nb de jours ajout� saisi
	{
		foreach($tab_conges as $id_conges => $user_nb_jours_ajout)
		{
			$valid=verif_saisie_decimal($user_nb_jours_ajout, $DEBUG);   //verif la bonne saisie du nombre d�cimal
			if($valid==TRUE)
			{
				$user_nb_jours_ajout_float =(float) $user_nb_jours_ajout ;
				if($DEBUG==TRUE) {echo "$user_name --- $id_conges --- $user_nb_jours_ajout_float<br>\n";}
		
				if($user_nb_jours_ajout_float!=0)
				{
					/* Modification de la table conges_users */
					$sql1 = "UPDATE conges_solde_user SET su_solde = su_solde+$user_nb_jours_ajout_float WHERE su_login='$user_name' AND su_abs_id = $id_conges " ;
					/* On valide l'UPDATE dans la table ! */
					$ReqLog1 = requete_mysql($sql1, $mysql_link, "ajout_conges", $DEBUG) ;
		
					// on insert l'ajout de conges dans la table periode
					$commentaire="ajout jour";
					insert_ajout_dans_periode($DEBUG, $user_name, $user_nb_jours_ajout_float, $id_conges, $commentaire, $mysql_link);
				}
			}
		}
	}
	
	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"submit\" value=\"OK\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " Changements pris en compte avec succes !<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";
	}

}


function ajout_global($DEBUG, $tab_new_nb_conges_all, $tab_calcul_proportionnel, $mysql_link)
{
	$session=session_id() ;
	
	// $tab_new_nb_conges_all[$id_conges]= nb_jours
	// $tab_calcul_proportionnel[$id_conges]= TRUE / FALSE
	
	// recup de la liste de TOUS les users dont $resp_login est responsable 
	// (prend en compte le resp direct, les groupes, le reps virtuel, etc ...)
	// renvoit une liste de login entre quotes et s�par�s par des virgules
	$list_users = get_list_all_users_du_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
	if($DEBUG==TRUE) { echo "list_all_users_du_resp = $list_users<br>\n";}

	if($DEBUG==TRUE) { echo "tab_new_nb_conges_all = <br>"; print_r($tab_new_nb_conges_all); echo "<br>\n" ;}
	if($DEBUG==TRUE) { echo "tab_calcul_proportionnel = <br>"; print_r($tab_calcul_proportionnel); echo "<br>\n" ;}

	foreach($tab_new_nb_conges_all as $id_conges => $nb_jours)
	{
		if($nb_jours!=0)
		{
			$sql1="SELECT u_login, u_quotite FROM conges_users WHERE u_login IN ($list_users) ORDER BY u_login ";
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "ajout_global", $DEBUG);
				
			while($resultat1 = mysql_fetch_array($ReqLog1)) 
			{
				$current_login  =$resultat1["u_login"];
				$current_quotite=$resultat1["u_quotite"];
				
				// 1 : update de la table conges_solde_user
				$req_update = "UPDATE conges_solde_user ";
				if($tab_calcul_proportionnel[$id_conges]!=TRUE)
					$req_update = $req_update."SET su_solde = su_solde+$nb_jours ";
				else
					// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
					$req_update = $req_update."SET su_solde = su_solde +( (ROUND(($nb_jours*($current_quotite/100))*2))/2 ) ";
		
				$req_update = $req_update." WHERE  su_login = '$current_login' AND su_abs_id = $id_conges   ";

				$ReqLog_update = requete_mysql($req_update, $mysql_link, "ajout_global", $DEBUG);
		
				// 2 : on insert l'ajout de conges GLOBAL (pour tous les users) dans la table periode
				$commentaire="ajout pour tous les personnels";
				
				// ajout conges
				if($tab_calcul_proportionnel[$id_conges]!=TRUE)
					$nb_conges=$nb_jours;
				else
					// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
					$nb_conges = (ROUND(($nb_jours*($current_quotite/100))*2))/2  ;
				
				insert_ajout_dans_periode($DEBUG, $current_login, $nb_conges, $id_conges, $commentaire, $mysql_link);
			}
		}
	}
	
	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"submit\" value=\"OK\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " Changements pris en compte avec succes !<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";
	}
}

function ajout_global_groupe($DEBUG, $choix_groupe, $tab_new_nb_conges_all, $tab_calcul_proportionnel, $mysql_link)
{
	// $tab_new_nb_conges_all[$id_conges]= nb_jours
	// $tab_calcul_proportionnel[$id_conges]= TRUE / FALSE
	
	$session=session_id() ;
	
	// recup de la liste des users d'un groupe donn� 
	$list_users = get_list_users_du_groupe($choix_groupe, $mysql_link, $DEBUG);
	
	foreach($tab_new_nb_conges_all as $id_conges => $nb_jours)
	{
		if($nb_jours!=0)
		{
			$sql1="SELECT u_login, u_quotite FROM conges_users WHERE u_login IN ($list_users) ORDER BY u_login ";
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "ajout_global_groupe", $DEBUG);
				
			while ($resultat1 = mysql_fetch_array($ReqLog1)) 
			{
				$current_login  =$resultat1["u_login"];
				$current_quotite=$resultat1["u_quotite"];
				
				// 1 : on update conges_solde_user
				$req_update = "UPDATE conges_solde_user ";
				if($tab_calcul_proportionnel[$id_conges]!=TRUE)
					$req_update = $req_update."SET su_solde = su_solde+$nb_jours ";
				else
					// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
					$req_update = $req_update."SET su_solde = su_solde+( (ROUND(($nb_jours*($current_quotite/100))*2))/2 ) ";
				
				$req_update = $req_update." WHERE  su_login = '$current_login' AND su_abs_id = $id_conges   ";
		
				$ReqLog_update = requete_mysql($req_update, $mysql_link, "ajout_global_groupe", $DEBUG);
				
				
				// 2 : on insert l'ajout de conges dans la table periode
				// recup du nom du groupe
				$groupename= get_group_name_from_id($choix_groupe, $mysql_link, $DEBUG);
				$commentaire="ajout pour le groupe $groupename";
			
				// ajout conges
				if($tab_calcul_proportionnel[$id_conges]!=TRUE)
					$nb_conges=$nb_jours;
				else
					// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
					$nb_conges = (ROUND(($nb_jours*($current_quotite/100))*2))/2  ;
				
				insert_ajout_dans_periode($DEBUG, $current_login, $nb_conges, $id_conges, $commentaire, $mysql_link);
			}
	
		}
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"submit\" value=\"OK\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " Changements pris en compte avec succes !<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";
	}
}



// on insert l'ajout de conges dans la table periode
function insert_ajout_dans_periode($DEBUG, $login, $nb_jours, $id_type_abs, $commentaire, $mysql_link)
{
	$date_today=date("Y-m-d");
	
	$result=insert_dans_periode($login, $date_today, "am", $date_today, "am", $nb_jours, $commentaire, $id_type_abs, "ajout", $mysql_link, $DEBUG);
}

/*
// on insert l'ajout de conges GLOBAL (pour tous les users) dans la table periode
function insert_ajout_global_dans_periode($DEBUG, $tab_new_nb_conges_all, $commentaire, $tab_calcul_proportionnel, $mysql_link)
{
	// $tab_new_nb_conges_all[$id_conges]= nb_jours
	// $tab_calcul_proportionnel[$id_conges]= TRUE / FALSE
	
	$date_today=date("Y-m-d");
		
	// recup de la liste de TOUS les users dont $resp_login est responsable 
	// (prend en compte le resp direct, les groupes, le reps virtuel, etc ...)
	// renvoit une liste de login entre quotes et s�par�s par des virgules
	$list_all_users = get_list_all_users_du_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
	if($DEBUG==TRUE) { echo "list_all_users_du_resp = $list_users<br>\n";}

	// recup des login de tous les user pour qui on doit faire un insert dan sla table periode
	$req_select="SELECT u_login, u_quotite FROM conges_users";
	$req_select = $req_select." WHERE  u_login IN ($list_all_users)  ";
	
	$ReqLog2 = requete_mysql($req_select, $mysql_link, "insert_ajout_global_dans_periode", $DEBUG);
	
	while ($resultat = mysql_fetch_array($ReqLog2)) 
	{
		$login=$resultat["u_login"];
		$quotite=$resultat["u_quotite"];
		
		// ajout conges
		foreach($tab_new_nb_conges_all as $id_type_cong => $nb_jours)
		{
			if($nb_jours!=0)
			{
				if($tab_calcul_proportionnel[$id_type_cong]!=TRUE)
					$nb_jours_to_add=$nb_jours;
				else
					// pour arrondir au 1/2 le + proche on  fait x 2, on arrondit, puis on divise par 2 
					$nb_jours_to_add = (ROUND(($nb_jours*($quotite/100))*2))/2  ;
				insert_ajout_dans_periode($DEBUG, $login, $nb_jours_to_add, $id_type_cong, $commentaire, $mysql_link);
			}
		}
	}

}
*/
?>

</CENTER>
</body>
</html>
