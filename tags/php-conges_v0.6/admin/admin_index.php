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
if($config_verif_droits==1){ include("../INCLUDE.PHP/verif_droits.php");}
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
	
	/*** initialisation des variables ***/
	$password1="";
	$password2="";
	/************************************/

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	// POST
	if(isset($_POST['saisie_user'])) { $saisie_user=$_POST['saisie_user']; }
	if(isset($_POST['new_login'])) { $new_login=$_POST['new_login']; }
	if(isset($_POST['new_nom'])) { $new_nom=$_POST['new_nom']; }
	if(isset($_POST['new_prenom'])) { $new_prenom=$_POST['new_prenom']; }
	if(isset($_POST['new_quotite'])) { $new_quotite=$_POST['new_quotite']; }
	if(isset($_POST['new_jours_an'])) { $new_jours_an=$_POST['new_jours_an']; }
	if(isset($_POST['new_solde_jours'])) { $new_solde_jours=$_POST['new_solde_jours']; }
	if(isset($_POST['new_rtt_an'])) { $new_rtt_an=$_POST['new_rtt_an']; }
	if(isset($_POST['new_solde_rtt'])) { $new_solde_rtt=$_POST['new_solde_rtt']; }
	if(isset($_POST['new_is_resp'])) { $new_is_resp=$_POST['new_is_resp']; }
	if(isset($_POST['new_resp_login'])) { $new_resp_login=$_POST['new_resp_login']; }
	if(isset($_POST['new_password1'])) { $new_password1=$_POST['new_password1']; }
	if(isset($_POST['new_password2'])) { $new_password2=$_POST['new_password2']; }
	if(isset($_POST['new_email'])) { $new_email=$_POST['new_email']; }
	if(isset($_POST['tab_checkbox_sem_imp'])) { $tab_checkbox_sem_imp=$_POST['tab_checkbox_sem_imp']; }
	if(isset($_POST['tab_checkbox_sem_p'])) { $tab_checkbox_sem_p=$_POST['tab_checkbox_sem_p']; }
	/*************************************/
	
	// titre
	printf("<H2>Administration de la DataBase : </H2>\n\n");
	//connexion mysql
	$link = connexion_mysql() ;
	
	if($saisie_user=="ok") {
		ajout_user();
	}
	else {
		affichage();  /* affichage normal */
	}
	
	mysql_close($link);
	
/*** FONCTIONS ***/

function affichage() {
	global $PHP_SELF, $link;
	global $config_admin_see_all , $config_responsable_virtuel, $config_rtt_comme_conges, $config_where_to_find_user_email;
	global $session;
	global $session_username ;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_email ;
	global $new_rtt_an, $new_solde_rtt, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;

	/*********************/
	/* Etat Utilisateurs */
	/*********************/
	// Récuperation des informations
	
	// si l'admin peut voir tous les users  OU si on est en mode "responsble virtuel" (cf config.php)
	if(($config_admin_see_all==1) || ($config_responsable_virtuel==1))   
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_passwd, u_quotite, u_email FROM conges_users ORDER BY u_nom"  ;
	else
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_passwd, u_quotite, u_email FROM conges_users WHERE u_resp_login = '$session_username' ORDER BY u_nom, u_prenom"  ;

	// AFFICHAGE TABLEAU
	printf("<h3>Etat des Utilisateurs :</h3>\n");
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"titre\">Nom</td>\n";
	echo "<td class=\"titre\">Prenom</td>\n";
	echo "<td class=\"titre\">login</td>\n";
	echo "<td class=\"titre\">Quotité</td>\n";
	echo "<td class=\"titre\">nb congés / an</td>\n";
	echo "<td class=\"titre\">solde congés</td>\n";
	if($config_rtt_comme_conges==1)
		echo "<td class=\"titre\">nb rtt / an</td>\n<td class=\"titre\">solde rtt</td>\n";
	echo "<td class=\"titre\">is_resp</td>\n";
	echo "<td class=\"titre\">resp_login</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"titre\">email</td>\n";
	echo "<td></td>\n";
	echo "<td></td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";
	
	$ReqLog3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error());
	while ($resultat3 = mysql_fetch_array($ReqLog3))
	{
		
		$sql_login=$resultat3["u_login"] ;
		$sql_nom=$resultat3["u_nom"] ;
		$sql_prenom=$resultat3["u_prenom"] ;
		$sql_quotite=affiche_decimal($resultat3["u_quotite"]) ;
		$sql_nb_jours_an=$resultat3["u_nb_jours_an"] ;
		$sql_solde_jours=$resultat3["u_solde_jours"] ;
		$sql_nb_rtt_an=$resultat3["u_nb_rtt_an"] ;
		$sql_solde_rtt=$resultat3["u_solde_rtt"] ;
		$sql_is_resp=$resultat3["u_is_resp"] ;
		$sql_resp_login=$resultat3["u_resp_login"] ;
		$sql_email=$resultat3["u_email"] ;
		
		$admin_modif_user="<a href=\"admin_modif_user.php?session=$session&u_login=$sql_login\">Modifier</a>" ;
		$admin_suppr_user="<a href=\"admin_suppr_user.php?session=$session&u_login=$sql_login\">Supprimer</a>" ;
		$admin_chg_pwd_user="<a href=\"admin_chg_pwd_user.php?session=$session&u_login=$sql_login\">Password</a>" ;
		
		echo "<tr>\n";
		echo "<td class=\"histo\"><b>$sql_nom</b></td>\n";
		echo "<td class=\"histo\"><b>$sql_prenom</b></td>\n";
		echo "<td class=\"histo\">$sql_login</td>\n";
		echo "<td class=\"histo\">$sql_quotite%%</td>\n";
		echo "<td class=\"histo\">$sql_nb_jours_an</td>\n";
		echo "<td class=\"histo\">$sql_solde_jours</td>\n";
		if($config_rtt_comme_conges==1)
		{
			echo "<td class=\"histo\">$sql_nb_rtt_an</td>\n";
			echo "<td class=\"histo\">$sql_solde_rtt</td>\n";
		}
		echo "<td class=\"histo\">$sql_is_resp</td>\n";
		echo "<td class=\"histo\">$sql_resp_login</td>\n";
		if($config_where_to_find_user_email=="dbconges")
			echo "<td class=\"histo\">$sql_email</td>\n";
		echo "<td class=\"histo\">$admin_modif_user</td>\n";
		echo "<td class=\"histo\">$admin_suppr_user</td>\n";
		echo "<td class=\"histo\">$admin_chg_pwd_user</td>\n";
		echo "</tr>\n";
	}
	printf("</table>\n\n");

	
	/*********************/
	/* Ajout Utilisateur */
	/*********************/
	
	printf("<br><br><br><hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	// TITRE
	printf("<H3><u>Nouvel Utilisateur :</u></H3>\n\n");

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
	
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\">login</td>\n";
	echo "<td class=\"histo\">Nom</td>\n";
	echo "<td class=\"histo\">Prenom</td>\n";
	echo "<td class=\"histo\">Quotité</td>\n";
	echo "<td class=\"histo\">nb congés / an</td>\n";
	echo "<td class=\"histo\">solde congés</td>\n";
	if($config_rtt_comme_conges==1)
		echo "<td class=\"histo\">nb rtt / an</td>\n<td class=\"histo\">solde rtt</td>";
	echo "<td class=\"histo\">est_responsable</td>\n";
	echo "<td class=\"histo\">responsable</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"histo\">email</td>\n";
	echo "<td class=\"histo\">password</td>\n";
	echo "<td class=\"histo\">password</td>\n";
	echo "</tr>\n";

	$text_nom="<input type=\"text\" name=\"new_nom\" size=\"10\" maxlength=\"30\" value=\"".$new_nom."\">" ;
	$text_prenom="<input type=\"text\" name=\"new_prenom\" size=\"10\" maxlength=\"30\" value=\"".$new_prenom."\">" ;
	$text_quotite="<input type=\"text\" name=\"new_quotite\" size=\"3\" maxlength=\"3\" value=\"".$new_quotite."\">" ;
	$text_jours_an="<input type=\"text\" name=\"new_jours_an\" size=\"5\" maxlength=\"5\" value=\"".$new_jours_an."\">" ;
	$text_solde_jours="<input type=\"text\" name=\"new_solde_jours\" size=\"5\" maxlength=\"5\" value=\"".$new_solde_jours."\">" ;

	$text_rtt_an="<input type=\"text\" name=\"new_rtt_an\" size=\"5\" maxlength=\"5\" value=\"".$new_rtt_an."\">" ;
	$text_solde_rtt="<input type=\"text\" name=\"new_solde_rtt\" size=\"5\" maxlength=\"5\" value=\"".$new_solde_rtt."\">" ;

	$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	
	// AFFICHAGE OPTIONS DU SELECT
	$text_resp_login="<select name=\"new_resp_login\" id=\"resp_login_id\" >" ;
	$sql2 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp = \"Y\" ORDER BY u_nom, u_prenom"  ;
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
		$current_resp_login=$resultat2["u_login"];
		if($new_resp_login==$current_resp_login)
			$text_resp_login=$text_resp_login."<option value=\"$current_resp_login\" selected>".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
		else
			$text_resp_login=$text_resp_login."<option value=\"$current_resp_login\">".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
	}
	$text_resp_login=$text_resp_login."</select>" ;

	$text_email="<input type=\"text\" name=\"new_email\" size=\"10\" maxlength=\"99\" value=\"".$new_email."\">" ;
	
	$text_password1="<input type=\"password\" name=\"new_password1\" size=\"10\" maxlength=\"15\" value=\"\">" ;
	$text_password2="<input type=\"password\" name=\"new_password2\" size=\"10\" maxlength=\"15\" value=\"\">" ;
	$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"10\" value=\"".$new_login."\">" ;
	
	echo "<tr>\n";
	echo "<td class=\"histo\">$text_login</td>\n";
	echo "<td class=\"histo\">$text_nom</td>\n";
	echo "<td class=\"histo\">$text_prenom</td>\n";
	echo "<td class=\"histo\">$text_quotite</td>\n";
	echo "<td class=\"histo\">$text_jours_an</td>\n";
	echo "<td class=\"histo\">$text_solde_jours</td>\n";
	if($config_rtt_comme_conges==1)
	{
		echo "<td class=\"histo\">$text_rtt_an</td>\n";
		echo "<td class=\"histo\">$text_solde_rtt</td>\n";
	}
	echo "<td class=\"histo\">$text_is_resp</td>\n";
	echo "<td class=\"histo\">$text_resp_login</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"histo\">$text_email</td>\n";
	echo "<td class=\"histo\">$text_password1</td>\n";
	echo "<td class=\"histo\">$text_password2</td>\n";
	echo "</tr>\n";
	printf("</table><br>\n\n");
	
	// saisie des jours d'abscence ARTT ou temps partiel:
	saisie_jours_absence_temps_partiel($new_login, $link);
	
	echo "<br>\n";
	printf("<input type=\"hidden\" name=\"saisie_user\" value=\"ok\">\n");
	printf("<input type=\"submit\" value=\"Valider Nouvel Utilisateur\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;
	
}



function ajout_user() {
	global $PHP_SELF, $link;
	global $session;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_rtt_an, $new_solde_rtt ;
	global $new_is_resp, $new_resp_login, $new_password1, $new_password2, $new_email ;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p ;
	
	if(verif_new_param()==0) 
	{
		echo "$new_login---$new_nom---$new_prenom---$new_quotite---$new_jours_an---$new_solde_jours---$new_rtt_an---$new_solde_rtt---$new_is_resp---$new_resp_login---$new_email<br>\n";

		$sql1 = "INSERT INTO conges_users (u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_passwd, u_quotite, u_email ) 
				VALUES ('$new_login','$new_nom','$new_prenom', '$new_jours_an',
				'$new_solde_jours', '$new_rtt_an', '$new_solde_rtt','$new_is_resp',
				'$new_resp_login',password('$new_password1'), $new_quotite, '$new_email')" ;
		$result = mysql_query($sql1, $link) or die("ERREUR : ajout_user() : ".$sql1." --> ".mysql_error());

		$list_colums_to_insert="a_login";
		$list_values_to_insert="'$new_login'";
		// on parcours le tableau des jours d'absence semaine impaire
		if(isset($tab_checkbox_sem_imp)) {
			while (list ($key, $val) = each ($tab_checkbox_sem_imp)) {
				//echo "$key => $val<br>\n";
				$list_colums_to_insert="$list_colums_to_insert, $key";
				$list_values_to_insert="$list_values_to_insert, '$val'";
			}
		}
		if(isset($tab_checkbox_sem_p)) {
			while (list ($key, $val) = each ($tab_checkbox_sem_p)) {
				//echo "$key => $val<br>\n";
				$list_colums_to_insert="$list_colums_to_insert, $key";
				$list_values_to_insert="$list_values_to_insert, '$val'";
			}
		}


		$sql2 = "INSERT INTO conges_artt ($list_colums_to_insert) VALUES ($list_values_to_insert)" ;
		$result = mysql_query($sql2, $link) or die("ERREUR : admin_index.php : ajout_user() : \n$sql2\n".mysql_error());

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

		/* APPEL D'UNE AUTRE PAGE */
		printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
		printf("<input type=\"submit\" value=\"Retour\">\n");
		printf(" </form> \n");
	}
}

function verif_new_param() {
	global $PHP_SELF, $link;;
	global $session;
	global $config_rtt_comme_conges;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_rtt_an, $new_solde_rtt ;
	global $new_is_resp, $new_resp_login, $new_password1, $new_password2, $new_email ;

	if($config_rtt_comme_conges==0)
		$new_rtt_an=0;
	else
		$valid=verif_saisie_decimal($new_rtt_an);   //verif la bonne saisie du nombre décimal
		
	if($config_rtt_comme_conges==0)
		$new_solde_rtt=0;
	else
		$valid=verif_saisie_decimal($new_solde_rtt);   //verif la bonne saisie du nombre décimal

	$valid=verif_saisie_decimal($new_jours_an);       //verif la bonne saisie du nombre décimal
	$valid=verif_saisie_decimal($new_solde_jours);    //verif la bonne saisie du nombre décimal
		
	// verif des parametres reçus :
	if((strlen($new_nom)==0)||(strlen($new_prenom)==0)||(strlen($new_jours_an)==0)||(strlen($new_solde_jours)==0)||(strlen($new_password1)==0)||(strlen($new_password2)==0)||(strcmp($new_password1, $new_password2)!=0)||(strlen($new_login)==0)||($new_quotite>100)) {
		printf("<H3> ATTENTION : certain champs saisis ne sont pas valides ...... </H3>\n" ) ;
		echo "$new_login---$new_nom---$new_prenom---$new_quotite---$new_jours_an---$new_solde_jours---$new_rtt_an---$new_solde_rtt---$new_is_resp---$new_resp_login<br>\n";
		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"new_login\" value=\"$new_login\">\n");
		printf("<input type=\"hidden\" name=\"new_nom\" value=\"$new_nom\">\n");
		printf("<input type=\"hidden\" name=\"new_prenom\" value=\"$new_prenom\">\n");
		printf("<input type=\"hidden\" name=\"new_jours_an\" value=\"$new_jours_an\">\n");
		printf("<input type=\"hidden\" name=\"new_solde_jours\" value=\"$new_solde_jours\">\n");
		printf("<input type=\"hidden\" name=\"new_rtt_an\" value=\"$new_rtt_an\">\n");
		printf("<input type=\"hidden\" name=\"new_solde_rtt\" value=\"$new_solde_rtt\">\n");
		printf("<input type=\"hidden\" name=\"new_is_resp\" value=\"$new_is_resp\">\n");
		printf("<input type=\"hidden\" name=\"new_resp_login\" value=\"$new_resp_login\">\n");
		printf("<input type=\"hidden\" name=\"new_quotite\" value=\"$new_quotite\">\n");
		printf("<input type=\"hidden\" name=\"new_email\" value=\"$new_email\">\n");
		
		printf("<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n");
		printf("<input type=\"submit\" value=\"Recommencer\">\n");
		printf("</form>\n" ) ;
		
		return 1;
	}
	else {
		// verif si le login demandé n'existe pas déjà ....
		$sql_verif="select u_login from conges_users where u_login='$new_login' ";
		$ReqLog_verif = mysql_query($sql_verif, $link) or die("ERREUR : mysql_query : \n".$sql_verif."\n --> ".mysql_error());
		$num_verif = mysql_num_rows($ReqLog_verif);
		if ($num_verif!=0)
		{
			printf("<H3> ATTENTION : login déjà utilisé, veuillez en changer ...... </H3>\n" ) ;
			printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
			printf("<input type=\"hidden\" name=\"new_login\" value=\"$new_login\">\n");
			printf("<input type=\"hidden\" name=\"new_nom\" value=\"$new_nom\">\n");
			printf("<input type=\"hidden\" name=\"new_prenom\" value=\"$new_prenom\">\n");
			printf("<input type=\"hidden\" name=\"new_jours_an\" value=\"$new_jours_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_jours\" value=\"$new_solde_jours\">\n");
			printf("<input type=\"hidden\" name=\"new_rtt_an\" value=\"$new_rtt_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_rtt\" value=\"$new_solde_rtt\">\n");
			printf("<input type=\"hidden\" name=\"new_is_resp\" value=\"$new_is_resp\">\n");
			printf("<input type=\"hidden\" name=\"new_resp_login\" value=\"$new_resp_login\">\n");
			printf("<input type=\"hidden\" name=\"new_quotite\" value=\"$new_quotite\">\n");
			printf("<input type=\"hidden\" name=\"new_email\" value=\"$new_email\">\n");

			printf("<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n");
			printf("<input type=\"submit\" value=\"Recommencer\">\n");
			printf("</form>\n" ) ;

			return 1;	
		}
		elseif(strrchr($new_email, "@")==FALSE)
		{
			printf("<H3> ATTENTION : adresse mail éronnée ...... </H3>\n" ) ;
			printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
			printf("<input type=\"hidden\" name=\"new_login\" value=\"$new_login\">\n");
			printf("<input type=\"hidden\" name=\"new_nom\" value=\"$new_nom\">\n");
			printf("<input type=\"hidden\" name=\"new_prenom\" value=\"$new_prenom\">\n");
			printf("<input type=\"hidden\" name=\"new_jours_an\" value=\"$new_jours_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_jours\" value=\"$new_solde_jours\">\n");
			printf("<input type=\"hidden\" name=\"new_rtt_an\" value=\"$new_rtt_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_rtt\" value=\"$new_solde_rtt\">\n");
			printf("<input type=\"hidden\" name=\"new_is_resp\" value=\"$new_is_resp\">\n");
			printf("<input type=\"hidden\" name=\"new_resp_login\" value=\"$new_resp_login\">\n");
			printf("<input type=\"hidden\" name=\"new_quotite\" value=\"$new_quotite\">\n");
			printf("<input type=\"hidden\" name=\"new_email\" value=\"$new_email\">\n");

			printf("<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n");
			printf("<input type=\"submit\" value=\"Recommencer\">\n");
			printf("</form>\n" ) ;

			return 1;	
		}
		else
			return 0;
	}
}



?>
<hr align="center" size="2" width="90%">
</CENTER>
</body>
</html>
