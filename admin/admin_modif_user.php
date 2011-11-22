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
	if(isset($_GET['u_login'])) { $u_login=$_GET['u_login']; }
	if(isset($_GET['u_login_to_update'])) { $u_login_to_update=$_GET['u_login_to_update']; }
	// POST
	if(!isset($u_login_to_update))
		if(isset($_GET['u_login_to_update'])) { $u_login_to_update=$_POST['u_login_to_update']; }
	if(isset($_POST['new_login'])) { $new_login=$_POST['new_login']; }
	if(isset($_POST['new_nom'])) { $new_nom=$_POST['new_nom']; }
	if(isset($_POST['new_prenom'])) { $new_prenom=$_POST['new_prenom']; }
	if(isset($_POST['new_quotite'])) { $new_quotite=$_POST['new_quotite']; }
	if(isset($_POST['new_nb_j_an'])) { $new_nb_j_an=$_POST['new_nb_j_an']; }
	if(isset($_POST['new_solde_jours'])) { $new_solde_jours=$_POST['new_solde_jours']; }
	if(isset($_POST['new_rtt_an'])) { $new_rtt_an=$_POST['new_rtt_an']; }
	if(isset($_POST['new_solde_rtt'])) { $new_solde_rtt=$_POST['new_solde_rtt']; }
	if(isset($_POST['new_is_resp'])) { $new_is_resp=$_POST['new_is_resp']; }
	if(isset($_POST['new_resp_login'])) { $new_resp_login=$_POST['new_resp_login']; }
	if(isset($_POST['new_email'])) { $new_email=$_POST['new_email']; }
	if(isset($_POST['tab_checkbox_sem_imp'])) { $tab_checkbox_sem_imp=$_POST['tab_checkbox_sem_imp']; }
	if(isset($_POST['tab_checkbox_sem_p'])) { $tab_checkbox_sem_p=$_POST['tab_checkbox_sem_p']; }
	if(isset($_POST['new_jour'])) { $new_jour=$_POST['new_jour']; }
	if(isset($_POST['new_mois'])) { $new_mois=$_POST['new_mois']; }
	if(isset($_POST['new_year'])) { $new_year=$_POST['new_year']; }
	/*************************************/
	
	// TITRE
	if(isset($u_login))
		printf("<H1>Modification utilisateur : %s .</H1>\n\n", $u_login);
	elseif(isset($u_login_to_update))
		printf("<H1>Modification utilisateur : %s .</H1>\n\n", $u_login_to_update);

		
	if(isset($u_login)) {
		modifier($u_login);
	}
	else {
		if(isset($u_login_to_update)) {
			commit_update($u_login_to_update);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session&onglet=admin-users");
		}
	}

function modifier($u_login) {
	global $PHP_SELF;
	global $session;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p, $config_rtt_comme_conges, $config_where_to_find_user_email ;
	
	//connexion mysql
	$link = connexion_mysql() ;
	
	/********************/
	/* Etat utilisateur */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_quotite, u_email FROM conges_users WHERE u_login = '$u_login' " ;
	// AFFICHAGE TABLEAU
	printf("<form action=$PHP_SELF?session=$session&u_login_to_update=".$u_login." method=\"POST\">\n" ) ;
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\">Nom</td>\n";
	echo "<td class=\"histo\">Prenom</td>\n";
	echo "<td class=\"histo\">login</td>\n";
	echo "<td class=\"histo\">Quotité</td>\n";
	echo "<td class=\"histo\">nb congés / an</td>\n";
	echo "<td class=\"histo\">solde congés</td>\n";
	if($config_rtt_comme_conges==TRUE)
		echo "<td class=\"histo\">nb rtt / an</td>\n<td class=\"histo\">solde rtt</td>\n";
	echo "<td class=\"histo\">is_resp</td>\n";
	echo "<td class=\"histo\">resp_login</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"histo\">email</td>\n";
	echo "</tr>\n";
	
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$sql_nom=$resultat1["u_nom"];
		$sql_prenom=$resultat1["u_prenom"];
		$sql_login=$resultat1["u_login"];
		$sql_quotite=$resultat1["u_quotite"];
		$sql_nb_jours_an=$resultat1["u_nb_jours_an"];
		$sql_solde_jours=$resultat1["u_solde_jours"];
		$sql_nb_rtt_an=$resultat1["u_nb_rtt_an"];
		$sql_solde_rtt=$resultat1["u_solde_rtt"];
		$sql_is_resp=$resultat1["u_is_resp"];
		$sql_resp_login=$resultat1["u_resp_login"];
		$sql_email=$resultat1["u_email"];
	}
	
	// AFICHAGE DE LA LIGNE DES VALEURS ACTUELLES A MOFIDIER
	echo "<tr>\n";
	echo "<td class=\"histo\">$sql_nom</td>\n";
	echo "<td class=\"histo\">$sql_prenom</td>\n";
	echo "<td class=\"histo\">$sql_login</td>\n";
	echo "<td class=\"histo\">$sql_quotite</td>\n";
	echo "<td class=\"histo\">$sql_nb_jours_an</td>\n";
	echo "<td class=\"histo\">$sql_solde_jours</td>\n";
	if($config_rtt_comme_conges==TRUE)
	{
		echo "<td class=\"histo\">$sql_nb_rtt_an</td>\n";
		echo "<td class=\"histo\">$sql_solde_rtt</td>\n";
	}
	echo "<td class=\"histo\">$sql_is_resp</td>\n";
	echo "<td class=\"histo\">$sql_resp_login</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"histo\">$sql_email</td>\n";
	echo "</tr>\n";
	
	// contruction des champs de saisie 
	$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"30\" value=\"".$sql_login."\">" ;
	$text_nom="<input type=\"text\" name=\"new_nom\" size=\"10\" maxlength=\"30\" value=\"".$sql_nom."\">" ;
	$text_prenom="<input type=\"text\" name=\"new_prenom\" size=\"10\" maxlength=\"30\" value=\"".$sql_prenom."\">" ;
	$text_quotite="<input type=\"text\" name=\"new_quotite\" size=\"3\" maxlength=\"3\" value=\"".$sql_quotite."\">" ;
	$text_nb_j_an="<input type=\"text\" name=\"new_nb_j_an\" size=\"5\" maxlength=\"5\" value=\"".$sql_nb_jours_an."\">" ;
	$text_solde_jours="<input type=\"text\" name=\"new_solde_jours\" size=\"5\" maxlength=\"5\" value=\"".$sql_solde_jours."\">" ;
	if($config_rtt_comme_conges==TRUE)
	{
		$text_rtt_an="<input type=\"text\" name=\"new_rtt_an\" size=\"5\" maxlength=\"5\" value=\"".$sql_nb_rtt_an."\">" ;
		$text_solde_rtt="<input type=\"text\" name=\"new_solde_rtt\" size=\"5\" maxlength=\"5\" value=\"".$sql_solde_rtt."\">" ;
	}

	if($sql_is_resp=="Y")
		$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"Y\">Y</option><option value=\"N\">N</option></select>" ;
	else
		$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	
	$text_resp_login="<select name=\"new_resp_login\" id=\"resp_login_id\" >" ;
	
	if($config_where_to_find_user_email=="dbconges")
		$text_email="<input type=\"text\" name=\"new_email\" size=\"10\" maxlength=\"99\" value=\"".$sql_email."\">" ;
	
		
	// construction des options du SELECT
	$sql2 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp = \"Y\" ORDER BY u_nom,u_prenom"  ;
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
			if($resultat2["u_login"]==$sql_resp_login )
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
	echo "<td class=\"histo\">$text_nb_j_an</td>\n";
	echo "<td class=\"histo\">$text_solde_jours</td>\n";
	if($config_rtt_comme_conges==TRUE)
	{
		echo "<td class=\"histo\">$text_rtt_an</td>\n";
		echo "<td class=\"histo\">$text_solde_rtt</td>\n";
	}
	echo "<td class=\"histo\">$text_is_resp</td>\n";
	echo "<td class=\"histo\">$text_resp_login</td>\n"; 
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"histo\">$text_email</td>\n";
	echo "</tr>\n";
	
	printf("</table><br>\n\n");
	
	
	// saisie des jours d'abscence ARTT ou temps partiel:
	saisie_jours_absence_temps_partiel($u_login,$link);
	
	printf("<br><input type=\"submit\" value=\"Valider\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"admin_index.php?session=$session&onglet=admin-users\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;

	mysql_close($link);

	}
	
function commit_update($u_login_to_update) {
	global $PHP_SELF;
	global $session;
	global $config_rtt_comme_conges;
	global $new_nom, $new_prenom, $new_quotite, $new_nb_j_an, $new_solde_jours, $new_rtt_an, $new_solde_rtt, $new_email;
	global $new_is_resp, $new_resp_login, $new_login;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p ;
	global $new_jour, $new_mois, $new_year ;
	
	//connexion mysql
	$link = connexion_mysql() ;
	$result=TRUE;
	
	echo "$u_login_to_update---$new_nom---$new_prenom---$new_quotite---$new_nb_j_an---$new_solde_jours---$new_rtt_an---$new_solde_rtt---$new_is_resp---$new_resp_login---$new_email---$new_login<br>\n";
	
	if($config_rtt_comme_conges==FALSE)
	{
		$new_rtt_an=0;
		$new_solde_rtt=0;
	}
	
	$valid=verif_saisie_decimal($new_nb_j_an);    //verif la bonne saisie du nombre décimal
	$valid=verif_saisie_decimal($new_solde_jours);    //verif la bonne saisie du nombre décimal
	$valid=verif_saisie_decimal($new_rtt_an);    //verif la bonne saisie du nombre décimal
	$valid=verif_saisie_decimal($new_solde_rtt);    //verif la bonne saisie du nombre décimal
	
	// UPDATE de la table conges_users
	$sql1 = "UPDATE conges_users  SET u_nom='$new_nom', u_prenom='$new_prenom', u_nb_jours_an='$new_nb_j_an', u_solde_jours='$new_solde_jours', u_nb_rtt_an='$new_rtt_an', u_solde_rtt='$new_solde_rtt', u_is_resp='$new_is_resp', u_resp_login='$new_resp_login', u_login='$new_login', u_quotite='$new_quotite', u_email='$new_email' WHERE u_login='$u_login_to_update' " ;
	$result1 = mysql_query($sql1, $link) or die("ERREUR : commit_update() : ".mysql_error());
	if($result1==FALSE)
		$result==FALSE;
	
	$tab_grille_rtt_actuelle = get_current_grille_rtt($u_login_to_update, $link);
	$tab_new_grille_rtt=tab_grille_rtt_from_checkbox($tab_checkbox_sem_imp, $tab_checkbox_sem_p);
	
	if($tab_grille_rtt_actuelle==$tab_new_grille_rtt)
	{
		// on ne touche pas à la table artt
	}
	else
	{
		$new_date_deb_grille="$new_year-$new_mois-$new_jour";
		echo "$new_date_deb_grille<br>\n" ;
	
		// $new_date_fin_grille = $new_date_deb_grille -1 jour !
		$new_jour_num= (integer) $new_jour;
		$new_mois_num= (integer) $new_mois;
		$new_year_num= (integer) $new_year;
		$new_date_fin_grille=date("Y-m-d", mktime(0, 0, 0, $new_mois_num, $new_jour_num-1, $new_year_num)); // int mktime(int hour, int minute, int second, int month, int day, int year )
	
		// UPDATE de la table conges_artt 
		// en fait, on update la dernière grille (on update la date de fin de grille), et on ajoute une nouvelle 
		// grille (avec sa date de début de grille)

		// phase 1 : on update la dernière grille (on update la date de fin de grille)	
		$sql2 = "UPDATE conges_artt SET a_date_fin_grille='$new_date_fin_grille' 
				WHERE a_login='$u_login_to_update' AND a_date_fin_grille='9999-12-31'" ;

		//echo "sql2 = $sql2<br>\n";
		$result2 = mysql_query($sql2, $link) or die("ERREUR : commit_update() : ".mysql_error());
		if($result2==FALSE)
			$result==FALSE;

		// phase 2 : on met à 'Y' les demi-journées de rtt (et seulement celles là)
		$list_columns="";
		$list_valeurs="";
		$i=0;
		if(isset($tab_checkbox_sem_imp)) {
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
		if(isset($tab_checkbox_sem_p)) {
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
			//echo "sql3 = $sql3<br>\n";
			$result3 = mysql_query($sql3, $link) or die("ERREUR : commit_update() : \n$sql3\n".mysql_error());
			if($result3==FALSE)
				$result==FALSE;
		}
	}
	
	// Si changement du login, (on a dèja updaté la table users) on update toutes les autres tables
	// (les grilles artt, les periodes de conges et les échanges de rtt) avec le nouveau login
	if($new_login!=$u_login_to_update)
	{
		// update table artt
		$sql_upd_artt = "UPDATE conges_artt SET a_login='$new_login' WHERE a_login='$u_login_to_update'" ;
		//echo "sql_upd_artt = $sql_upd_artt<br>\n";
		$result4 = mysql_query($sql_upd_artt, $link) or die("ERREUR : commit_update() : ".mysql_error());
		if($result4==FALSE)
			$result==FALSE;

		// update table periode
		$sql_upd_periode = "UPDATE conges_periode SET p_login='$new_login' WHERE p_login='$u_login_to_update'" ;
		//echo "sql_upd_periode = $sql_upd_periode<br>\n";
		$result5 = mysql_query($sql_upd_periode, $link) or die("ERREUR : commit_update() : ".mysql_error());
		if($result5==FALSE)
			$result==FALSE;

		// update table echange_rtt
		$sql_upd_echange = "UPDATE conges_echange_rtt SET e_login='$new_login' WHERE e_login='$u_login_to_update'" ;
		//echo "sql_upd_echange = $sql_upd_echange<br>\n";
		$result6 = mysql_query($sql_upd_echange, $link) or die("ERREUR : commit_update() : ".mysql_error());
		if($result6==FALSE)
			$result==FALSE;
	}
	
	if($result==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	mysql_close($link);
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-users\">";

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>


<?
// FONCTIONS :

function get_current_grille_rtt($u_login_to_update, $link)
{
	$tab_grille=array();
	
	$sql1 = "SELECT * FROM conges_artt WHERE a_login='$u_login_to_update' AND a_date_fin_grille='9999-12-31' "  ;
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : get_current_grille_rtt() : ".mysql_error());
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

	//echo "get_current_grille_rtt :<br>\n";
	//array_walk ($tab_grille, 'test_print_array');	
	return $tab_grille;
}


function tab_grille_rtt_from_checkbox($tab_checkbox_sem_imp, $tab_checkbox_sem_p)
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
	if(isset($tab_checkbox_sem_imp)) {
		while (list ($key, $val) = each ($tab_checkbox_sem_imp)) {
			//echo "$key => $val<br>\n";
			$tab_grille[$key]=$val;
		}
	}
	if(isset($tab_checkbox_sem_p)) {
		while (list ($key, $val) = each ($tab_checkbox_sem_p)) {
			//echo "$key => $val<br>\n";
			$tab_grille[$key]=$val;
		}
	}

	//echo "tab_grille_rtt_from_checkbox :<br>\n";
	//array_walk ($tab_grille, 'test_print_array');	
	return $tab_grille;
}


function test_print_array ($item, $key) {
	echo "$key. $item<br />\n";
}


?>
