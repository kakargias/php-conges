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


$DEBUG=FALSE;
//$DEBUG=TRUE ;

// verif des droits du user � afficher la page
verif_droits_user($session, "is_admin", $DEBUG);

	
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";	
echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "<TITLE> ".$_SESSION['config']['titre_admin_index']." </TITLE>\n";
echo "</head>\n";
	
$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
echo "<CENTER>\n";
	
	/*************************************/
	// init des variables:
//	$tab_checkbox_sem_imp=array();
//	$tab_checkbox_sem_p=array();
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$u_login = getpost_variable("u_login") ;
	$u_login_to_update = getpost_variable("u_login_to_update") ;
	$tab_new_user['login'] = getpost_variable("new_login") ;
	$tab_new_user['nom'] = getpost_variable("new_nom") ;
	$tab_new_user['prenom']  = getpost_variable("new_prenom") ;
	$tab_new_user['quotite']   = getpost_variable("new_quotite") ;
	$tab_new_user['is_resp'] = getpost_variable("new_is_resp") ;
	$tab_new_user['resp_login'] = getpost_variable("new_resp_login") ;
	$tab_new_user['is_admin'] = getpost_variable("new_is_admin") ;
	$tab_new_user['see_all']    = getpost_variable("new_see_all") ;	
	$tab_new_user['email'] = getpost_variable("new_email") ;
	$tab_new_user['jour'] = getpost_variable("new_jour") ;
	$tab_new_user['mois'] = getpost_variable("new_mois") ;
	$tab_new_user['year'] = getpost_variable("new_year") ;
	$tab_new_jours_an = getpost_variable("tab_new_jours_an") ;
	$tab_new_solde    = getpost_variable("tab_new_solde") ;
	$tab_checkbox_sem_imp = getpost_variable("tab_checkbox_sem_imp") ;
	$tab_checkbox_sem_p = getpost_variable("tab_checkbox_sem_p") ;
	
/*	if(isset($_POST['new_nb_j_an'])) { $tab_new_user['nb_j_an']=$_POST['new_nb_j_an']; }
	if(isset($_POST['new_solde_jours'])) { $tab_new_user['solde_jours']=$_POST['new_solde_jours']; }
	if(isset($_POST['new_rtt_an'])) { $tab_new_user['rtt_an']=$_POST['new_rtt_an']; }
	if(isset($_POST['new_solde_rtt'])) { $tab_new_user['solde_rtt']=$_POST['new_solde_rtt']; }
*/
	/*************************************/
	
	// TITRE
	if($u_login!="")
		$login_titre = $u_login;
	elseif($u_login_to_update!="")
		$login_titre = $u_login_to_update;
		
	echo "<H1>".$_SESSION['lang']['admin_modif_user_titre']." : $login_titre .</H1>\n\n";

		
	if($u_login!="") 
	{
		modifier($u_login, $tab_checkbox_sem_imp, $tab_checkbox_sem_p, $DEBUG);
	}
	elseif($u_login_to_update!="") 
	{
		commit_update($u_login_to_update, $tab_new_user, $tab_new_jours_an, $tab_new_solde, $tab_checkbox_sem_imp, $tab_checkbox_sem_p, $DEBUG);
	}
	else
	{
		// renvoit sur la page principale .
		header("Location: admin_index.php?session=$session&onglet=admin-users");
	}
	
echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";
echo "</CENTER>\n";
echo "</body>\n";
echo "</html>\n";



/*************************************************************************************************/
/*   FONCTIONS    */
/*************************************************************************************************/


function modifier($u_login, $tab_checkbox_sem_imp, $tab_checkbox_sem_p, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	// recup du tableau des types de conges (seulement les conges)
	$tab_type_conges=recup_tableau_types_conges($mysql_link, $DEBUG);

	// recup du tableau des types de conges (seulement les conges)
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) 
	{
	  $tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels($mysql_link, $DEBUG);
	}

	// R�cup�ration des informations
	$tab_user = recup_infos_du_user($u_login, "", $mysql_link, $DEBUG);

	/********************/
	/* Etat utilisateur */
	/********************/
	echo "<form action=$PHP_SELF?session=$session&u_login_to_update=$u_login method=\"POST\">\n" ;
	// AFFICHAGE TABLEAU DES INFOS	
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['divers_nom_maj_1']."</td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['divers_prenom_maj_1']."</td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['divers_login_maj_1']."</td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['divers_quotite_maj_1']."</td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['admin_users_is_resp']."</td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['admin_users_resp_login']."</td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['admin_users_is_admin']."</td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['admin_users_see_all']."</td>\n";
	if($_SESSION['config']['where_to_find_user_email']=="dbconges")
		echo "<td class=\"histo\">".$_SESSION['lang']['admin_users_mail']."</td>\n";
	echo "</tr>\n";
	
	// AFICHAGE DE LA LIGNE DES VALEURS ACTUELLES A MOFIDIER
	echo "<tr>\n";
	echo "<td class=\"histo\">".$tab_user['nom']."</td>\n";
	echo "<td class=\"histo\">".$tab_user['prenom']."</td>\n";
	echo "<td class=\"histo\">".$tab_user['login']."</td>\n";
	echo "<td class=\"histo\">".$tab_user['quotite']."</td>\n";
	echo "<td class=\"histo\">".$tab_user['is_resp']."</td>\n";
	echo "<td class=\"histo\">".$tab_user['resp_login']."</td>\n";
	echo "<td class=\"histo\">".$tab_user['is_admin']."</td>\n";
	echo "<td class=\"histo\">".$tab_user['see_all']."</td>\n";
	if($_SESSION['config']['where_to_find_user_email']=="dbconges")
		echo "<td class=\"histo\">".$tab_user['email']."</td>\n";
	echo "</tr>\n";
	
	// contruction des champs de saisie 
	$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"30\" value=\"".$tab_user['login']."\">" ;
	$text_nom="<input type=\"text\" name=\"new_nom\" size=\"10\" maxlength=\"30\" value=\"".$tab_user['nom']."\">" ;
	$text_prenom="<input type=\"text\" name=\"new_prenom\" size=\"10\" maxlength=\"30\" value=\"".$tab_user['prenom']."\">" ;
	$text_quotite="<input type=\"text\" name=\"new_quotite\" size=\"3\" maxlength=\"3\" value=\"".$tab_user['quotite']."\">" ;
	if($tab_user['is_resp']=="Y")
		$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"Y\">Y</option><option value=\"N\">N</option></select>" ;
	else
		$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
		
	if($tab_user['is_admin']=="Y")
		$text_is_admin="<select name=\"new_is_admin\" ><option value=\"Y\">Y</option><option value=\"N\">N</option></select>" ;
	else
		$text_is_admin="<select name=\"new_is_admin\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	
	if($tab_user['see_all']=="Y")
		$text_see_all="<select name=\"new_see_all\" ><option value=\"Y\">Y</option><option value=\"N\">N</option></select>" ;
	else
		$text_see_all="<select name=\"new_see_all\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	
	if($_SESSION['config']['where_to_find_user_email']=="dbconges")
		$text_email="<input type=\"text\" name=\"new_email\" size=\"10\" maxlength=\"99\" value=\"".$tab_user['email']."\">" ;
	
		
	$text_resp_login="<select name=\"new_resp_login\" id=\"resp_login_id\" >" ;
	// construction des options du SELECT pour new_resp_login
	$sql2 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp = \"Y\" ORDER BY u_nom,u_prenom"  ;
	$ReqLog2 = requete_mysql($sql2, $mysql_link, "modifier", $DEBUG);

	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
			if($resultat2["u_login"]==$tab_user['resp_login'] )
				$text_resp_login=$text_resp_login."<option value=\"".$resultat2["u_login"]."\" selected>".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
			else
				$text_resp_login=$text_resp_login."<option value=\"".$resultat2["u_login"]."\">".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
		}

	$text_resp_login=$text_resp_login."</select>" ;

	// AFFICHAGE ligne de saisie
	echo "<tr>\n";
	echo "<td class=\"histo\">$text_nom</td>\n";
	echo "<td class=\"histo\">$text_prenom</td>\n";
	echo "<td class=\"histo\">$text_login</td>\n";
	echo "<td class=\"histo\">$text_quotite</td>\n";
	echo "<td class=\"histo\">$text_is_resp</td>\n";
	echo "<td class=\"histo\">$text_resp_login</td>\n"; 
	echo "<td class=\"histo\">$text_is_admin</td>\n";
	echo "<td class=\"histo\">$text_see_all</td>\n";
	if($_SESSION['config']['where_to_find_user_email']=="dbconges")
		echo "<td class=\"histo\">$text_email</td>\n";
	echo "</tr>\n";

	echo "</table><br>\n\n";
	
	echo "<br>\n";
	
	// AFFICHAGE TABLEAU DES conges annuels et soldes	
	echo "<table cellpadding=\"2\" class=\"tablo\" >\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\"></td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['admin_modif_nb_jours_an']." </td>\n";
	echo "<td class=\"histo\"></td>\n";
	echo "<td class=\"histo\">".$_SESSION['lang']['divers_solde']."</td>\n";
	echo "<td class=\"histo\"></td>\n";
	echo "</tr>\n";
	foreach($tab_type_conges as $id_type_cong => $libelle)
	{
		echo "<tr align=\"center\">\n";
		echo "<td class=\"histo\">$libelle</td>\n";
		// jours / an
		echo "<td class=\"histo\">".$tab_user['conges'][$libelle]['nb_an']."</td>\n";
		$text_jours_an="<input type=\"text\" name=\"tab_new_jours_an[$id_type_cong]\" size=\"5\" maxlength=\"5\" value=\"".$tab_user['conges'][$libelle]['nb_an']."\">" ;
		echo "<td class=\"histo\">$text_jours_an</td>\n";
		// solde
		echo "<td class=\"histo\">".$tab_user['conges'][$libelle]['solde']."</td>\n";
		$text_solde_jours="<input type=\"text\" name=\"tab_new_solde[$id_type_cong]\" size=\"5\" maxlength=\"5\" value=\"".$tab_user['conges'][$libelle]['solde']."\">" ;
		echo "<td class=\"histo\">$text_solde_jours</td>\n";
		echo "</tr>\n";
	}

	// recup du tableau des types de conges (seulement les conges)
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) 
	{
	  foreach($tab_type_conges_exceptionnels as $id_type_cong_exp => $libelle)
	  {
	    echo "<tr align=\"center\">\n";
	    echo "<td class=\"histo\">$libelle</td>\n";
		// jours / an
		echo "<td class=\"histo\">0</td>\n";
		echo "<td class=\"histo\">0</td>\n";
	    // solde
	    echo "<td class=\"histo\">".$tab_user['conges'][$libelle]['solde']."</td>\n";
	    $text_solde_jours="<input type=\"text\" name=\"tab_new_solde[$id_type_cong_exp]\" size=\"5\" maxlength=\"5\" value=\"".$tab_user['conges'][$libelle]['solde']."\">" ;
	    echo "<td class=\"histo\">$text_solde_jours</td>\n";
	    echo "</tr>\n";
	  }
	}

	echo "</table><br>\n\n";

	/*********************************************************/
	// saisie des jours d'abscence RTT ou temps partiel:
	saisie_jours_absence_temps_partiel($u_login,$mysql_link, $DEBUG);
	
	echo "<br><input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">\n";
	echo "</form>\n" ;

	echo "<form action=\"admin_index.php?session=$session&onglet=admin-users\" method=\"POST\">\n" ;
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_cancel']."\">\n";
	echo "</form>\n" ;

	mysql_close($mysql_link);

}
	
function commit_update($u_login_to_update, &$tab_new_user, &$tab_new_jours_an, &$tab_new_solde, $tab_checkbox_sem_imp, $tab_checkbox_sem_p, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
		
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	$result=TRUE;
	
	// recup du tableau des types de conges (seulement les conges)
	$tab_type_conges=recup_tableau_types_conges($mysql_link, $DEBUG);

	echo "$u_login_to_update---".$tab_new_user['nom']."---".$tab_new_user['prenom']."---".$tab_new_user['quotite']."---".$tab_new_user['is_resp']."---".$tab_new_user['resp_login']."---".$tab_new_user['is_admin']."---".$tab_new_user['see_all']."---".$tab_new_user['email']."---".$tab_new_user['login']."<br>\n";
	
	foreach($tab_type_conges as $id_conges => $libelle)
	{
		$valid=verif_saisie_decimal($tab_new_jours_an[$id_conges], $DEBUG);  //verif la bonne saisie du nombre d�cimal
		$valid=verif_saisie_decimal($tab_new_solde[$id_conges], $DEBUG);  //verif la bonne saisie du nombre d�cimal
	}
	
	// UPDATE de la table conges_users
	$sql1 = "UPDATE conges_users  
		SET	u_nom='".addslashes($tab_new_user['nom'])."', 
			u_prenom='".addslashes($tab_new_user['prenom'])."',
			u_is_resp='".$tab_new_user['is_resp']."', 
			u_resp_login='".$tab_new_user['resp_login']."', 
			u_is_admin='".$tab_new_user['is_admin']."', 
			u_see_all='".$tab_new_user['see_all']."', 
			u_login='".$tab_new_user['login']."', 
			u_quotite='".$tab_new_user['quotite']."', 
			u_email='".$tab_new_user['email']."' 
		WHERE u_login='$u_login_to_update' " ;
	$result1 = requete_mysql($sql1, $mysql_link, "commit_update", $DEBUG);

	if($result1==FALSE)
		$result==FALSE;
	
	/*************************************/
	/* Mise a jour de la table conges_solde_user   */
	foreach($tab_type_conges as $id_conges => $libelle)
	{
		$valid=verif_saisie_decimal($tab_new_jours_an[$id_conges], $DEBUG);  //verif la bonne saisie du nombre d�cimal
		$valid=verif_saisie_decimal($tab_new_solde[$id_conges], $DEBUG);  //verif la bonne saisie du nombre d�cimal
		$sql_solde = "UPDATE conges_solde_user  
			SET	su_nb_an=$tab_new_jours_an[$id_conges], 
				su_solde=$tab_new_solde[$id_conges] 
			WHERE su_login='$u_login_to_update' AND su_abs_id=$id_conges " ;
		$result_solde = requete_mysql($sql_solde, $mysql_link, "commit_update", $DEBUG);
	
		if($result_solde==FALSE)
			$result==FALSE;
	}

	/*************************************/
	/* Mise a jour de la table artt si besoin :   */
	$tab_grille_rtt_actuelle = get_current_grille_rtt($u_login_to_update, $mysql_link, $DEBUG);
	$tab_new_grille_rtt=tab_grille_rtt_from_checkbox($tab_checkbox_sem_imp, $tab_checkbox_sem_p, $DEBUG);
	
	if($tab_grille_rtt_actuelle==$tab_new_grille_rtt)
	{
		// on ne touche pas � la table artt
	}
	else
	{
		$new_date_deb_grille=$tab_new_user['year']."-".$tab_new_user['mois']."-".$tab_new_user['jour'];
		//echo "$new_date_deb_grille<br>\n" ;
	
		/****************************/
		/***   phase 1 :  ***/
		// si la derniere grille est ancienne, on l'update (on update la date de fin de grille)
		// sinon, si la derniere grille date d'aujourd'hui, on la supprime
		
		// on regarde si la grille artt a deja �t� modifi�e aujourd'hui :
		$sql_grille="SELECT a_date_fin_grille FROM conges_artt 
				WHERE a_login='$u_login_to_update' AND a_date_debut_grille='$new_date_deb_grille'";
		$result_grille = requete_mysql($sql_grille, $mysql_link, "commit_update", $DEBUG);

		$count_grille=mysql_num_rows($result_grille);
		
		if($count_grille==0) // si pas de grille modifi�e aujourd'hui : on update la date de fin de la derniere grille
		{
			// date de fin de la grille pr�cedent :
			// $new_date_fin_grille = $new_date_deb_grille -1 jour !
			$new_jour_num= (integer) $tab_new_user['jour'];
			$new_mois_num= (integer) $tab_new_user['mois'];
			$new_year_num= (integer) $tab_new_user['year'];
			$new_date_fin_grille=date("Y-m-d", mktime(0, 0, 0, $new_mois_num, $new_jour_num-1, $new_year_num)); // int mktime(int hour, int minute, int second, int month, int day, int year )

			// UPDATE de la table conges_artt 
			// en fait, on update la derni�re grille (on update la date de fin de grille), et on ajoute une nouvelle 
			// grille (avec sa date de d�but de grille)

			// on update la derni�re grille (on update la date de fin de grille)	
			$sql2 = "UPDATE conges_artt SET a_date_fin_grille='$new_date_fin_grille' 
					WHERE a_login='$u_login_to_update' AND a_date_fin_grille='9999-12-31'" ;
			$result2 = requete_mysql($sql2, $mysql_link, "commit_update", $DEBUG);

			if($result2==FALSE)
				$result==FALSE;
		}
		else  // si une grille modifi�e aujourd'hui : on delete cette grille
		{
			$sql_suppr_grille="DELETE FROM conges_artt WHERE a_login='$u_login_to_update' AND a_date_debut_grille='$new_date_deb_grille'";
			$result_suppr_grille = requete_mysql($sql_suppr_grille, $mysql_link, "commit_update", $DEBUG);

			if($result_suppr_grille==FALSE)
				$result==FALSE;
		}	

		/****************************/
		/***   phase 2 :  ***/
		// on Insert la nouvelle grille (celle qui commence aujourd'hui)
		//  on met � 'Y' les demi-journ�es de rtt (et seulement celles l�)
		$list_columns="";
		$list_valeurs="";
		$i=0;
		if($tab_checkbox_sem_imp!="") {
			while (list ($key, $val) = each ($tab_checkbox_sem_imp)) {
				//echo "$key => $val<br>\n";
				if($i!=0) 
				{
					$list_columns=$list_columns.", ";
					$list_valeurs=$list_valeurs.", ";
				}
				$list_columns=$list_columns." $key ";
				$list_valeurs=$list_valeurs." '$val' ";
				$i=$i+1;
			}
		}
		if($tab_checkbox_sem_p!="") {
			while (list ($key, $val) = each ($tab_checkbox_sem_p)) {
				//echo "$key => $val<br>\n";
				if($i!=0) 
				{
					$list_columns=$list_columns.", ";
					$list_valeurs=$list_valeurs.", ";
				}
				$list_columns=$list_columns." $key ";
				$list_valeurs=$list_valeurs." '$val' ";
				$i=$i+1;
			}
		}
		if( ($list_columns!="") && ($list_valeurs!="") )
		{
			$sql3 = "INSERT INTO conges_artt (a_login, $list_columns, a_date_debut_grille )
					VALUES ('$u_login_to_update', $list_valeurs, '$new_date_deb_grille') " ;
			$result3 = requete_mysql($sql3, $mysql_link, "commit_update", $DEBUG);

			if($result3==FALSE)
				$result==FALSE;
		}
	}

	// Si changement du login, (on a d�ja updat� la table users) on update toutes les autres tables
	// (les grilles artt, les periodes de conges et les �changes de rtt, etc ....) avec le nouveau login
	if($tab_new_user['login'] != $u_login_to_update)
	{
		// update table artt
		$sql_upd_artt = "UPDATE conges_artt SET a_login='".$tab_new_user['login']."' WHERE a_login='$u_login_to_update'" ;
		$result4 = requete_mysql($sql_upd_artt, $mysql_link, "commit_update", $DEBUG);

		if($result4==FALSE)
			$result==FALSE;

		// update table echange_rtt
		$sql_upd_echange = "UPDATE conges_echange_rtt SET e_login='".$tab_new_user['login']."' WHERE e_login='$u_login_to_update'" ;
		$result5 = requete_mysql($sql_upd_echange, $mysql_link, "commit_update", $DEBUG);

		if($result5==FALSE)
			$result==FALSE;

		// update table edition_papier
		$sql_upd_edpap = "UPDATE conges_edition_papier SET ep_login='".$tab_new_user['login']."' WHERE ep_login='$u_login_to_update'" ;
		$result6 = requete_mysql($sql_upd_edpap, $mysql_link, "commit_update", $DEBUG);

		if($result6==FALSE)
			$result==FALSE;

		// update table groupe_grd_resp
		$sql_upd_grd_resp = "UPDATE conges_groupe_grd_resp SET ggr_login='".$tab_new_user['login']."' WHERE ggr_login='$u_login_to_update'" ;
		$result7 = requete_mysql($sql_upd_grd_resp, $mysql_link, "commit_update", $DEBUG);
		
		if($result7==FALSE)
			$result==FALSE;

		// update table groupe_resp
		$sql_upd_resp = "UPDATE conges_groupe_resp SET gr_login='".$tab_new_user['login']."' WHERE gr_login='$u_login_to_update'" ;
		$result8 = requete_mysql($sql_upd_resp, $mysql_link, "commit_update", $DEBUG);
		
		if($result8==FALSE)
			$result==FALSE;

		// update table conges_groupe_users
		$sql_upd_gr_user = "UPDATE conges_groupe_users SET gu_login='".$tab_new_user['login']."' WHERE gu_login='$u_login_to_update'" ;
		$result9 = requete_mysql($sql_upd_gr_user, $mysql_link, "commit_update", $DEBUG);
		
		if($result9==FALSE)
			$result==FALSE;

		// update table periode
		$sql_upd_periode = "UPDATE conges_periode SET p_login='".$tab_new_user['login']."' WHERE p_login='$u_login_to_update'" ;
		$result10 = requete_mysql($sql_upd_periode, $mysql_link, "commit_update", $DEBUG);
		
		if($result10==FALSE)
			$result==FALSE;
			
		// update table conges_solde_user  
		$sql_upd_su = "UPDATE conges_solde_user SET su_login='".$tab_new_user['login']."' WHERE su_login='$u_login_to_update'" ;
		$result11 = requete_mysql($sql_upd_su, $mysql_link, "commit_update", $DEBUG);
		
		if($result11==FALSE)
			$result==FALSE;
			
	}
	
	if($tab_new_user['login'] != $u_login_to_update)
		$comment_log = "modif_user (old_login = $u_login_to_update)  new_login = ".$tab_new_user['login'];
	else
		$comment_log = "modif_user login = $u_login_to_update";
	
	log_action(0, "", $u_login_to_update, $comment_log, $mysql_link, $DEBUG);

	if($result==TRUE)
		echo $_SESSION['lang']['form_modif_ok']." !<br><br> \n";
	else
		echo $_SESSION['lang']['form_modif_not_ok']." !<br><br> \n";

	mysql_close($mysql_link);
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-users\">";

}


function get_current_grille_rtt($u_login_to_update, $mysql_link, $DEBUG=FALSE)
{
	$tab_grille=array();
	
	$sql1 = "SELECT * FROM conges_artt WHERE a_login='$u_login_to_update' AND a_date_fin_grille='9999-12-31' "  ;
	$ReqLog1 = requete_mysql($sql1, $mysql_link, "get_current_grille_rtt", $DEBUG);

	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$tab_grille['sem_imp_lu_am'] = $resultat1['sem_imp_lu_am'] ;
		$tab_grille['sem_imp_lu_pm'] = $resultat1['sem_imp_lu_pm'] ;
		$tab_grille['sem_imp_ma_am'] = $resultat1['sem_imp_ma_am'] ;
		$tab_grille['sem_imp_ma_pm'] = $resultat1['sem_imp_ma_pm'] ;
		$tab_grille['sem_imp_me_am'] = $resultat1['sem_imp_me_am'] ;
		$tab_grille['sem_imp_me_pm'] = $resultat1['sem_imp_me_pm'] ;
		$tab_grille['sem_imp_je_am'] = $resultat1['sem_imp_je_am'] ;
		$tab_grille['sem_imp_je_pm'] = $resultat1['sem_imp_je_pm'] ;
		$tab_grille['sem_imp_ve_am'] = $resultat1['sem_imp_ve_am'] ;
		$tab_grille['sem_imp_ve_pm'] = $resultat1['sem_imp_ve_pm'] ;
		$tab_grille['sem_imp_sa_am'] = $resultat1['sem_imp_sa_am'] ;
		$tab_grille['sem_imp_sa_pm'] = $resultat1['sem_imp_sa_pm'] ;
		$tab_grille['sem_imp_di_am'] = $resultat1['sem_imp_di_am'] ;
		$tab_grille['sem_imp_di_pm'] = $resultat1['sem_imp_di_pm'] ;
		
		$tab_grille['sem_p_lu_am'] = $resultat1['sem_p_lu_am'] ;
		$tab_grille['sem_p_lu_pm'] = $resultat1['sem_p_lu_pm'] ;
		$tab_grille['sem_p_ma_am'] = $resultat1['sem_p_ma_am'] ;
		$tab_grille['sem_p_ma_pm'] = $resultat1['sem_p_ma_pm'] ;
		$tab_grille['sem_p_me_am'] = $resultat1['sem_p_me_am'] ;
		$tab_grille['sem_p_me_pm'] = $resultat1['sem_p_me_pm'] ;
		$tab_grille['sem_p_je_am'] = $resultat1['sem_p_je_am'] ;
		$tab_grille['sem_p_je_pm'] = $resultat1['sem_p_je_pm'] ;
		$tab_grille['sem_p_ve_am'] = $resultat1['sem_p_ve_am'] ;
		$tab_grille['sem_p_ve_pm'] = $resultat1['sem_p_ve_pm'] ;
		$tab_grille['sem_p_sa_am'] = $resultat1['sem_p_sa_am'] ;
		$tab_grille['sem_p_sa_pm'] = $resultat1['sem_p_sa_pm'] ;
		$tab_grille['sem_p_di_am'] = $resultat1['sem_p_di_am'] ;
		$tab_grille['sem_p_di_pm'] = $resultat1['sem_p_di_pm'] ;
	}

	if($DEBUG==TRUE)
	{
		echo "get_current_grille_rtt :<br>\n";
		print_r($tab_grille);	
		echo "<br>\n";
	}
	
	return $tab_grille;
}


function tab_grille_rtt_from_checkbox($tab_checkbox_sem_imp, $tab_checkbox_sem_p, $DEBUG=FALSE)
{
	$tab_grille=array();
	$semaine=array("lu", "ma", "me", "je", "ve", "sa", "di");
	
	// initialiastaion du tableau
	foreach($semaine as $day){
		$key1="sem_imp_".$day."_am";
		$key2="sem_imp_".$day."_pm";
		$tab_grille[$key1] = "";
		$tab_grille[$key2] = "";
		$key3="sem_p_".$day."_am";
		$key4="sem_p_".$day."_pm";
		$tab_grille[$key3] = "";
		$tab_grille[$key4] = "";
	}
	
	// mise a jour du tab avec les valeurs des chechbox 
	if($tab_checkbox_sem_imp!="") {
		while (list ($key, $val) = each ($tab_checkbox_sem_imp)) {
			//echo "$key => $val<br>\n";
			$tab_grille[$key]=$val;
		}
	}
	if($tab_checkbox_sem_p!="") {
		while (list ($key, $val) = each ($tab_checkbox_sem_p)) {
			//echo "$key => $val<br>\n";
			$tab_grille[$key]=$val;
		}
	}

	if($DEBUG==TRUE)
	{
		echo "tab_grille_rtt_from_checkbox :<br>\n";
		print_r($tab_grille);	
		echo "<br>\n";
	}

	return $tab_grille;
}

?>
