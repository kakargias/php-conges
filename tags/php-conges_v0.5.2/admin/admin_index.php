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
	if(isset($_POST['tab_checkbox_sem_imp'])) { $tab_checkbox_sem_imp=$_POST['tab_checkbox_sem_imp']; }
	if(isset($_POST['tab_checkbox_sem_p'])) { $tab_checkbox_sem_p=$_POST['tab_checkbox_sem_p']; }
	/*************************************/
	
	// titre
	printf("<H2>Administration de la DataBase : </H2>\n\n");
	//connexion mysql
	$link = connexion_mysql() ;
	
	if(isset($new_login)) {
		ajout_user();
	}
	else {
		affichage();  /* affichage normal */
	}
	
	mysql_close($link);
	
/*** FONCTIONS ***/

function affichage() {
	global $PHP_SELF, $link;
	global $config_admin_see_all , $config_responsable_virtuel, $config_rtt_comme_conges ;
	global $session;
	global $session_username ;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours;
	global $new_rtt_an, $new_solde_rtt, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;

	/*********************/
	/* Etat Utilisateurs */
	/*********************/
	// Récuperation des informations
	
	// si l'admin peut voir tous les users  OU si on est en mode "responsble virtuel" (cf config.php)
	if(($config_admin_see_all==1) || ($config_responsable_virtuel==1))   
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_passwd, u_quotite FROM conges_users ORDER BY u_nom"  ;
	else
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_passwd, u_quotite FROM conges_users WHERE u_resp_login = '$session_username' ORDER BY u_nom, u_prenom"  ;

	// AFFICHAGE TABLEAU
	printf("<h3>Etat des Utilisateurs :</h3>\n");
	printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">Nom</td><td class=\"titre\">Prenom</td><td class=\"titre\">login</td><td class=\"titre\">Quotité</td><td class=\"titre\">nb congés / an</td><td class=\"titre\">solde congés</td>");
	if($config_rtt_comme_conges==1)
		printf("<td class=\"titre\">nb rtt / an</td><td class=\"titre\">solde rtt</td>");
	printf("<td class=\"titre\">is_resp</td><td class=\"titre\">resp_login</td><td></td><td></td><td></td></tr>\n");
	$ReqLog3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error());
	while ($resultat3 = mysql_fetch_array($ReqLog3)) {
			$admin_modif_user="<a href=\"admin_modif_user.php?session=$session&u_login=".$resultat3["u_login"]."\">Modifier</a>" ;
			$admin_suppr_user="<a href=\"admin_suppr_user.php?session=$session&u_login=".$resultat3["u_login"]."\">Supprimer</a>" ;
			$admin_chg_pwd_user="<a href=\"admin_chg_pwd_user.php?session=$session&u_login=".$resultat3["u_login"]."\">Password</a>" ;
			printf("<tr>\n");
			if($config_rtt_comme_conges==1)
			{
				printf("<td class=\"histo\"><b>%s</b></td><td class=\"histo\"><b>%s</b></td><td class=\"histo\">%s</td><td class=\"histo\">%d%%</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n", 
						$resultat3["u_nom"], $resultat3["u_prenom"], $resultat3["u_login"], $resultat3["u_quotite"], $resultat3["u_nb_jours_an"], $resultat3["u_solde_jours"], $resultat3["u_nb_rtt_an"], $resultat3["u_solde_rtt"], $resultat3["u_is_resp"], $resultat3["u_resp_login"], $admin_modif_user, $admin_suppr_user, $admin_chg_pwd_user);
			}
			else
			{
				printf("<td class=\"histo\"><b>%s</b></td><td class=\"histo\"><b>%s</b></td><td class=\"histo\">%s</td><td class=\"histo\">%d%%</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n", 
						$resultat3["u_nom"], $resultat3["u_prenom"], $resultat3["u_login"], $resultat3["u_quotite"], $resultat3["u_nb_jours_an"], $resultat3["u_solde_jours"], $resultat3["u_is_resp"], $resultat3["u_resp_login"], $admin_modif_user, $admin_suppr_user, $admin_chg_pwd_user);
			}
			printf("</tr>\n");
		}
	printf("</table>\n\n");

	
	/*********************/
	/* Ajout Utilisateur */
	/*********************/
	
	printf("<br><br><br><hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	// TITRE
	printf("<H3><u>Nouvel Utilisateur :</u></H3>\n\n");

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
	
	printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"histo\">login</td><td class=\"histo\">Nom</td><td class=\"histo\">Prenom</td><td class=\"histo\">Quotité</td><td class=\"histo\">nb congés / an</td><td class=\"histo\">solde congés</td>");
	if($config_rtt_comme_conges==1)
		printf("<td class=\"histo\">nb rtt / an</td><td class=\"histo\">solde rtt</td>");
	printf("<td class=\"histo\">est_responsable</td><td class=\"histo\">responsable</td><td class=\"histo\">password</td><td class=\"histo\">password</td></tr>\n");

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

	$text_password1="<input type=\"password\" name=\"new_password1\" size=\"10\" maxlength=\"15\" value=\"\">" ;
	$text_password2="<input type=\"password\" name=\"new_password2\" size=\"10\" maxlength=\"15\" value=\"\">" ;
	$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"10\" value=\"".$new_login."\">" ;
	
	printf("<tr>\n");
	if($config_rtt_comme_conges==1)
	{
		printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n",
				$text_login, $text_nom, $text_prenom, $text_quotite, $text_jours_an, $text_solde_jours, $text_rtt_an, $text_solde_rtt, $text_is_resp, $text_resp_login, $text_password1, $text_password2);
	}
	else
	{
		printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n",
				$text_login, $text_nom, $text_prenom, $text_quotite, $text_jours_an, $text_solde_jours, $text_is_resp, $text_resp_login, $text_password1, $text_password2);
	}
	printf("</tr>\n");
	printf("</table><br>\n\n");
	
	// saisie des jours d'abscence ARTT ou temps partiel:
	saisie_jours_absence_temps_partiel($new_login, $link);
	
	echo "<br>\n";
	printf("<input type=\"submit\" value=\"Valider Nouvel Utilisateur\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;
}

function ajout_user() {
	global $PHP_SELF, $link;
	global $session;
	global  $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_rtt_an, $new_solde_rtt, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p ;
	
	if(verif_new_param()==0) 
	{
		echo "$new_login---$new_nom---$new_prenom---$new_quotite---$new_jours_an---$new_solde_jours---$new_rtt_an---$new_solde_rtt---$new_is_resp---$new_resp_login<br>\n";

		$sql1 = "INSERT INTO conges_users (u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_passwd, u_quotite ) 
				VALUES ('$new_login','$new_nom','$new_prenom', '$new_jours_an',
				'$new_solde_jours', '$new_rtt_an', '$new_solde_rtt','$new_is_resp',
				'$new_resp_login',password('$new_password1'), $new_quotite)" ;
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
	global  $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_rtt_an, $new_solde_rtt, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;

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
